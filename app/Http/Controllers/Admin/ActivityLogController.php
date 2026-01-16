<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
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
