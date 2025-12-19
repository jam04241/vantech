<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\CustomerPurchaseOrder;
use App\Models\DRTransaction;
use Illuminate\Support\Facades\Log;

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

    public function getPurchaseTransactions($customerId)
    {
        try {
            $customer = Customer::findOrFail($customerId);

            $receipts = CustomerPurchaseOrder::where('customer_id', $customerId)
                ->with(['drTransaction', 'product', 'paymentMethod'])
                ->get()
                ->groupBy(function ($order) {
                    return $order->drTransaction->receipt_no;
                })
                ->map(function ($orders, $receiptNo) {
                    $drTransaction = $orders->first()->drTransaction;
                    $subtotal = $orders->sum('total_price');
                    $discount = $subtotal - $drTransaction->total_sum;
                    $paymentMethod = $orders->first()->paymentMethod;

                    return [
                        'receipt_no' => $receiptNo,
                        'dr_receipt_id' => $drTransaction->id,
                        'date_time' => $drTransaction->created_at,
                        'subtotal' => $subtotal,
                        'discount' => $discount,
                        'total_price' => $drTransaction->total_sum,
                        'payment_method' => $paymentMethod ? $paymentMethod->method_name : 'N/A',
                        'bank_name' => $paymentMethod ? $paymentMethod->bank_name : null,
                        'account_name' => $paymentMethod ? $paymentMethod->account_name : null,
                        'reference_no' => $paymentMethod ? $paymentMethod->reference_no : null,
                        'products' => $orders->map(function ($order) {
                            return [
                                'product_name' => $order->product->product_name,
                                'serial_no' => $order->serial_number,
                                'warranty' => $order->product->warranty_period,
                                'unit_price' => $order->unit_price,
                                'total_price' => $order->total_price
                            ];
                        })->values()
                    ];
                })
                ->values();

            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'full_name' => $customer->first_name . ' ' . $customer->last_name,
                    'contact_no' => $customer->contact_no,
                    'address' => trim(($customer->street ?? '') . ' ' . ($customer->brgy ?? '') . ' ' . ($customer->city_province ?? ''))
                ],
                'receipts' => $receipts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase transactions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
