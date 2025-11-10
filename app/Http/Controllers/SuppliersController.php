<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use App\Http\Requests\SupplierRequest;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    public function index()
    {
        $suppliers = Suppliers::orderBy('supplier_name')->get();
        return view('DASHBOARD.suppliers', compact('suppliers'));
    }

    public function store(SupplierRequest $request)
    {
        try {
            $data = $request->validated();
            $data['status'] = 'active'; // Set default status
            Suppliers::create($data);
            
            return redirect()->route('suppliers')->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers')->with('error', 'Error creating supplier: ' . $e->getMessage());
        }
    }

    public function edit(Suppliers $supplier)
    {
        return view('DASHBOARD.suppliers_edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, Suppliers $supplier)
    {
        try {
            $supplier->update($request->validated());
            return redirect()->route('suppliers')->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers')->with('error', 'Error updating supplier: ' . $e->getMessage());
        }
    }

    public function destroy(Suppliers $supplier)
    {
        try {
            $supplier->delete();
            return redirect()->route('suppliers')->with('success', 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('suppliers')->with('error', 'Error deleting supplier: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Request $request, Suppliers $supplier)
    {
        try {
            $supplier->status = $supplier->status === 'active' ? 'inactive' : 'active';
            $supplier->save();
            
            return response()->json([
                'success' => true, 
                'status' => $supplier->status,
                'message' => 'Supplier status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }
}