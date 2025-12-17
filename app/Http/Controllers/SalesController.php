<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_Stocks;
use App\Models\Purchase_Details;
use App\Models\CustomerPurchaseOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display sales dashboard
     */
    public function index()
    {
        return view('DASHBOARD.Sales');
    }

    /**
     * Get sales analytics data (API endpoint)
     */
    public function getSalesData(Request $request)
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

            $data = [
                'total_good_cost' => $this->getTotalSales($startDate, $endDate),
                'total_orders' => $this->getTotalOrders($startDate, $endDate),
                'avg_order_value' => $this->getAverageOrderValue($startDate, $endDate),
                'revenue' => $this->getRevenue($startDate, $endDate),
                'discount' => $this->getTotalDiscount($startDate, $endDate),
                'profit' => $this->getProfit($startDate, $endDate),
                'sales_trend' => $this->getSalesTrend($startDate, $endDate),
                'top_products' => $this->getTopProducts($startDate, $endDate),
                'recent_transactions' => $this->getRecentTransactions($startDate, $endDate),
                'date_range' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d')
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculate Total Good Cost (Sum of total_price from purchase_details where status = 'Received')
     * Formula: Total Good Cost = Sum of all purchase detail totals with status = 'Received'
     */
    private function getTotalSales($startDate = null, $endDate = null)
    {
        // Get total good cost from purchase_details (only 'Received' status)
        $totalGoodCost = Purchase_Details::whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'Received')
            ->sum('total_price');

        return round($totalGoodCost ?? 0, 2);
    }

    /**
     * Get Total Orders (from purchase_details with status = 'Received')
     * Also used for Average Order Value calculation
     */
    private function getTotalOrders($startDate, $endDate)
    {
        $totalOrders = Purchase_Details::whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'Received')
            ->count();

        return $totalOrders;
    }

    /**
     * Calculate Average Order Value (Total Good Cost of 'Received' items / Count of 'Received' items)
     */
    private function getAverageOrderValue($startDate, $endDate)
    {
        $totalReceivedCost = Purchase_Details::whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'Received')
            ->sum('total_price');

        $totalReceivedOrders = Purchase_Details::whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'Received')
            ->count();

        return $totalReceivedOrders > 0
            ? round($totalReceivedCost / $totalReceivedOrders, 2)
            : 0;
    }

    /**
     * Calculate Profit (Total Sum from dr_transactions - Total Good Cost)
     * Formula: Profit = SUM(dr_transactions.total_sum) - SUM(purchase_details.total_price where status='Received')
     * Note: Only shows profit when revenue exceeds costs. Returns 0 if there's a loss (negative profit)
     */
    private function getProfit($startDate, $endDate)
    {
        // Get total sum from dr transactions
        $totalRevenue = DB::table('dr_transactions')
            ->whereBetween('created_at', [$startDate, $endDate],)
            ->where('type', 'purchase')
            ->sum('total_sum');

        // Get total good cost from purchase details (only Received)
        $totalGoodCost = Purchase_Details::whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'Received')
            ->sum('total_price');

        // Calculate profit
        $profit = $totalRevenue - $totalGoodCost;

        // Only return profit if it's positive, otherwise return 0 (no loss displayed)
        return $profit > 0 ? round($profit, 2) : 0;
    }

    /**
     * Calculate Revenue (Units Sold x Selling Price from customer_purchase_orders)
     */
    private function getRevenue($startDate, $endDate)
    {
        // Revenue from actual customer sales
        $revenue = CustomerPurchaseOrder::whereBetween('order_date', [$startDate, $endDate])
            ->where('status', 'Success')
            ->sum('total_price');

        return round($revenue, 2);
    }

    /**
     * Get sales trend data for chart
     * Shows daily revenue from customer orders within the date range
     */
    private function getSalesTrend($startDate, $endDate)
    {
        $salesTrend = [];

        // Generate daily sales data from customer purchase orders
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dailySales = CustomerPurchaseOrder::whereDate('order_date', $currentDate)
                ->where('status', 'Success')
                ->sum('total_price');

            $salesTrend[] = [
                'date' => $currentDate->format('Y-m-d'),
                'sales' => round($dailySales, 2)
            ];

            $currentDate->addDay();
        }

        return $salesTrend;
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
            ->limit(8)
            ->get()
            ->map(function ($item) {
                return [
                    'product_name' => strlen($item->product_name) > 20
                        ? substr($item->product_name, 0, 20) . '...'
                        : $item->product_name,
                    'full_name' => $item->product_name,
                    'quantity' => (int) $item->total_quantity,
                    'sales' => round($item->total_sales, 2)
                ];
            });

        return $topProducts;
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
     * Get recent transactions from dr_transactions table
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
            ->limit(50)
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
     * Get sales summary by date range (quick endpoint)
     */
    public function getSalesSummary(Request $request)
    {
        try {
            $startDate = Carbon::parse($request->get('start_date', Carbon::now()->startOfMonth()));
            $endDate = Carbon::parse($request->get('end_date', Carbon::now()->endOfMonth()));

            $summary = [
                'total_good_cost' => $this->getTotalSales($startDate, $endDate),
                'total_orders' => $this->getTotalOrders($startDate, $endDate),
                'revenue' => $this->getRevenue($startDate, $endDate),
                'profit' => $this->getProfit($startDate, $endDate),
                'period' => $startDate->format('M d') . ' - ' . $endDate->format('M d, Y')
            ];

            $summary['avg_order_value'] = $this->getAverageOrderValue($startDate, $endDate);

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching sales summary: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get real-time sales data (for live updates)
     */
    public function getRealTimeSales()
    {
        try {
            $today = Carbon::today();

            $realTimeData = [
                'today_good_cost' => $this->getTotalSales($today, $today),
                'today_orders' => $this->getTotalOrders($today, $today),
                'today_revenue' => $this->getRevenue($today, $today),
                'today_profit' => $this->getProfit($today, $today),
                'last_updated' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            return response()->json([
                'success' => true,
                'data' => $realTimeData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching real-time data: ' . $e->getMessage()
            ], 500);
        }
    }
}
