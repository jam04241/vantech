<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceType;
use App\Http\Requests\ServiceRequest;
use App\Traits\LoadsBrandData;
use App\Traits\LogsAuditTrail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServicesController extends Controller
{
    use LoadsBrandData, LogsAuditTrail;
    /**
     * Display a listing of all services.
     */
    public function index()
    {
        $services = Service::with(['customer', 'serviceType'])->get();
        $serviceTypes = ServiceType::all();
        $customers = \App\Models\Customer::all();

        return view('ServicesOrder.Services', [
            'services' => $services,
            'serviceTypes' => $serviceTypes,
            'customers' => $customers,
        ]);
    }
    public function getserviceRecords(Request $request)
    {
        // Base query for services with relationships
        $query = Service::with(['customer', 'serviceType'])
            ->whereIn('status', ['Completed', 'Pending', 'On Hold', 'In Progress']);

        // Date range filtering
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }

        // Status filtering
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Enhanced search filtering (all table content)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Search customer names (first name, last name, full name)
                $q->whereHas('customer', function ($customerQuery) use ($search) {
                    $customerQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                })
                    // Search service type
                    ->orWhereHas('serviceType', function ($serviceQuery) use ($search) {
                        $serviceQuery->where('name', 'like', "%{$search}%");
                    })
                    // Search item details
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    // Search status
                    ->orWhere('status', 'like', "%{$search}%")
                    // Search total price (if numeric)
                    ->orWhere('total_price', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Get statistics based on current filters
        $statsQuery = Service::query()
            ->whereIn('status', ['Completed', 'Pending', 'On Hold', 'In Progress']);

        if ($startDate && $endDate) {
            $statsQuery->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        }

        $statistics = [
            'total' => $statsQuery->count(),
            'pending' => $statsQuery->clone()->where('status', 'Pending')->count(),
            'completed' => $statsQuery->clone()->where('status', 'Completed')->count(),
            'in_progress' => $statsQuery->clone()->where('status', 'In Progress')->count(),
            'on_hold' => $statsQuery->clone()->where('status', 'On Hold')->count(),
            'total_revenue' => $statsQuery->clone()->where('status', 'Completed')->sum('total_price')
        ];

        // Check if this is an HTMX request
        if ($request->header('HX-Request')) {
            // For HTMX requests, paginate and return partial view
            $services = $query->paginate(25);

            // Prepare variables for the partial views
            $search = $request->input('customer_search', '');
            $status = $request->input('status_filter', '');
            $sortOrder = $request->input('sort_order', 'newest');

            // Return partial view for table only
            if ($request->has('target') && $request->target === 'table') {
                return view('partials.service_recordTable', compact('services'));
            }

            // Return partial view for statistics only
            if ($request->has('target') && $request->target === 'stats') {
                return view('partials.service_recordStats', compact('statistics', 'startDate', 'endDate'));
            }

            // Default: return table view for HTMX requests
            return view('partials.service_recordTable', compact('services'));
        }

        // For regular requests, return full page
        $services = $query->paginate(25);
        $search = $request->input('customer_search', '');
        $status = $request->input('status_filter', '');
        $sortOrder = $request->input('sort_order', 'newest');

        return view('DASHBOARD.service_record', compact(
            'services',
            'statistics',
            'startDate',
            'endDate',
            'search',
            'status',
            'sortOrder'
        ));
    }
    /**
     * Store a newly created service in database.
     */
    public function store(ServiceRequest $request)
    {
        $service = Service::create($request->validated());

        // Load relationships
        $service->load(['customer', 'serviceType']);

        // Log the audit trail for adding service
        $customerName = $service->customer ? $service->customer->first_name . ' ' . $service->customer->last_name : 'Unknown';
        $serviceName = $service->serviceType ? $service->serviceType->name : 'Unknown';
        $fee = $service->serviceType ? $service->serviceType->price : 0;

        $this->logAddServiceListAudit($customerName, $serviceName, $fee, $request);

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully.',
            'service' => $service
        ]);
    }

    /**
     * Show the specified service with its replacements.
     */
    public function show(Service $service)
    {
        // Load customer, serviceType, and replacements relationships
        $service->load(['customer', 'serviceType', 'replacements']);

        return response()->json([
            'id' => $service->id,
            'customer_id' => $service->customer_id,
            'service_type_id' => $service->service_type_id,
            'customer' => $service->customer ? [
                'id' => $service->customer->id,
                'first_name' => $service->customer->first_name,
                'last_name' => $service->customer->last_name,
            ] : null,
            'serviceType' => $service->serviceType ? [
                'id' => $service->serviceType->id,
                'name' => $service->serviceType->name,
                'price' => $service->serviceType->price,
            ] : null,
            'type' => $service->type,
            'brand' => $service->brand,
            'model' => $service->model,
            'date_in' => $service->date_in,
            'date_out' => $service->date_out,
            'description' => $service->description,
            'action' => $service->action,
            'status' => $service->status,
            'total_price' => $service->total_price,
            'replacements' => $service->replacements ?? [],
            'created_at' => $service->created_at,
            'updated_at' => $service->updated_at,
        ]);
    }

    /**
     * Update the specified service in database.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        // Load relationships but exclude replacements to avoid table-not-found errors
        $service->load(['customer', 'serviceType']);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully.',
            'service' => $service
        ]);
    }

    /**
     * Archive (cancel) the specified service.
     */
    public function archive(Service $service)
    {
        $service->update(['status' => 'Canceled']);

        return response()->json([
            'success' => true,
            'message' => 'Service archived successfully.',
            'service' => $service
        ]);
    }

    /**
     * Remove the specified service from database.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully.'
        ]);
    }

    /**
     * Log Acknowledgment Receipt issuance
     */
    public function logAcknowledgmentReceipt(Request $request, Service $service)
    {
        $service->load(['customer', 'serviceType']);

        $customerName = $service->customer ? $service->customer->first_name . ' ' . $service->customer->last_name : 'Unknown';
        $serviceName = $service->serviceType ? $service->serviceType->name : 'Unknown';

        $this->logAcknowledgmentReceiptAudit($customerName, $serviceName, $request);

        return response()->json([
            'success' => true,
            'message' => 'Acknowledgment receipt logged successfully.'
        ]);
    }

    /**
     * Log Service Receipt issuance
     */
    public function logServiceReceipt(Request $request, Service $service)
    {
        $service->load(['customer', 'serviceType']);

        $customerName = $service->customer ? $service->customer->first_name . ' ' . $service->customer->last_name : 'Unknown';
        $serviceName = $service->serviceType ? $service->serviceType->name : 'Unknown';
        $totalAmount = $service->total_price ?? 0;

        $this->logServiceReceiptAudit($customerName, $serviceName, $totalAmount, $request);

        return response()->json([
            'success' => true,
            'message' => 'Service receipt logged successfully.'
        ]);
    }

    // API endpoint for Services module - get all service types
    public function getServiceTypes()
    {
        $types = Service::distinct()->whereNotNull('type')->pluck('type')->sort()->values();

        return response()->json($types);
    }

    /**
     * API: Get all services with optional filtering and search
     */
    public function apiList(Request $request)
    {
        Log::info('🔍 API Services List Called', [
            'is_htmx' => $request->header('HX-Request') ? 'yes' : 'no',
            'status_filter' => $request->input('status', []),
            'sort' => $request->input('sort', 'newest'),
            'search' => $request->input('search', ''),
        ]);

        // Load customer, serviceType, and replacements relationships
        $query = Service::with(['customer', 'serviceType', 'replacements']);

        // Handle sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc'); // Oldest first
        } else {
            $query->orderBy('created_at', 'desc'); // Newest first (default)
        }

        // Handle multi-status filtering
        $statuses = $request->input('status', []);

        if (in_array('all', $statuses) || empty($statuses)) {
            // For "All" filter, exclude Completed and Canceled status
            $query->whereNotIn('status', ['Completed', 'Canceled']);
        } else {
            // Filter by selected statuses (exclude Completed and Canceled)
            $allowedStatuses = array_intersect($statuses, ['Pending', 'In Progress', 'On Hold']);
            if (!empty($allowedStatuses)) {
                $query->whereIn('status', $allowedStatuses);
            } else {
                // If no valid statuses, show default (exclude Completed and Canceled)
                $query->whereNotIn('status', ['Completed', 'Canceled']);
            }
        }

        // Search by multiple fields - including customer name, service type, type, brand, model, and description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($cq) use ($search) {
                    $cq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                })
                    ->orWhereHas('serviceType', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('total_price', 'like', "%{$search}%");
            });
        }

        $services = $query->get()->map(function ($service) {
            return [
                'id' => $service->id,
                'customer_id' => $service->customer_id,
                'service_type_id' => $service->service_type_id,
                'customer' => $service->customer ? [
                    'id' => $service->customer->id,
                    'first_name' => $service->customer->first_name,
                    'last_name' => $service->customer->last_name,
                ] : null,
                'serviceType' => $service->serviceType ? [
                    'id' => $service->serviceType->id,
                    'name' => $service->serviceType->name,
                    'price' => $service->serviceType->price,
                ] : null,
                'type' => $service->type,
                'brand' => $service->brand,
                'model' => $service->model,
                'date_in' => $service->date_in,
                'date_out' => $service->date_out,
                'description' => $service->description,
                'action' => $service->action,
                'status' => $service->status,
                'total_price' => $service->total_price,
                'replacements' => $service->replacements ?? [],
                'created_at' => $service->created_at,
                'updated_at' => $service->updated_at,
            ];
        });

        Log::info('📋 API Services Response - Mapped:', [
            'count' => $services->count(),
            'first_service' => $services->first(),
        ]);

        // Check if this is an HTMX request
        if ($request->header('HX-Request')) {
            // Convert mapped array back to collection with objects for Blade compatibility
            $servicesCollection = collect($services)->map(function ($serviceArray) {
                return (object) [
                    'id' => $serviceArray['id'],
                    'customer' => $serviceArray['customer'] ? (object) $serviceArray['customer'] : null,
                    'serviceType' => $serviceArray['serviceType'] ? (object) $serviceArray['serviceType'] : null,
                    'type' => $serviceArray['type'],
                    'brand' => $serviceArray['brand'],
                    'model' => $serviceArray['model'],
                    'description' => $serviceArray['description'],
                    'status' => $serviceArray['status'],
                    'total_price' => $serviceArray['total_price'],
                ];
            });

            // Return Blade partial for HTMX
            return view('ServicesOrder.partials.ServiceCards', [
                'services' => $servicesCollection
            ]);
        }

        // Return JSON for API calls
        return response()->json($services);
    }

    private function getStatusColor($status)
    {
        switch ($status) {
            case 'Pending':
                return 'bg-yellow-100 text-yellow-800';
            case 'In Progress':
                return 'bg-blue-100 text-blue-800';
            case 'On Hold':
                return 'bg-orange-100 text-orange-800';
            case 'Completed':
                return 'bg-green-100 text-green-800';
            case 'Canceled':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }
}
