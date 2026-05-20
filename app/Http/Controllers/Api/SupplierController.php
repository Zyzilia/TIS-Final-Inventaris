<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class SupplierController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/suppliers",
     *     operationId="getSupplierList",
     *     summary="Ambil daftar supplier beserta kategori komponen yang dipasok",
     *     security={{"bearerAuth":{}}},
     *     tags={"Partners"},
     *     @OA\Response(response=200, description="Berhasil mengambil data supplier"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $suppliers = Supplier::with('items.category')->get()->map(function ($supplier) {
            $lowerName = strtolower($supplier->name);
            $categories = $supplier->items->map(function ($item) {
                return $item->category ? $item->category->name : null;
            })->filter()->unique()->values()->all();

            // Format category name based on seeded categories
            $categoryLabel = 'General';
            if (str_contains($lowerName, 'amd')) {
                $categoryLabel = 'CPU & GPU';
            } elseif (!empty($categories)) {
                $categoryLabel = implode(' & ', $categories);
            } else {
                // Default labels matching standard PC components WMS setup
                if (str_contains($lowerName, 'nvidia')) {
                    $categoryLabel = 'GPU';
                } elseif (str_contains($lowerName, 'intel')) {
                    $categoryLabel = 'CPU & GPU';
                } elseif (str_contains($lowerName, 'corsair')) {
                    $categoryLabel = 'RAM & PSU';
                } elseif (str_contains($lowerName, 'samsung')) {
                    $categoryLabel = 'Storage';
                } elseif (str_contains($lowerName, 'asus')) {
                    $categoryLabel = 'Motherboard';
                } elseif (str_contains($lowerName, 'nzxt')) {
                    $categoryLabel = 'Case & Cooling';
                }
            }

            return [
                'name' => $supplier->name,
                'address' => $supplier->address,
                'phone' => $supplier->phone,
                'category' => $categoryLabel
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $suppliers
        ]);
    }
}
