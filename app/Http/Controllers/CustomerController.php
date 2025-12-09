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

    public function search(Request $request)
    {
        $query = $request->input('query', '');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $customers = Customer::where('first_name', 'LIKE', "%{$query}%")
            ->orWhere('last_name', 'LIKE', "%{$query}%")
            ->orWhere('contact_no', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                return [
                    'id' => $customer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'full_name' => $customer->first_name . ' ' . $customer->last_name,
                    'contact_no' => $customer->contact_no
                ];
            });

        return response()->json($customers);
    }

    // API endpoint for Services module - get all customers
    public function getApiList()
    {
        $customers = Customer::all()->map(function ($customer) {
            return [
                'id' => $customer->id,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name
            ];
        });

        return response()->json($customers);
    }
}
