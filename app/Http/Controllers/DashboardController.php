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
use Illuminate\Support\Facades\Log;

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
            Log::info('📊 Dashboard data request received');

            // Cache dashboard data for 5 minutes
            $data = Cache::remember('dashboard_data', 300, function () {
                Log::info('🔄 Generating fresh dashboard data (cache miss)');
                return [
                    'metrics' => $this->getKeyMetrics(),
                    'top_products' => $this->getTopSellingProducts(),
                    'low_stock_alerts' => $this->getLowStockAlerts(),
                    'supplier_status' => $this->getSupplierStatus(),
                    'inventory_status' => $this->getInventoryStatus(),
                    'last_updated' => Carbon::now()->format('Y-m-d H:i:s')
                ];
            });

            Log::info('✅ Dashboard data generated successfully', ['data_keys' => array_keys($data)]);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error fetching dashboard data: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString()
            ]);

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
     * Get top selling products from customer_purchase_orders grouped by product_id
     * Sums quantity for same products
     * Cached for 5 minutes
     */
    private function getTopSellingProducts()
    {
        return Cache::remember('dashboard_top_products', 300, function () {
            Log::info('📊 Fetching top selling products...');

            $topProducts = CustomerPurchaseOrder::select(
                'products.id',
                'products.product_name',
                DB::raw('SUM(customer_purchase_orders.quantity) as total_sold')
            )
                ->join('products', 'customer_purchase_orders.product_id', '=', 'products.id')
                ->where('customer_purchase_orders.status', 'Success')
                ->groupBy('products.id')
                ->orderBy('total_sold', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->product_name,
                        'sold' => (int) $item->total_sold
                    ];
                });

            Log::info('✅ Top selling products fetched', ['count' => $topProducts->count()]);
            return $topProducts;
        });
    }

    /**
     * Get low stock alerts
     * Groups by product_id only (not product_name) to avoid duplicates
     * Alerts if total stock > 0 and <= 5
     * Cached for 5 minutes
     */
    private function getLowStockAlerts()
    {
        return Cache::remember('dashboard_low_stock_alerts', 300, function () {
            Log::info('📊 Fetching low stock alerts...');

            $lowStockItems = Product_Stocks::select(
                'products.id',
                'products.product_name',
                DB::raw('SUM(product_stocks.stock_quantity) as total_stock')
            )
                ->join('products', 'product_stocks.product_id', '=', 'products.id')
                ->groupBy('products.id')
                ->havingRaw('SUM(product_stocks.stock_quantity) > 0 AND SUM(product_stocks.stock_quantity) <= 5')
                ->orderBy('total_stock', 'asc')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->product_name,
                        'left' => (int) $item->total_stock
                    ];
                });

            Log::info('✅ Low stock alerts fetched', ['count' => $lowStockItems->count()]);
            return $lowStockItems;
        });
    }

    /**
     * Get supplier status (active vs inactive)
     * Cached for 5 minutes
     */
    private function getSupplierStatus()
    {
        return Cache::remember('dashboard_supplier_status', 300, function () {
            $activeSuppliers = Suppliers::where('status', 'active')->count();
            $inactiveSuppliers = Suppliers::where('status', 'inactive')->count();
            $totalSuppliers = $activeSuppliers + $inactiveSuppliers;

            $percentage = $totalSuppliers > 0 ? round(($activeSuppliers / $totalSuppliers) * 100) : 0;

            return [
                'active' => $activeSuppliers,
                'inactive' => $inactiveSuppliers,
                'percentage' => $percentage
            ];
        });
    }

    /**
     * Get inventory status (Brand New vs Used)
     * Cached for 5 minutes
     */
    private function getInventoryStatus()
    {
        return Cache::remember('dashboard_inventory_status', 300, function () {
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
        });
    }
}
