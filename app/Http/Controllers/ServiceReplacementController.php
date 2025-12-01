<?php

namespace App\Http\Controllers;

use App\Models\ServiceReplacement;
use App\Http\Requests\ServiceReplacementRequest;
use Illuminate\Http\Request;

class ServiceReplacementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created replacement in storage.
     */
    public function store(ServiceReplacementRequest $request)
    {
        $replacement = ServiceReplacement::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Replacement added successfully.',
            'replacement' => $replacement
        ]);
    }

    /**
     * Update the specified replacement in storage (including soft delete via is_disabled).
     */
    public function update(ServiceReplacementRequest $request, ServiceReplacement $serviceReplacement)
    {
        $serviceReplacement->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Replacement updated successfully.',
            'id' => $serviceReplacement->id,
            'replacement' => $serviceReplacement
        ]);
    }

    /**
     * Remove the specified replacement from storage.
     */
    public function destroy(ServiceReplacement $serviceReplacement)
    {
        $serviceReplacement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Replacement deleted successfully.'
        ]);
    }
}
