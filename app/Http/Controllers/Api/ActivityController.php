<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ActivityController extends Controller
{
    #[OA\Get(
        path: '/api/activities',
        operationId: 'getActivityLogs',
        summary: 'Ambil daftar log aktivitas terbaru',
        description: 'Mendapatkan 10 log aktivitas terbaru dalam sistem inventaris (audit trail). Memerlukan hak akses Admin atau Staff.',
        security: [['bearerAuth' => []]],
        tags: ['Audit Trail'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil mengambil log aktivitas',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'user_id', type: 'integer', nullable: true, example: 1),
                                    new OA\Property(property: 'user_name', type: 'string', example: 'Admin'),
                                    new OA\Property(property: 'action', type: 'string', example: 'Restock'),
                                    new OA\Property(property: 'description', type: 'string', example: 'Admin menambahkan Laptop Asus ke Rak'),
                                    new OA\Property(property: 'item_type', type: 'string', example: 'gpu'),
                                    new OA\Property(property: 'amount', type: 'string', example: '+10 Unit'),
                                    new OA\Property(property: 'order_id', type: 'string', example: '#000001'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-06-08T08:00:00.000000Z'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-06-08T08:00:00.000000Z')
                                ]
                            )
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
    public function index()
    {
        $activities = ActivityLog::orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }
}

