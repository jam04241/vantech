<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Dashboard API Routes (accessible from web with session auth)
Route::middleware('web')->prefix('dashboard')->group(function () {
    Route::get('/data', [DashboardController::class, 'getDashboardData']);
});

// Sales Analytics API Routes (accessible from web with session auth)
Route::middleware('web')->prefix('sales')->group(function () {
    Route::get('/data', [SalesController::class, 'getSalesData']);
    Route::get('/summary', [SalesController::class, 'getSalesSummary']);
    Route::get('/realtime', [SalesController::class, 'getRealTimeSales']);
});

// Test endpoints for debugging
Route::get('/test/sales-data', function () {
    $controller = new SalesController();
    return $controller->getSalesData(request());
});

Route::get('/test/dashboard-data', function () {
    $controller = new DashboardController();
    return $controller->getDashboardData();
});

Route::get('/test/database-check', function () {
    return [
        'customer_purchase_orders_count' => \App\Models\CustomerPurchaseOrder::count(),
        'purchase_details_count' => \App\Models\Purchase_Details::count(),
        'products_count' => \App\Models\Product::count(),
        'customers_count' => \App\Models\Customer::count(),
        'product_stocks_count' => \App\Models\Product_Stocks::count(),
        'product_stocks_data' => \App\Models\Product_Stocks::limit(5)->get(),
        'recent_customer_orders' => \App\Models\CustomerPurchaseOrder::latest()->limit(5)->get(),
        'recent_purchase_details' => \App\Models\Purchase_Details::latest()->limit(5)->get(),
        'total_sales_test' => \App\Models\Product_Stocks::select(
            \Illuminate\Support\Facades\DB::raw('SUM(CAST(stock_quantity AS UNSIGNED) * price) as total_sales')
        )->value('total_sales')
    ];
});
