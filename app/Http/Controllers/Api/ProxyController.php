<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class ProxyController extends Controller
{
    #[OA\Get(
        path: '/api/proxy/provinces',
        operationId: 'getProvinces',
        summary: 'Ambil daftar provinsi dari RajaOngkir',
        security: [['bearerAuth' => []]],
        tags: ['Logistics Gateway'],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil daftar provinsi'),
            new OA\Response(response: 500, description: 'Kesalahan Server')
        ]
    )]
    public function getProvinces()
    {
        try {
            $response = Http::withHeaders([
                'key' => config('services.rajaongkir.key') ?? env('RAJAONGKIR_API_KEY'),
            ])->get(env('RAJAONGKIR_BASE_URL').'/province');

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data dari pihak ketiga',
                    'errors' => $response->json()['rajaongkir']['status'] ?? null,
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar provinsi berhasil diambil',
                'data' => $response->json()['rajaongkir']['results'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server gateway',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    #[OA\Get(
        path: '/api/proxy/cities',
        operationId: 'getCities',
        summary: 'Ambil daftar kota/kabupaten berdasarkan provinsi',
        security: [['bearerAuth' => []]],
        tags: ['Logistics Gateway'],
        parameters: [
            new OA\Parameter(name: 'province', in: 'query', required: true, description: 'ID Provinsi', schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil daftar kota'),
            new OA\Response(response: 500, description: 'Kesalahan Server')
        ]
    )]
    public function getCities(Request $request)
    {
        try {
            $provinceId = $request->query('province');

            $response = Http::withHeaders([
                'key' => config('services.rajaongkir.key') ?? env('RAJAONGKIR_API_KEY'),
            ])->get(env('RAJAONGKIR_BASE_URL').'/city', [
                'province' => $provinceId,
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data kota',
                    'errors' => $response->json()['rajaongkir']['status'] ?? null,
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar kota berhasil diambil',
                'data' => $response->json()['rajaongkir']['results'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server gateway',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    #[OA\Post(
        path: '/api/proxy/shipping-cost',
        operationId: 'checkShipping',
        summary: 'Cek ongkir via RajaOngkir Proxy',
        security: [['bearerAuth' => []]],
        tags: ['Logistics Gateway'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'origin', type: 'string', example: '501'),
                    new OA\Property(property: 'destination', type: 'string', example: '114'),
                    new OA\Property(property: 'weight', type: 'integer', example: 1000),
                    new OA\Property(property: 'courier', type: 'string', example: 'jne')
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Success')
        ]
    )]
    public function checkCost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin' => 'required|string',
            'destination' => 'required|string',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string|in:jne,pos,tiki',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi parameter ongkir gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $response = Http::withHeaders([
                'key' => config('services.rajaongkir.key') ?? env('RAJAONGKIR_API_KEY'),
            ])->post(env('RAJAONGKIR_BASE_URL').'/cost', [
                'origin' => $request->origin,
                'destination' => $request->destination,
                'weight' => $request->weight,
                'courier' => $request->courier,
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghitung biaya pengiriman',
                    'errors' => $response->json()['rajaongkir']['status'] ?? null,
                ], $response->status());
            }

            return response()->json([
                'success' => true,
                'message' => 'Kalkulasi biaya pengiriman berhasil',
                'data' => $response->json()['rajaongkir']['results'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan kalkulasi pada server gateway',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    #[OA\Get(
        path: '/api/proxy/currency-rates',
        operationId: 'getCurrencyRates',
        summary: 'Ambil kurs mata uang asing (USD ke IDR) untuk import parts',
        security: [['bearerAuth' => []]],
        tags: ['Finance Gateway'],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil mengambil kurs'),
            new OA\Response(response: 500, description: 'Kesalahan Server')
        ]
    )]
    public function getCurrencyRates()
    {
        try {
            // Menggunakan API gratis open.er-api.com
            $response = Http::get('https://open.er-api.com/v6/latest/USD');

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data kurs mata uang',
                ], $response->status());
            }

            $data = $response->json();

            return response()->json([
                'success' => true,
                'message' => 'Kurs berhasil diambil',
                'data' => [
                    'base' => $data['base_code'],
                    'rates' => [
                        'IDR' => $data['rates']['IDR'] ?? null,
                        'SGD' => $data['rates']['SGD'] ?? null,
                        'CNY' => $data['rates']['CNY'] ?? null,
                    ],
                    'last_updated' => $data['time_last_update_utc'],
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server currency gateway',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
