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
        path: '/api/proxy/areas',
        operationId: 'searchAreas',
        summary: 'Cari Area Biteship (Kecamatan/Kodepos)',
        description: 'Mencari area di Indonesia menggunakan Biteship API berdasarkan nama kecamatan atau kode pos (min 3 karakter). Memerlukan hak akses Admin atau Staff.',
        security: [['bearerAuth' => []]],
        tags: ['Logistics Gateway'],
        parameters: [
            new OA\Parameter(
                name: 'q',
                in: 'query',
                required: true,
                description: 'Nama kecamatan atau kode pos',
                schema: new OA\Schema(type: 'string', minLength: 3, example: 'Mampang')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil melakukan pencarian area',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'string', example: 'ID-12345'),
                                    new OA\Property(property: 'name', type: 'string', example: 'Mampang Prapatan'),
                                    new OA\Property(property: 'postal_code', type: 'integer', example: 12790),
                                    new OA\Property(property: 'country', type: 'string', example: 'ID')
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized - Token JWT tidak valid atau kadaluwarsa'
            ),
            new OA\Response(
                response: 500,
                description: 'Gagal mencari area karena kendala gateway api'
            )
        ]
    )]
    public function searchAreas(Request $request)
    {
        try {
            $query = $request->query('q');
            if (!$query || strlen($query) < 3) {
                return response()->json(['success' => true, 'data' => []], 200);
            }

            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . config('services.biteship.key'),
            ])->get(config('services.biteship.base_url') . '/maps/areas', [
                'countries' => 'ID',
                'input' => $query,
                'type' => 'single'
            ]);

            return response()->json([
                'success' => true,
                'data' => $response->json()['areas'] ?? []
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencari area',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[OA\Post(
        path: '/api/proxy/shipping-cost',
        operationId: 'checkShipping',
        summary: 'Cek ongkir via Biteship Proxy',
        description: 'Mendapatkan tarif pengiriman berdasarkan asal, tujuan, berat, dan kurir melalui Biteship API yang diformat menyerupai RajaOngkir. Memerlukan hak akses Admin atau Staff.',
        security: [['bearerAuth' => []]],
        tags: ['Logistics Gateway'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['origin', 'destination', 'weight', 'courier'],
                properties: [
                    new OA\Property(property: 'origin', type: 'string', description: 'ID Area Biteship Asal', example: '501'),
                    new OA\Property(property: 'destination', type: 'string', description: 'ID Area Biteship Tujuan', example: '114'),
                    new OA\Property(property: 'weight', type: 'integer', description: 'Berat barang dalam gram', example: 1000),
                    new OA\Property(property: 'courier', type: 'string', description: 'Kode kurir (misal: jne, sicepat, jnt)', example: 'jne')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil melakukan kalkulasi ongkos kirim',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Kalkulasi biaya pengiriman berhasil via Biteship'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'name', type: 'string', example: 'JNE'),
                                    new OA\Property(
                                        property: 'costs',
                                        type: 'array',
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(property: 'service', type: 'string', example: 'REG'),
                                                new OA\Property(property: 'description', type: 'string', example: 'Layanan Reguler'),
                                                new OA\Property(
                                                    property: 'cost',
                                                    type: 'array',
                                                    items: new OA\Items(
                                                        properties: [
                                                            new OA\Property(property: 'value', type: 'integer', example: 12000),
                                                            new OA\Property(property: 'etd', type: 'string', example: '1-2 hari')
                                                        ]
                                                    )
                                                )
                                            ]
                                        )
                                    )
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized - Token JWT tidak valid atau kadaluwarsa'
            ),
            new OA\Response(
                response: 422,
                description: 'Validasi parameter input gagal'
            ),
            new OA\Response(
                response: 500,
                description: 'Terjadi kesalahan kalkulasi pada server gateway'
            )
        ]
    )]
    public function checkCost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'origin' => 'required|string',
            'destination' => 'required|string',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi parameter ongkir gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . config('services.biteship.key'),
                'Content-Type'  => 'application/json'
            ])->post(config('services.biteship.base_url') . '/rates/couriers', [
                'origin_area_id' => $request->origin,
                'destination_area_id' => $request->destination,
                'couriers' => $request->courier,
                'items' => [
                    [
                        'name' => 'Barang Inventaris',
                        'value' => 50000,
                        'weight' => (int) $request->weight,
                        'quantity' => 1
                    ]
                ]
            ]);

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghitung biaya pengiriman',
                    'errors' => $response->json(),
                ], $response->status());
            }

            $biteshipData = $response->json()['pricing'] ?? [];
            
            // Format to match RajaOngkir structure for the frontend
            $mappedCosts = [];
            foreach($biteshipData as $rate) {
                $mappedCosts[] = [
                    'service' => strtoupper($rate['courier_service_code']),
                    'description' => $rate['courier_service_name'],
                    'cost' => [
                        [
                            'value' => $rate['price'],
                            'etd' => str_replace('days', 'hari', $rate['duration'] ?? '1-3')
                        ]
                    ]
                ];
            }

            $rajaongkirFormat = [
                [
                    'name' => count($biteshipData) > 0 ? $biteshipData[0]['courier_name'] : strtoupper($request->courier),
                    'costs' => $mappedCosts
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Kalkulasi biaya pengiriman berhasil via Biteship',
                'data' => $rajaongkirFormat,
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
        summary: 'Ambil kurs mata uang asing (USD ke IDR)',
        description: 'Mendapatkan kurs konversi mata uang asing real-time (USD, SGD, CNY ke IDR) untuk kebutuhan impor barang/komponen. Memerlukan hak akses Admin atau Staff.',
        security: [['bearerAuth' => []]],
        tags: ['Finance Gateway'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Kurs berhasil diambil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Kurs berhasil diambil'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'base', type: 'string', example: 'USD'),
                                new OA\Property(
                                    property: 'rates',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'IDR', type: 'number', example: 16250.5),
                                        new OA\Property(property: 'SGD', type: 'number', example: 1.35),
                                        new OA\Property(property: 'CNY', type: 'number', example: 7.24)
                                    ]
                                ),
                                new OA\Property(property: 'last_updated', type: 'string', example: 'Mon, 08 Jun 2026 00:00:00 +0000')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            ),
            new OA\Response(
                response: 500,
                description: 'Terjadi kesalahan pada server currency gateway'
            )
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
