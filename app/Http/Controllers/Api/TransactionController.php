<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use App\Models\Item;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = StockTransaction::with(['item.category', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi input gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = Item::find($request->item_id);
        $type = $request->type;
        $quantity = intval($request->quantity);
        $status = $request->input('status', 'completed');
        $user = auth()->user();
        $userId = $user ? $user->id : 1;
        $userName = $user ? $user->name : 'Admin';

        // Jika transaksi outbound langsung status completed, cek stok mencukupi
        if ($type === 'out' && $status === 'completed' && $item->stock < $quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok barang tidak mencukupi untuk melakukan transaksi outbound',
            ], 400);
        }

        // Simpan transaksi
        $tx = StockTransaction::create([
            'item_id' => $item->id,
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $quantity,
            'notes' => $request->notes,
            'status' => $status,
        ]);

        // Sesuaikan stok jika statusnya langsung completed
        if ($status === 'completed') {
            if ($type === 'in') {
                $item->increment('stock', $quantity);
            } else {
                $item->decrement('stock', $quantity);
            }

            // Log ke audit trail ActivityLog
            $categoryTypes = [
                1 => 'gpu', 2 => 'cpu', 3 => 'ram', 4 => 'ssd',
                5 => 'mb', 6 => 'psu', 7 => 'case', 8 => 'cooling'
            ];
            $itemType = $categoryTypes[$item->category_id] ?? 'gpu';

            ActivityLog::create([
                'user_id' => $userId,
                'user_name' => $userName,
                'action' => $type === 'in' ? 'Restock' : 'Paid',
                'description' => "{$userName} membuat transaksi stok {$type} untuk {$item->name} sebanyak {$quantity} Unit",
                'item_type' => $itemType,
                'amount' => ($type === 'in' ? '+' : '-') . $quantity . ' Unit',
                'order_id' => '#' . str_pad($tx->id, 6, '0', STR_PAD_LEFT),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil ditambahkan',
            'data' => $tx->load(['item', 'user']),
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/api/transactions/{id}",
     *     operationId="updateTransactionStatus",
     *     summary="Update status transaksi (completed / pending)",
     *     security={{"bearerAuth":{}}},
     *     tags={"Transactions"},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID Transaksi", schema=@OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", enum={"pending", "completed"}, example="completed")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Status transaksi berhasil diperbarui"),
     *     @OA\Response(response=400, description="Penyesuaian stok gagal / stok tidak cukup"),
     *     @OA\Response(response=404, description="Transaksi tidak ditemukan")
     * )
     */
    public function update(Request $request, string $id)
    {
        $tx = StockTransaction::find($id);

        if (!$tx) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $oldStatus = $tx->status;
        $newStatus = $request->status;
        $item = Item::find($tx->item_id);
        $user = auth()->user();
        $userId = $user ? $user->id : 1;
        $userName = $user ? $user->name : 'Admin';

        if ($oldStatus !== $newStatus) {
            // Skenario 1: Pending -> Completed (Stok harus disesuaikan)
            if ($oldStatus === 'pending' && $newStatus === 'completed') {
                if ($tx->type === 'out' && $item->stock < $tx->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk menyelesaikan transaksi outbound ini',
                    ], 400);
                }

                if ($tx->type === 'in') {
                    $item->increment('stock', $tx->quantity);
                } else {
                    $item->decrement('stock', $tx->quantity);
                }
            }
            // Skenario 2: Completed -> Pending (Stok harus di-rollback)
            elseif ($oldStatus === 'completed' && $newStatus === 'pending') {
                // Rollback transaksi inbound (stok berkurang)
                if ($tx->type === 'in') {
                    if ($item->stock < $tx->quantity) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Gagal mengubah status menjadi Pending. Stok barang saat ini kurang dari kuantitas transaksi inbound ini.',
                        ], 400);
                    }
                    $item->decrement('stock', $tx->quantity);
                } 
                // Rollback transaksi outbound (stok bertambah kembali)
                else {
                    $item->increment('stock', $tx->quantity);
                }
            }

            $tx->status = $newStatus;
            $tx->save();

            // Log ke audit trail ActivityLog
            $categoryTypes = [
                1 => 'gpu', 2 => 'cpu', 3 => 'ram', 4 => 'ssd',
                5 => 'mb', 6 => 'psu', 7 => 'case', 8 => 'cooling'
            ];
            $itemType = $categoryTypes[$item->category_id] ?? 'gpu';

            ActivityLog::create([
                'user_id' => $userId,
                'user_name' => $userName,
                'action' => 'Update',
                'description' => "{$userName} mengubah status transaksi #{$tx->id} ({$tx->type}) menjadi " . ucfirst($newStatus),
                'item_type' => $itemType,
                'amount' => 'Status',
                'order_id' => '#' . str_pad($tx->id, 6, '0', STR_PAD_LEFT),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status transaksi berhasil diperbarui',
            'data' => $tx->load(['item', 'user']),
        ], 200);
    }
}
