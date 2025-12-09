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
     * Cached for 5 minutes (300 seconds)
     */
    public function getDashboardData()
    {
        try {
            // Cache dashboard data for 5 minutes
            $data = Cache::remember('dashboard_data', 300, function () {
                return [
                    'metrics' => $this->getKeyMetrics(),
                    'top_products' => $this->getTopSellingProducts(),
                    'low_stock_alerts' => $this->getLowStockAlerts(),
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
     * Cached for 5 minutes
     */
    private function getKeyMetrics()
    {
        return Cache::remember('dashboard_metrics', 300, function () {
            // Employee count
            $employeeCount = Employee::count();

            // Customer count
            $customerCount = Customer::count();

            // Total products in stock (stock_quantity > 0)
            $productCount = Product_Stocks::where('stock_quantity', '>', 0)
                ->distinct('product_id')
                ->count('product_id');

            // Daily sales (today's total from customer_purchase_orders)
            $dailySales = CustomerPurchaseOrder::whereDate('order_date', Carbon::today())
                ->where('status', 'Success')
                ->sum('total_price');

            return [
                'employees' => $employeeCount,
                'customers' => $customerCount,
                'products' => $productCount,
                'daily_sales' => round($dailySales, 2)
            ];
        });
    }

    /**
     * Get top selling products from customer_purchase_orders grouped by product_name and price
     */
    private function getTopSellingProducts()
    {
        $topProducts = CustomerPurchaseOrder::select(
            'products.product_name',
            'product_stocks.price',
            DB::raw('SUM(customer_purchase_orders.quantity) as total_sold')
        )
            ->join('products', 'customer_purchase_orders.product_id', '=', 'products.id')
            ->join('product_stocks', 'products.id', '=', 'product_stocks.product_id')
            ->where('customer_purchase_orders.status', 'Success')
            ->groupBy('products.product_name', 'product_stocks.price')
            ->orderBy('total_sold', 'desc')
            ->limit(10) // Get more items to show in scrollable view
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'price' => '₱' . number_format($item->price, 2),
                    'sold' => (int) $item->total_sold
                ];
            });

        return $topProducts;
    }

    /**
     * Get low stock alerts (stock_quantity = 1)
     */
    private function getLowStockAlerts()
    {
        $lowStockItems = Product_Stocks::select(
            'products.product_name',
            'product_stocks.stock_quantity',
            'product_stocks.price'
        )
            ->join('products', 'product_stocks.product_id', '=', 'products.id')
            ->where('product_stocks.stock_quantity', 1)
            ->orderBy('products.product_name')
            ->limit(10) // Get more items for scrollable view
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'left' => (int) $item->stock_quantity,
                    'price' => '₱' . number_format($item->price, 2)
                ];
            });

        return $lowStockItems;
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
