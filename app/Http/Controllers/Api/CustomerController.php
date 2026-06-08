<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class CustomerController extends Controller
{
    #[OA\Get(
        path: '/api/customers',
        operationId: 'getCustomerList',
        summary: 'Ambil daftar customer',
        description: 'Mendapatkan seluruh daftar customer/partner penerima barang. Memerlukan hak akses Admin atau Staff.',
        security: [['bearerAuth' => []]],
        tags: ['Partners - Customers'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil mengambil data customer',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'Hyperion Tech'),
                                    new OA\Property(property: 'type', type: 'string', example: 'Wholesale Distributor'),
                                    new OA\Property(property: 'phone', type: 'string', example: '+62-812-9999-8888'),
                                    new OA\Property(property: 'location', type: 'string', example: 'Yogyakarta, Indonesia'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-06-08T08:00:00.000000Z'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-06-08T08:00:00.000000Z')
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
        $customers = Customer::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $customers
        ]);
    }

    #[OA\Post(
        path: '/api/customers',
        operationId: 'storeCustomer',
        summary: 'Tambah customer baru',
        description: 'Menambahkan data customer baru ke dalam database. Memerlukan hak akses Admin.',
        security: [['bearerAuth' => []]],
        tags: ['Partners - Customers'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'type', 'phone', 'location'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Hyperion Tech'),
                    new OA\Property(property: 'type', type: 'string', example: 'Wholesale Distributor'),
                    new OA\Property(property: 'phone', type: 'string', example: '+62-812-9999-8888'),
                    new OA\Property(property: 'location', type: 'string', example: 'Yogyakarta, Indonesia')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Customer berhasil ditambahkan',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Customer berhasil ditambahkan'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Hyperion Tech'),
                                new OA\Property(property: 'type', type: 'string', example: 'Wholesale Distributor'),
                                new OA\Property(property: 'phone', type: 'string', example: '+62-812-9999-8888'),
                                new OA\Property(property: 'location', type: 'string', example: 'Yogyakarta, Indonesia'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time')
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
            'name' => 'required|string|unique:customers,name|max:255',
            'type' => 'required|string|max:100',
            'phone' => 'required|string|max:50',
            'location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi input gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil ditambahkan',
            'data' => $customer
        ], 201);
    }

    #[OA\Put(
        path: '/api/customers/{id}',
        operationId: 'updateCustomer',
        summary: 'Update data customer',
        description: 'Memperbarui data customer berdasarkan ID. Memerlukan hak akses Admin.',
        security: [['bearerAuth' => []]],
        tags: ['Partners - Customers'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID Customer',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Hyperion Tech Indo'),
                    new OA\Property(property: 'type', type: 'string', example: 'Retail Distributor'),
                    new OA\Property(property: 'phone', type: 'string', example: '+62-812-9999-7777'),
                    new OA\Property(property: 'location', type: 'string', example: 'Sleman, Yogyakarta')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Customer berhasil diperbarui',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Customer berhasil diperbarui'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Hyperion Tech Indo'),
                                new OA\Property(property: 'type', type: 'string', example: 'Retail Distributor'),
                                new OA\Property(property: 'phone', type: 'string', example: '+62-812-9999-7777'),
                                new OA\Property(property: 'location', type: 'string', example: 'Sleman, Yogyakarta')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 403, description: 'Forbidden - Hanya untuk Admin'),
            new OA\Response(response: 404, description: 'Customer tidak ditemukan'),
            new OA\Response(response: 422, description: 'Validasi update gagal')
        ]
    )]
    public function update(Request $request, string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255|unique:customers,name,' . $id,
            'type' => 'string|max:100',
            'phone' => 'string|max:50',
            'location' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi update gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil diperbarui',
            'data' => $customer
        ], 200);
    }

    #[OA\Delete(
        path: '/api/customers/{id}',
        operationId: 'deleteCustomer',
        summary: 'Hapus customer',
        description: 'Menghapus data customer dari database berdasarkan ID. Memerlukan hak akses Admin.',
        security: [['bearerAuth' => []]],
        tags: ['Partners - Customers'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'ID Customer',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Customer berhasil dihapus',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Customer berhasil dihapus')
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 403, description: 'Forbidden - Hanya untuk Admin'),
            new OA\Response(response: 404, description: 'Customer tidak ditemukan')
        ]
    )]
    public function destroy(string $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer tidak ditemukan'
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil dihapus'
        ], 200);
    }
}
