<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DRTransaction;
use App\Models\Product;
use App\Models\Purchase_Details;
use App\Models\CustomerPurchaseOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesReportController extends Controller
{
    /**
     * Display sales reports page
     */
    public function index()
    {
        return view('DASHBOARD.salesReports');
    }

    /**
     * Get sales report data (API endpoint)
     */
    public function getSalesReportData(Request $request)
    {
        try {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            // If no dates provided, use current month
            if (!$startDate || !$endDate) {
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
            }

            // Validate date format
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            $transactions = $this->getRecentTransactions($startDate, $endDate);
            $summary = $this->getSalesSummary($startDate, $endDate);
            $topProducts = $this->getTopProducts($startDate, $endDate);
            $topCustomers = $this->getTopCustomers($startDate, $endDate);

            return response()->json([
                'success' => true,
                'data' => [
                    'transactions' => $transactions,
                    'summary' => $summary,
                    'top_products' => $topProducts,
                    'top_customers' => $topCustomers
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching sales report data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales report data'
            ], 500);
        }
    }

    /**
     * Get recent transactions from dr_transactions table with quantity
     */
    private function getRecentTransactions($startDate, $endDate)
    {
        $transactions = DB::table('dr_transactions')
            ->select(
                'dr_transactions.id',
                DB::raw('MAX(customers.first_name) as first_name'),
                DB::raw('MAX(customers.last_name) as last_name'),
                'dr_transactions.total_sum',
                'dr_transactions.created_at',
                'dr_transactions.receipt_no',
                DB::raw('SUM(customer_purchase_orders.total_price) as subtotal'),
                DB::raw('SUM(customer_purchase_orders.quantity) as total_qty')
            )
            ->leftJoin('customer_purchase_orders', 'dr_transactions.id', '=', 'customer_purchase_orders.dr_receipt_id')
            ->leftJoin('customers', 'customer_purchase_orders.customer_id', '=', 'customers.id')
            ->where('type', 'purchase')
            ->whereBetween('dr_transactions.created_at', [$startDate, $endDate])
            ->groupBy(
                'dr_transactions.id',
                'dr_transactions.total_sum',
                'dr_transactions.created_at',
                'dr_transactions.receipt_no'
            )
            ->orderBy('dr_transactions.created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                $subtotal = round($transaction->subtotal ?? 0, 2);
                $totalSum = round($transaction->total_sum ?? 0, 2);
                $discount = round($subtotal - $totalSum, 2);

                // Construct customer name: show first name only if last name is not available
                $customerName = '-';
                if (!empty($transaction->first_name)) {
                    $customerName = $transaction->first_name;
                    if (!empty($transaction->last_name)) {
                        $customerName .= ' ' . $transaction->last_name;
                    }
                }

                return [
                    'id' => $transaction->id,
                    'customer_name' => $customerName,
                    'subtotal' => $subtotal,
                    'discount' => $discount > 0 ? $discount : 0,
                    'amount' => $totalSum,
                    'qty' => $transaction->total_qty ?? 0,
                    'date' => Carbon::parse($transaction->created_at)->format('m/d/Y h:i A'),
                    'receipt_no' => $transaction->receipt_no ?? '-'
                ];
            });

        return $transactions;
    }

    /**
     * Get sales summary for the report
     */
    private function getSalesSummary($startDate, $endDate)
    {
        $revenue = DB::table('dr_transactions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'purchase')
            ->sum('total_sum');

        $totalOrders = DB::table('dr_transactions')
            ->where('type', 'purchase')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $avgOrderValue = $totalOrders > 0 ? $revenue / $totalOrders : 0;

        return [
            'revenue' => round($revenue, 2),
            'total_orders' => $totalOrders,
            'avg_order_value' => round($avgOrderValue, 2),
            'discount' => $this->getTotalDiscount($startDate, $endDate)
        ];
    }

    /**
     * Calculate Total Discount (Total Price from customer_purchase_orders - Total Sum from dr_transactions)
     */
    private function getTotalDiscount($startDate, $endDate)
    {
        // Get total price from customer purchase orders
        $totalPrice = CustomerPurchaseOrder::whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'Success')
            ->sum('total_price');

        // Get total sum from dr transactions
        $totalSum = DB::table('dr_transactions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'purchase')
            ->sum('total_sum');

        // Calculate discount
        $discount = $totalPrice - $totalSum;

        return $discount > 0 ? round($discount, 2) : 0;
    }

    /**
     * Get top products by quantity sold
     */
    private function getTopProducts($startDate, $endDate)
    {
        $topProducts = CustomerPurchaseOrder::select(
            'products.product_name',
            DB::raw('SUM(customer_purchase_orders.quantity) as total_quantity'),
            DB::raw('SUM(customer_purchase_orders.total_price) as total_sales')
        )
            ->join('products', 'customer_purchase_orders.product_id', '=', 'products.id')
            ->whereBetween('customer_purchase_orders.order_date', [$startDate, $endDate])
            ->where('customer_purchase_orders.status', 'Success')
            ->groupBy('products.product_name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => $item->product_name,
                    'quantity' => (int) $item->total_quantity,
                    'sales' => round($item->total_sales, 2)
                ];
            });

        return $topProducts;
    }

    /**
     * Get top customers by total purchase amount
     */
    private function getTopCustomers($startDate, $endDate)
    {
        $topCustomers = DB::table('dr_transactions')
            ->select(
                DB::raw('MAX(customers.first_name) as first_name'),
                DB::raw('MAX(customers.last_name) as last_name'),
                DB::raw('SUM(dr_transactions.total_sum) as total_spent'),
                DB::raw('COUNT(dr_transactions.id) as transaction_count')
            )
            ->leftJoin('customer_purchase_orders', 'dr_transactions.id', '=', 'customer_purchase_orders.dr_receipt_id')
            ->leftJoin('customers', 'customer_purchase_orders.customer_id', '=', 'customers.id')
            ->where('dr_transactions.type', 'purchase')
            ->whereBetween('dr_transactions.created_at', [$startDate, $endDate])
            ->whereNotNull('customers.id')
            ->groupBy('customers.id')
            ->orderBy('total_spent', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($customer) {
                // Construct customer name
                $customerName = '-';
                if (!empty($customer->first_name)) {
                    $customerName = $customer->first_name;
                    if (!empty($customer->last_name)) {
                        $customerName .= ' ' . $customer->last_name;
                    }
                }

                return [
                    'customer_name' => $customerName,
                    'total_spent' => round($customer->total_spent ?? 0, 2),
                    'transaction_count' => (int) $customer->transaction_count
                ];
            });

        return $topCustomers;
    }
}
