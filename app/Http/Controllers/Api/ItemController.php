<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use OpenApi\Attributes as OA;
use App\Models\ActivityLog;

class ItemController extends Controller
{
    private function getUsdToIdrRate()
    {
        return Cache::remember('usd_to_idr_rate', 3600, function () {
            try {
                $response = Http::get('https://open.er-api.com/v6/latest/USD');
                if ($response->successful()) {
                    return $response->json()['rates']['IDR'] ?? 16000;
                }
            } catch (\Exception $e) {}
            return 16000; // Fallback
        });
    }
    #[OA\Get(
        path: '/api/items',
        operationId: 'getItemList',
        summary: 'Ambil daftar semua barang',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil data')
        ]
    )]
    public function index()
    {
        $items = Item::with(['category', 'supplier'])->get();
        $rate = $this->getUsdToIdrRate();

        $items->map(function ($item) use ($rate) {
            $item->price = ($item->price_usd * $rate) * (1 + ($item->profit_margin / 100));
            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar barang berhasil diambil',
            'data' => $items,
        ], 200);
    }

    #[OA\Post(
        path: '/api/items',
        operationId: 'storeItem',
        summary: 'Tambah barang baru (Admin Only)',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Laptop Asus'),
                    new OA\Property(property: 'sku', type: 'string', example: 'LAP-001'),
                    new OA\Property(property: 'price_usd', type: 'number', example: 1000),
                    new OA\Property(property: 'profit_margin', type: 'number', example: 10),
                    new OA\Property(property: 'weight', type: 'integer', example: 1200),
                    new OA\Property(property: 'stock', type: 'integer', example: 10)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created'),
            new OA\Response(response: 403, description: 'Forbidden')
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:items,sku',
            'price_usd' => 'required|numeric|min:0',
            'profit_margin' => 'numeric|min:0',
            'weight' => 'integer|min:0',
            'stock' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi input gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $item = Item::create($request->all());

        // Log Activity
        $user = auth()->user();
        $userName = $user ? $user->name : 'Admin';
        $categoryTypes = [
            1 => 'gpu', 2 => 'cpu', 3 => 'ram', 4 => 'ssd',
            5 => 'mb', 6 => 'psu', 7 => 'case', 8 => 'cooling'
        ];
        $itemType = $categoryTypes[$item->category_id] ?? 'gpu';

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $userName,
            'action' => 'Restock',
            'description' => "{$userName} menambahkan {$item->name} ke Rak",
            'item_type' => $itemType,
            'amount' => "+{$item->stock} Unit",
            'order_id' => '#' . str_pad($item->id, 6, '0', STR_PAD_LEFT),
        ]);

        // Create Stock Transaction
        \App\Models\StockTransaction::create([
            'item_id' => $item->id,
            'user_id' => $user?->id ?? 1,
            'type' => 'in',
            'quantity' => $item->stock,
            'notes' => 'Pemasukan barang baru via panel admin',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke inventaris',
            'data' => $item,
        ], 201);
    }

    #[OA\Get(
        path: '/api/items/{id}',
        operationId: 'getItemById',
        summary: 'Ambil detail satu barang',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID Barang', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil data'),
            new OA\Response(response: 404, description: 'Barang tidak ditemukan')
        ]
    )]
    public function show(string $id)
    {
        $item = Item::with(['category', 'supplier'])->find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        $rate = $this->getUsdToIdrRate();
        $item->price = ($item->price_usd * $rate) * (1 + ($item->profit_margin / 100));

        return response()->json([
            'success' => true,
            'data' => $item,
        ], 200);
    }

    #[OA\Put(
        path: '/api/items/{id}',
        operationId: 'updateItem',
        summary: 'Update data barang (Admin Only)',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID Barang', schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer', example: 1),
                    new OA\Property(property: 'name', type: 'string', example: 'Laptop Asus ROG'),
                    new OA\Property(property: 'sku', type: 'string', example: 'LAP-001'),
                    new OA\Property(property: 'price_usd', type: 'number', example: 1100),
                    new OA\Property(property: 'profit_margin', type: 'number', example: 12),
                    new OA\Property(property: 'weight', type: 'integer', example: 1250),
                    new OA\Property(property: 'stock', type: 'integer', example: 12)
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Berhasil diupdate'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Barang tidak ditemukan')
        ]
    )]
    public function update(Request $request, string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'brand' => 'nullable|string|max:255',
            'name' => 'string|max:255',
            'sku' => 'string|unique:items,sku,'.$id,
            'price_usd' => 'numeric|min:0',
            'profit_margin' => 'numeric|min:0',
            'weight' => 'integer|min:0',
            'stock' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi update gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $oldStock = $item->stock;
        $item->update($request->all());
        $newStock = $item->stock;

        // Log Activity
        $user = auth()->user();
        $userName = $user ? $user->name : 'Admin';
        $categoryTypes = [
            1 => 'gpu', 2 => 'cpu', 3 => 'ram', 4 => 'ssd',
            5 => 'mb', 6 => 'psu', 7 => 'case', 8 => 'cooling'
        ];
        $itemType = $categoryTypes[$item->category_id] ?? 'gpu';

        if ($oldStock != $newStock) {
            $diff = $newStock - $oldStock;
            $diffStr = ($diff > 0 ? '+' : '') . $diff . ' Unit';
            ActivityLog::create([
                'user_id' => $user?->id,
                'user_name' => $userName,
                'action' => $diff > 0 ? 'Restock' : 'Audit',
                'description' => "{$userName} mengubah stok {$item->name} ({$oldStock} -> {$newStock})",
                'item_type' => $itemType,
                'amount' => $diffStr,
                'order_id' => '#' . str_pad($item->id, 6, '0', STR_PAD_LEFT),
            ]);

            // Create Stock Transaction
            \App\Models\StockTransaction::create([
                'item_id' => $item->id,
                'user_id' => $user?->id ?? 1,
                'type' => $diff > 0 ? 'in' : 'out',
                'quantity' => abs($diff),
                'notes' => $diff > 0 ? 'Restock barang via panel edit' : 'Pengurangan barang/audit via panel edit',
            ]);
        } else {
            ActivityLog::create([
                'user_id' => $user?->id,
                'user_name' => $userName,
                'action' => 'Update',
                'description' => "{$userName} memperbarui informasi {$item->name}",
                'item_type' => $itemType,
                'amount' => 'Info',
                'order_id' => '#' . str_pad($item->id, 6, '0', STR_PAD_LEFT),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Informasi barang berhasil diperbarui',
            'data' => $item,
        ], 200);
    }

    #[OA\Delete(
        path: '/api/items/{id}',
        operationId: 'deleteItem',
        summary: 'Hapus data barang (Admin Only)',
        security: [['bearerAuth' => []]],
        tags: ['Inventory'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'ID Barang', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil dihapus'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Barang tidak ditemukan')
        ]
    )]
    public function destroy(string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        // Log Activity before deletion
        $user = auth()->user();
        $userName = $user ? $user->name : 'Admin';
        $categoryTypes = [
            1 => 'gpu', 2 => 'cpu', 3 => 'ram', 4 => 'ssd',
            5 => 'mb', 6 => 'psu', 7 => 'case', 8 => 'cooling'
        ];
        $itemType = $categoryTypes[$item->category_id] ?? 'gpu';

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $userName,
            'action' => 'Delete',
            'description' => "{$userName} menghapus {$item->name} dari Rak",
            'item_type' => $itemType,
            'amount' => "-{$item->stock} Unit",
            'order_id' => '#' . str_pad($item->id, 6, '0', STR_PAD_LEFT),
        ]);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus permanen',
        ], 200);
    }
}
