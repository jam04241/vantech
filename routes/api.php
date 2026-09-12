<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DrTransactionController;

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
Route::middleware(['web', 'auth'])->prefix('dashboard')->group(function () {
    Route::get('/data', [DashboardController::class, 'getDashboardData']);
});

// Sales Analytics API Routes (accessible from web with session auth)
// 'web' alone only starts the session - 'auth' is what actually requires a login.
// Without it these returned full revenue figures to anonymous callers.
Route::middleware(['web', 'auth'])->prefix('sales')->group(function () {
    Route::get('/data', [SalesController::class, 'getSalesData']);
    Route::get('/summary', [SalesController::class, 'getSalesSummary']);
    Route::get('/realtime', [SalesController::class, 'getRealTimeSales']);
});

// DR Transaction API Routes
Route::middleware(['web', 'auth'])->prefix('dr')->group(function () {
    Route::get('/next-number', [DRTransactionController::class, 'getNextDRNumber']);
});

// DR Transaction detail endpoint
Route::middleware(['web', 'auth'])->get('/dr-transactions/{id}', function ($id) {
    $drTransaction = \App\Models\DRTransaction::find($id);
    if (!$drTransaction) {
        return response()->json(['error' => 'DR Transaction not found'], 404);
    }
    return response()->json($drTransaction);
});

// Debugging endpoints. These dump sales, customer and stock records, so they are
// only registered outside production and still require a logged-in session.
if (!app()->environment('production')) {
    Route::middleware(['web', 'auth'])->prefix('test')->group(function () {
        Route::get('/sales-data', [SalesController::class, 'getSalesData']);
        Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);

        Route::get('/database-check', function () {
            return [
                'customer_purchase_orders_count' => \App\Models\CustomerPurchaseOrder::count(),
                'purchase_details_count' => \App\Models\Purchase_Details::count(),
                'products_count' => \App\Models\Product::count(),
                'customers_count' => \App\Models\Customer::count(),
                'product_stocks_count' => \App\Models\Product_Stocks::count(),
                'product_stocks_data' => \App\Models\Product_Stocks::limit(5)->get(),
                'recent_customer_orders' => \App\Models\CustomerPurchaseOrder::latest()->limit(5)->get(),
                'recent_purchase_details' => \App\Models\Purchase_Details::latest()->limit(5)->get(),
                'total_stock_value' => \App\Models\Product_Stocks::select(
                    \Illuminate\Support\Facades\DB::raw('SUM(CAST(stock_quantity AS UNSIGNED) * price) as total_sales')
                )->value('total_sales')
            ];
        });
    });
}
