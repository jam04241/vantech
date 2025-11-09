<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/product/add', function () {
    return view('PRODUCT.add_product');
})->name('product.add');

Route::get('/POS', function () {
    return view('DASHBOARD.POS');
})->name('POS');

Route::get('/Suppliers', function () {
    return view('DASHBOARD.suppliers');
})->name('suppliers');

Route::get('/Suppliers/List', function () {
    return view('DASHBOARD.suppliers_list');
})->name('suppliers.list');


Route::get('/staff', function () {
    return view('DASHBOARD/staff');
})->name('staff');

Route::get('/tester', function () {
    return view('tester.testscanner');
})->name('tester.testscanner');

Route::get('/POS/Items', function () {
    return view('POS_SYSTEM.item_list');
})->name('pos.items');

// LOGIN FORM
Route::get('/LOGIN_FORM', function () {
    return view('LOGIN_FORM.login');
})->name('login');

// Route::post('/login', [AuthController::class, 'login']);