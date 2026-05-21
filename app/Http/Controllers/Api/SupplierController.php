<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
                'id' => $supplier->id,
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

    /**
     * @OA\Post(
     *     path="/api/suppliers",
     *     operationId="storeSupplier",
     *     summary="Tambah supplier baru",
     *     security={{"bearerAuth":{}}},
     *     tags={"Partners"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "address", "phone"},
     *             @OA\Property(property="name", type="string", example="Gigabyte Technology"),
     *             @OA\Property(property="address", type="string", example="New Taipei City, Taiwan"),
     *             @OA\Property(property="phone", type="string", example="+886-2-8912-4000")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Supplier berhasil ditambahkan"),
     *     @OA\Response(response=422, description="Validasi input gagal"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:suppliers,name|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi input gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier = Supplier::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil ditambahkan',
            'data' => [
                'id' => $supplier->id,
                'name' => $supplier->name,
                'address' => $supplier->address,
                'phone' => $supplier->phone,
                'category' => 'General'
            ]
        ], 201);
    }
}
