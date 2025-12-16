<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Product_Stocks;
use App\Models\CustomerPurchaseOrder;
use App\Models\Suppliers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display dashboard with real-time data
     */
    public function index()
    {
        return view('DASHBOARD.homepage');
    }

    /**
     * Get dashboard analytics data (API endpoint)
     * Real-time data with minimal caching (1 second) for performance
     */
    public function getDashboardData()
    {
        try {
            // Cache dashboard data for 1 second to prevent excessive DB queries
            // while still providing near real-time updates
            $data = Cache::remember('dashboard_data', 1, function () {
                return [
                    'metrics' => $this->getKeyMetrics(),
                    'top_products' => $this->getTopSellingProducts(),
                    'low_stock_alerts' => $this->getLowStockAlerts(),
                    'no_stock_alerts' => $this->getNoStockAlerts(),
                    'supplier_status' => $this->getSupplierStatus(),
                    'inventory_status' => $this->getInventoryStatus(),
                    'last_updated' => Carbon::now()->format('Y-m-d H:i:s')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching dashboard data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get key metrics for dashboard cards
     * Real-time data (no individual caching, handled at main level)
     */
    private function getKeyMetrics()
    {
        // Employee count
        $employeeCount = Employee::count();

        // Customer count
        $customerCount = Customer::count();

        // Total products in stock (stock_quantity > 0)
        $productCount = Product_Stocks::where('stock_quantity', '>', 0)
            ->distinct('product_id')
            ->count('product_id');

        // Daily sales (today's total from dr_transactions where type = 'purchase')
        $dailySales = DB::table('dr_transactions')
            ->whereDate('created_at', Carbon::today())
            ->where('type', 'purchase')
            ->sum('total_sum');

        return [
            'employees' => $employeeCount,
            'customers' => $customerCount,
            'products' => $productCount,
            'daily_sales' => round($dailySales ?? 0, 2)
        ];
    }

    /**
     * Get top selling products from customer_purchase_orders
     * Groups by product name only and aggregates all quantities
     */
    private function getTopSellingProducts()
    {
        $topProducts = CustomerPurchaseOrder::select(
            'products.product_name',
            DB::raw('SUM(customer_purchase_orders.quantity) as total_sold'),
            DB::raw('AVG(customer_purchase_orders.unit_price) as avg_price')
        )
            ->join('products', 'customer_purchase_orders.product_id', '=', 'products.id')
            ->where('customer_purchase_orders.status', 'Success')
            ->groupBy('products.product_name') // Group by name only!
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'price' => '₱' . number_format($item->avg_price, 2),
                    'sold' => (int) $item->total_sold
                ];
            });

        return $topProducts;
    }

    /**
     * Get low stock alerts (total stock per product name ≤ 5 and > 0)
     * Groups by product name only and aggregates all stock
     */
    private function getLowStockAlerts()
    {
        $lowStockItems = Product_Stocks::select(
            'products.product_name',
            DB::raw('SUM(product_stocks.stock_quantity) as total_stock'),
            DB::raw('AVG(product_stocks.price) as avg_price')
        )
            ->join('products', 'product_stocks.product_id', '=', 'products.id')
            ->groupBy('products.product_name') // Group by name only!
            ->havingRaw('SUM(product_stocks.stock_quantity) > 0 AND SUM(product_stocks.stock_quantity) <= 5')
            ->orderBy('total_stock', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'left' => (int) $item->total_stock,
                    'price' => '₱' . number_format($item->avg_price, 2)
                ];
            });

        return $lowStockItems;
    }

    /**
     * Get no stock alerts (total stock per product name = 0)
     * Groups by product name only and aggregates all stock
     */
    private function getNoStockAlerts()
    {
        $noStockItems = Product_Stocks::select(
            'products.product_name',
            DB::raw('SUM(product_stocks.stock_quantity) as total_stock'),
            DB::raw('AVG(product_stocks.price) as avg_price')
        )
            ->join('products', 'product_stocks.product_id', '=', 'products.id')
            ->groupBy('products.product_name') // Group by name only!
            ->havingRaw('SUM(product_stocks.stock_quantity) = 0')
            ->orderBy('products.product_name', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'left' => (int) $item->total_stock,
                    'price' => '₱' . number_format($item->avg_price, 2)
                ];
            });

        return $noStockItems;
    }

    /**
     * Get supplier status (active vs inactive)
     */
    private function getSupplierStatus()
    {
        $activeSuppliers = Suppliers::where('status', 'active')->count();
        $inactiveSuppliers = Suppliers::where('status', 'inactive')->count();
        $totalSuppliers = $activeSuppliers + $inactiveSuppliers;

        $percentage = $totalSuppliers > 0 ? round(($activeSuppliers / $totalSuppliers) * 100) : 0;

        return [
            'active' => $activeSuppliers,
            'inactive' => $inactiveSuppliers,
            'percentage' => $percentage
        ];
    }

    /**
     * Get inventory status (Brand New vs Used)
     */
    private function getInventoryStatus()
    {
        // Count products by condition where stock > 0
        $brandNewCount = Product::join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->where('products.product_condition', 'Brand New')
            ->where('product_stocks.stock_quantity', '>', 0)
            ->distinct('products.id')
            ->count('products.id');

        $usedCount = Product::join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->where('products.product_condition', 'Second Hand')
            ->where('product_stocks.stock_quantity', '>', 0)
            ->distinct('products.id')
            ->count('products.id');

        $totalProducts = $brandNewCount + $usedCount;
        $percentage = $totalProducts > 0 ? round(($brandNewCount / $totalProducts) * 100) : 0;

        return [
            'brand_new' => $brandNewCount,
            'used' => $usedCount,
            'percentage' => $percentage
        ];
    }
}