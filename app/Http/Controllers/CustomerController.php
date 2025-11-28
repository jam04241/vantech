<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('DASHBOARD.Customer_record', compact('customers'));
    }

    public function store(CustomerRequest $request)
    {
        try {
            $data = $request->validated();
            Customer::create($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Customer added successfully.'
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add customer. Please try again.'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json($customer);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Customer not found'
            ], 404);
        }
    }

    public function update(CustomerRequest $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $data = $request->validated();
            $customer->update($data);
            
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully.'
            ]);
                
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer. Please try again.'
            ], 500);
        }
    }
}