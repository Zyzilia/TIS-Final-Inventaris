<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class SupplierController extends Controller
{
    #[OA\Get(
        path: '/api/suppliers',
        operationId: 'getSupplierList',
        summary: 'Ambil daftar supplier beserta kategori komponen yang dipasok',
        description: 'Mendapatkan seluruh daftar supplier/partner pemasok barang beserta kategori komponen yang mereka pasok. Memerlukan hak akses Admin atau Staff.',
        security: [['bearerAuth' => []]],
        tags: ['Partners - Suppliers'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil mengambil data supplier',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Gigabyte Technology'),
                                    new OA\Property(property: 'address', type: 'string', example: 'New Taipei City, Taiwan'),
                                    new OA\Property(property: 'phone', type: 'string', example: '+886-2-8912-4000'),
                                    new OA\Property(property: 'category', type: 'string', example: 'Motherboard & GPU')
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized')
        ]
    )]
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

    #[OA\Post(
        path: '/api/suppliers',
        operationId: 'storeSupplier',
        summary: 'Tambah supplier baru',
        description: 'Menambahkan data supplier baru ke dalam database. Memerlukan hak akses Admin.',
        security: [['bearerAuth' => []]],
        tags: ['Partners - Suppliers'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'address', 'phone'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Gigabyte Technology'),
                    new OA\Property(property: 'address', type: 'string', example: 'New Taipei City, Taiwan'),
                    new OA\Property(property: 'phone', type: 'string', example: '+886-2-8912-4000')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Supplier berhasil ditambahkan',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Supplier berhasil ditambahkan'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Gigabyte Technology'),
                                new OA\Property(property: 'address', type: 'string', example: 'New Taipei City, Taiwan'),
                                new OA\Property(property: 'phone', type: 'string', example: '+886-2-8912-4000'),
                                new OA\Property(property: 'category', type: 'string', example: 'General')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 403, description: 'Forbidden - Hanya untuk Admin'),
            new OA\Response(response: 422, description: 'Validasi input gagal')
        ]
    )]
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

    #[OA\Put(
        path: '/api/suppliers/{id}',
        operationId: 'updateSupplier',
        summary: 'Update data supplier',
        description: 'Memperbarui data supplier berdasarkan ID. Memerlukan hak akses Admin.',
        security: [['bearerAuth' => []]],
        tags: ['Partners - Suppliers'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID Supplier',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Gigabyte Tech Co Ltd'),
                    new OA\Property(property: 'address', type: 'string', example: 'Taipei, Taiwan'),
                    new OA\Property(property: 'phone', type: 'string', example: '+886-2-8912-4001')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Supplier berhasil diperbarui',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Supplier berhasil diperbarui'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Gigabyte Tech Co Ltd'),
                                new OA\Property(property: 'address', type: 'string', example: 'Taipei, Taiwan'),
                                new OA\Property(property: 'phone', type: 'string', example: '+886-2-8912-4001')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 403, description: 'Forbidden - Hanya untuk Admin'),
            new OA\Response(response: 404, description: 'Supplier tidak ditemukan'),
            new OA\Response(response: 422, description: 'Validasi update gagal')
        ]
    )]
    public function update(Request $request, string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255|unique:suppliers,name,' . $id,
            'address' => 'string',
            'phone' => 'string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi update gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $supplier->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil diperbarui',
            'data' => $supplier
        ], 200);
    }

    #[OA\Delete(
        path: '/api/suppliers/{id}',
        operationId: 'deleteSupplier',
        summary: 'Hapus supplier',
        description: 'Menghapus data supplier dari database berdasarkan ID. Memerlukan hak akses Admin.',
        security: [['bearerAuth' => []]],
        tags: ['Partners - Suppliers'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID Supplier',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Supplier berhasil dihapus',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Supplier berhasil dihapus')
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 403, description: 'Forbidden - Hanya untuk Admin'),
            new OA\Response(response: 404, description: 'Supplier tidak ditemukan')
        ]
    )]
    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);

        if (!$supplier) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan'
            ], 404);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil dihapus'
        ], 200);
    }
}
