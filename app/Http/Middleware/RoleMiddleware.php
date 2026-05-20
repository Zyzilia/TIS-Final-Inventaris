<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, \Closure $next, ...$roles): Response
    {
        if (!auth('api')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau kedaluwarsa',
            ], 401);
        }

        $userRole = auth('api')->user()->role;
        if (!in_array($userRole, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Peran tidak memiliki izin.',
            ], 403);
        }

        return $next($request);
    }
}
