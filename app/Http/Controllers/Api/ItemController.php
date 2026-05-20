<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['category', 'supplier'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar barang berhasil diambil',
            'data' => $items,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:items,sku',
            'price' => 'required|numeric|min:0',
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

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil ditambahkan ke inventaris',
            'data' => $item,
        ], 201);
    }

    public function show(string $id)
    {
        $item = Item::with(['category', 'supplier'])->find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $item,
        ], 200);
    }

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
            'name' => 'string|max:255',
            'sku' => 'string|unique:items,sku,'.$id,
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi update gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $item->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Informasi barang berhasil diperbarui',
            'data' => $item,
        ], 200);
    }

    public function destroy(string $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang berhasil dihapus permanen',
        ], 200);
    }
}
