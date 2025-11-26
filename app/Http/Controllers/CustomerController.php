<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        // Index method can remain empty or be used for other purposes
    }

    public function store(CustomerRequest $request)
    {
        try {
            $data = $request->validated();
            Customer::create($data);
            
            return redirect()->route('pos.itemlist')
                ->with('success', 'Customer added successfully.')
                ->with('from_customer_add', true);
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add customer. Please try again.')
                ->withInput();
        }
    }

    public function search(Request $request)
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $customers = Customer::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('contact_no', 'like', "%{$query}%")
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
}