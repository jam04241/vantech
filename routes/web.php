<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SuppliersController;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root URL to Dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/Dashboard', function () {
    return view('DASHBOARD.homepage');
})->name('dashboard');

// Inventory page route
Route::get('/inventory', function () {
    return view('DASHBOARD.inventory');
})->name('inventory');

Route::get('/inventory/list', function () {
    return view('DASHBOARD.inventory_list');
})->name('inventory.list');




Route::get('/POS', function () {
    return view('DASHBOARD.POS');
})->name('POS');

Route::get('/Suppliers', function () {
    return view('DASHBOARD.suppliers');
})->name('suppliers');

Route::get('/Suppliers/List', function () {
    return view('DASHBOARD.suppliers_orders');
})->name('suppliers.list');

Route::get('/tester', function () {
    return view('tester.testscanner');
})->name('tester.testscanner');

Route::get('/POS/Items', function () {
    return view('POS_SYSTEM.item_list');
})->name('pos.items');

// Brand routes

Route::get('/brands', [BrandController::class, 'index'])->name('brands');
Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');

// Category routes

Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

// Product routes
Route::get('/product/add', [ProductController::class, 'create'])->name('product.add');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

Route::get('/Suppliers', [SuppliersController::class, 'index'])->name('suppliers');
Route::post('/suppliers', [SuppliersController::class, 'store'])->name('suppliers.store');
Route::post('/suppliers/{supplier}/toggle-status', [SuppliersController::class, 'toggleStatus'])->name('suppliers.toggle-status');
Route::get('/suppliers/{supplier}/edit', [SuppliersController::class, 'edit'])->name('suppliers.edit');
Route::put('/suppliers/{supplier}', [SuppliersController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{supplier}', [SuppliersController::class, 'destroy'])->name('suppliers.destroy');