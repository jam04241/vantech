<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DRTransaction;
use App\Models\Product;
use App\Models\Purchase_Details;
use App\Models\CustomerPurchaseOrder;
use App\Models\WarrantyClaim;
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
        $sold = CustomerPurchaseOrder::STATUS_SUCCESS;

        $transactions = DB::table('dr_transactions')
            ->select(
                'dr_transactions.id',
                DB::raw('MAX(customers.first_name) as first_name'),
                DB::raw('MAX(customers.last_name) as last_name'),
                'dr_transactions.total_sum',
                'dr_transactions.created_at',
                'dr_transactions.receipt_no',
                // Items returned under warranty no longer count on the receipt.
                DB::raw("SUM(CASE WHEN customer_purchase_orders.status = '{$sold}' THEN customer_purchase_orders.total_price ELSE 0 END) as subtotal"),
                DB::raw("SUM(CASE WHEN customer_purchase_orders.status = '{$sold}' THEN customer_purchase_orders.quantity ELSE 0 END) as total_qty"),
                DB::raw('MAX(COALESCE(claims.removed_amount, 0)) as warranty_amount'),
                DB::raw('MAX(COALESCE(claims.claimed_items, 0)) as warranty_items')
            )
            ->leftJoin('customer_purchase_orders', 'dr_transactions.id', '=', 'customer_purchase_orders.dr_receipt_id')
            ->leftJoin('customers', 'customer_purchase_orders.customer_id', '=', 'customers.id')
            ->leftJoinSub(WarrantyClaim::removedPerReceipt(), 'claims', 'claims.dr_receipt_id', '=', 'dr_transactions.id')
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
                $totalSum = round(($transaction->total_sum ?? 0) - $transaction->warranty_amount, 2);
                $discount = round($subtotal - $totalSum, 2);

                // Construct customer name: show first name only if last name is
                // not available. A sale with no customer is a walk-in.
                $customerName = CustomerPurchaseOrder::WALK_IN_LABEL;
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
                    'receipt_no' => $transaction->receipt_no ?? '-',
                    'warranty_items' => (int) $transaction->warranty_items,
                    'warranty_amount' => round($transaction->warranty_amount, 2),
                ];
            });

        return $transactions;
    }

    /**
     * Get sales summary for the report
     */
    private function getSalesSummary($startDate, $endDate)
    {
        // Receipt totals, less items that came back broken under warranty
        $revenue = DB::table('dr_transactions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'purchase')
            ->sum('total_sum')
            - WarrantyClaim::salesRemovedBetween($startDate, $endDate);

        // A receipt whose every item came back under warranty is no longer an order.
        $totalOrders = DB::table('dr_transactions')
            ->where('type', 'purchase')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereExists(function ($lines) {
                $lines->select(DB::raw(1))
                    ->from('customer_purchase_orders')
                    ->whereColumn('customer_purchase_orders.dr_receipt_id', 'dr_transactions.id')
                    ->where('customer_purchase_orders.status', CustomerPurchaseOrder::STATUS_SUCCESS);
            })
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

        // Get total sum from dr transactions, less items returned under warranty
        $totalSum = DB::table('dr_transactions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'purchase')
            ->sum('total_sum')
            - WarrantyClaim::salesRemovedBetween($startDate, $endDate);

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
        // Walk-in sales carry no customer_id, so the join below leaves them out
        // of this ranking on purpose - there is nobody to attribute them to.
        //
        // Collapse each DR transaction to one row per customer first. Joining
        // dr_transactions straight to its purchase order lines repeated total_sum
        // once per line, inflating both spend and transaction counts for any
        // receipt holding more than one item.
        //
        // Items returned under warranty are left out: their lines are skipped
        // and what was paid for them comes off the receipt total.
        $transactionsPerCustomer = DB::table('dr_transactions')
            ->select(
                'customer_purchase_orders.customer_id',
                'dr_transactions.id as dr_id',
                DB::raw('MAX(dr_transactions.total_sum) - MAX(COALESCE(claims.removed_amount, 0)) as total_sum')
            )
            ->join('customer_purchase_orders', 'dr_transactions.id', '=', 'customer_purchase_orders.dr_receipt_id')
            ->leftJoinSub(WarrantyClaim::removedPerReceipt(), 'claims', 'claims.dr_receipt_id', '=', 'dr_transactions.id')
            ->where('customer_purchase_orders.status', CustomerPurchaseOrder::STATUS_SUCCESS)
            ->where('dr_transactions.type', 'purchase')
            ->whereBetween('dr_transactions.created_at', [$startDate, $endDate])
            ->groupBy('customer_purchase_orders.customer_id', 'dr_transactions.id');

        $topCustomers = DB::query()
            ->fromSub($transactionsPerCustomer, 'per_transaction')
            ->select(
                DB::raw('MAX(customers.first_name) as first_name'),
                DB::raw('MAX(customers.last_name) as last_name'),
                DB::raw('SUM(per_transaction.total_sum) as total_spent'),
                DB::raw('COUNT(per_transaction.dr_id) as transaction_count')
            )
            ->join('customers', 'per_transaction.customer_id', '=', 'customers.id')
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
