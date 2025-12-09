<?php

namespace App\Http\Controllers;

use App\Models\DRTransaction;
use App\Services\DRTransactionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DrTransactionController extends Controller
{
    /**
     * The DR transaction service instance.
     *
     * @var DRTransactionService
     */
    protected $drTransactionService;

    /**
     * Create a new controller instance.
     *
     * @param DRTransactionService $drTransactionService
     */
    public function __construct(DRTransactionService $drTransactionService)
    {
        $this->drTransactionService = $drTransactionService;
    }
    /**
     * Get the next DR number that will be generated.
     *
     * @return JsonResponse
     */
    public function getNextDRNumber(): JsonResponse
    {
        try {
            $nextDRNumber = $this->drTransactionService->generateDRNumber();

            return response()->json([
                'success' => true,
                'dr_number' => $nextDRNumber
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate DR number',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(DRTransaction $DRTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DRTransaction $DRTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DRTransaction $DRTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DRTransaction $DRTransaction)
    {
        //
    }
}
