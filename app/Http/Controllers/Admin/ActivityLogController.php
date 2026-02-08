<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ActivityLogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:admin'),
        ];
    }
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by model type
        if ($request->has('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by action
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(50);

        return Inertia::render('Admin/ActivityLogs/Index', [
            'logs' => $logs,
            'filters' => $request->only(['model_type', 'action']),
        ]);
    }
}
