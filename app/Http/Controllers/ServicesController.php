<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceType;
use App\Http\Requests\ServiceRequest;
use App\Traits\LoadsBrandData;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    use LoadsBrandData;
    /**
     * Display a listing of all services.
     */
    public function index()
    {
        $services = Service::with(['customer', 'serviceType', 'replacements'])->get();
        $serviceTypes = ServiceType::all();
        $customers = \App\Models\Customer::all();

        return view('ServicesOrder.Services', [
            'services' => $services,
            'serviceTypes' => $serviceTypes,
            'customers' => $customers,
        ]);
    }

    /**
     * Store a newly created service in database.
     */
    public function store(ServiceRequest $request)
    {
        $service = Service::create($request->validated());

        $service->load(['customer', 'serviceType', 'replacements' => function ($q) {
            $q->where('is_disabled', 0);
        }]);

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
        $service->load(['customer', 'serviceType', 'replacements' => function ($q) {
            $q->where('is_disabled', 0);
        }]);
        return response()->json($service);
    }

    /**
     * Update the specified service in database.
     */
    public function update(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());

        $service->load(['customer', 'serviceType', 'replacements' => function ($q) {
            $q->where('is_disabled', 0);
        }]);

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
        $query = Service::with(['customer', 'serviceType', 'replacements' => function ($q) {
            // Only load enabled replacements (is_disabled = 0)
            $q->where('is_disabled', 0);
        }]);

        // Filter by status (exclude Canceled from "All" filter)
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        } else if ($request->has('status') && $request->status === 'all') {
            // For "All" filter, exclude Canceled status
            $query->whereNotIn('status', ['Canceled']);
        }

        // Search by multiple fields
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($cq) use ($search) {
                    $cq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                })
                    ->orWhereHas('serviceType', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('total_price', 'like', "%{$search}%");
            });
        }

        $services = $query->get();

        return response()->json($services);
    }
}
