<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Get filter parameters from request
        $search = $request->get('search', '');
        $module = $request->get('module', '');
        $action = $request->get('action', '');
        $sortOrder = $request->get('sortOrder', 'desc');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $perPage = $request->get('perPage', 15);

        // Build query
        $query = AuditLog::with('user');

        // Apply search filter - search across multiple fields
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                // Search in user's first name or last name (checks individual fields)
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                })
                    // Also search in other audit log fields
                    ->orWhere('action', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply module filter (works with or without search)
        if (!empty($module)) {
            $query->where('module', $module);
        }

        // Apply action filter (works with or without search)
        if (!empty($action)) {
            $query->where('action', $action);
        }

        // Apply date range filter
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // Apply sorting (always by created_at)
        $query->orderBy('created_at', $sortOrder);

        // Paginate results
        $auditLogs = $query->paginate($perPage);

        // If HTMX request, return only table partial
        if ($request->header('HX-Request')) {
            return view('DASHBOARD.audit-table', compact('auditLogs', 'search', 'module', 'action', 'sortOrder', 'dateFrom', 'dateTo'));
        }

        // Regular request, return full page
        return view('DASHBOARD.audit', compact('auditLogs', 'search', 'module', 'action', 'sortOrder', 'dateFrom', 'dateTo'));
    }
}
