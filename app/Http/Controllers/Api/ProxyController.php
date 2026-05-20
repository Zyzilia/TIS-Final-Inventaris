<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ProxyController extends Controller
{
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
}
