<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/auth/login',
        operationId: 'loginUser',
        summary: 'Login untuk mendapatkan token JWT',
        description: 'Autentikasi pengguna dengan email dan password untuk mendapatkan token JWT. Token ini wajib digunakan pada header Authorization: Bearer {token} untuk mengakses endpoint yang dilindungi.',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil Login dan autentikasi sukses',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Autentikasi berhasil'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'access_token', type: 'string', example: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'),
                                new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                                new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                                new OA\Property(
                                    property: 'user',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', type: 'string', example: 'Admin'),
                                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
                                        new OA\Property(property: 'role', type: 'string', example: 'admin')
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized - Kredensial login salah (email atau password tidak valid)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Invalid email or password')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Unprocessable Content - Validasi email/password gagal',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation failed'),
                        new OA\Property(property: 'errors', type: 'object')
                    ]
                )
            )
        ]
    )]
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        // Use Auth facade with the api guard
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    #[OA\Get(
        path: '/api/auth/profile',
        operationId: 'getUserProfile',
        summary: 'Dapatkan profil user saat ini',
        description: 'Mengambil data profil pengguna yang sedang login berdasarkan token JWT yang valid.',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil mengambil profil pengguna',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Profile retrieved successfully'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Admin'),
                                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
                                new OA\Property(property: 'role', type: 'string', example: 'admin'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized - Token JWT tidak valid atau kadaluwarsa'
            )
        ]
    )]
    public function profile()
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'data' => Auth::guard('api')->user(),
        ], 200);
    }

    #[OA\Post(
        path: '/api/auth/logout',
        operationId: 'logoutUser',
        summary: 'Logout user dan invalidasi token',
        description: 'Melakukan logout pengguna yang sedang login dan mencabut/menonaktifkan token JWT saat ini.',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil logout dan menghapus sesi token',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Successfully logged out')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized - Token JWT tidak valid atau kadaluwarsa'
            )
        ]
    )]
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ], 200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'message' => 'Autentikasi berhasil',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => config('jwt.ttl') * 60,
                'user' => Auth::guard('api')->user(),
            ],
        ], 200);
    }
}
