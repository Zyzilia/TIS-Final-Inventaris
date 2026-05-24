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
        security: [['bearerAuth' => []]],
        tags: ['Logistics Gateway']
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
