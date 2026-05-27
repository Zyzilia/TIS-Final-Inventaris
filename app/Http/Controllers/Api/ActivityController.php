<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the recent activities.
     */
    public function index()
    {
        $activities = ActivityLog::orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $activities
        ]);
    }
}
