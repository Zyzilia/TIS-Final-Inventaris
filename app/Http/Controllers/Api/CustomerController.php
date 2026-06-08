<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class CustomerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/customers",
     *     operationId="getCustomerList",
     *     summary="Ambil daftar customer",
     *     security={{"bearerAuth":{}}},
     *     tags={"Partners"},
     *     @OA\Response(response=200, description="Berhasil mengambil data customer"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        $customers = Customer::orderBy('name', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $customers
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/customers",
     *     operationId="storeCustomer",
     *     summary="Tambah customer baru",
     *     security={{"bearerAuth":{}}},
     *     tags={"Partners"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "type", "phone", "location"},
     *             @OA\Property(property="name", type="string", example="Hyperion Tech"),
     *             @OA\Property(property="type", type="string", example="Wholesale Distributor"),
     *             @OA\Property(property="phone", type="string", example="+62-812-9999-8888"),
     *             @OA\Property(property="location", type="string", example="Yogyakarta, Indonesia")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Customer berhasil ditambahkan"),
     *     @OA\Response(response=422, description="Validasi input gagal"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
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
