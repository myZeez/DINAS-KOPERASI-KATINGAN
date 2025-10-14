<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::with(['user'])
            ->latest('created_at');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Get filter options
        $actions = ActivityLog::distinct()->pluck('action')->filter()->sort();
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->filter()->sort();
        $users = ActivityLog::select('users.id', 'users.name')
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->distinct()
            ->orderBy('users.name')
            ->get();

        return view('admin.activity-logs.index', compact(
            'logs',
            'actions',
            'modelTypes',
            'users'
        ));
    }

    public function show(ActivityLog $activityLog): View
    {
        $activityLog->load(['user']);

        return view('admin.activity-logs.show', compact('activityLog'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Log aktivitas berhasil dihapus');
    }

    public function clear(Request $request)
    {
        $request->validate([
            'confirm' => 'required|accepted'
        ]);

        ActivityLog::truncate();

        return redirect()->route('admin.activity-logs.index')
            ->with('success', 'Semua log aktivitas berhasil dihapus');
    }
}
