<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\PurchaseDetailsController;
use App\Http\Controllers\ProductStocksController;
use App\Http\Controllers\CheckoutController;
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

Route::get('/POS', function () {
    return view('DASHBOARD.Sales');
})->name('Sales');


Route::get('/staff', function () {
    return view('DASHBOARD/staff');
})->name('staff');

Route::get('/tester', function () {
    return view('tester.testscanner');
})->name('tester.testscanner');

Route::get('/brandcategory', function () {
    return view('INVENTORY.brandcategoryHistory');
})->name('inventory.brandcategory');

// Issue Receipt for Quotation and Purchase
Route::get('/Receipt/Purchase', function () {
    return view('POS_SYSTEM.PurchaseReceipt');
})->name('pos.purchasereceipt');

Route::get('/Receipt/Quotation', function () {
    return view('POS_SYSTEM.QuotationReceipt');
})->name('pos.quotationreceipt');

Route::get('/PointOfSale', [BrandController::class, 'posBrand'])->name('pos.itemlist');

// POS ITEM LIST

Route::get('/Stock-Out', function () {
    return view('INVENTORY.stock_out');
})->name('inventory.stockout');

Route::get('/Total Stocks', function () {
    return view('partials.total_stock');
})->name('inventory.stocktotal');

Route::get('/Audit', function () {
    return view('DASHBOARD.audit');
})->name('audit.logs');




// Purchase Orders List
Route::get('/Suppliers/List', [PurchaseDetailsController::class, 'index'])->name('suppliers.list');
// Confirm purchase order
Route::put('/purchase/{id}/confirm', [PurchaseDetailsController::class, 'confirm'])->name('purchase.confirm');
Route::get('/purchase/statistics', [PurchaseDetailsController::class, 'statistics'])->name('purchase.statistics');

Route::get('/staff/AddEmployee', function () {
    return view(' Employee.employeeForm');
})->name('add.employee');

Route::get('/PointOfSale/AddCustomer', function () {
    return view('Customer.addCustomer');
})->name('customer.addCustomer');



Route::get('/PointOfSale/purchaseFrame', function () {
    return view('POS_SYSTEM.purchaseFrame');
})->name('pointofsale.purchaseframe');

// ROUTE FOR DATABASE
// Brand routes
Route::get('/brands', [BrandController::class, 'index'])->name('brands');
Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brands.update');

// Category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

// Product routes
Route::get('/product/add', [ProductController::class, 'create'])->name('product.add');
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::put('/products/{product}/price', [ProductStocksController::class, 'updatePrice'])->name('products.update_price');


// ============= AUTO-SUGGESTION API ROUTE =============
Route::get('/api/products/recent', [ProductController::class, 'getRecentProducts'])->name('products.recent');
// ============= END AUTO-SUGGESTION API ROUTE =============

// ============= SERIAL NUMBER DUPLICATE CHECK API ROUTE =============
Route::get('/api/products/check-serial', [ProductController::class, 'checkSerialNumber'])->name('products.check-serial');
// ============= END SERIAL NUMBER DUPLICATE CHECK API ROUTE =============


// ============= CUSTOMER AUTOSUGGESTION API ROUTE =============
Route::get('/api/customers/search', [CustomerController::class, 'searchCustomers'])->name('customers.search');
// ============= END CUSTOMER AUTOSUGGESTION API ROUTE =============


// ============= END CHECKOUT API ROUTE =============

// Suppliers routes
Route::get('/suppliers', [SuppliersController::class, 'index'])->name('suppliers');
Route::post('/suppliers', [SuppliersController::class, 'store'])->name('suppliers.store');
Route::get('/suppliers/{id}', [SuppliersController::class, 'show']);
Route::post('/suppliers/{id}', [SuppliersController::class, 'update']);
Route::post('/suppliers/{supplier}/toggle-status', [SuppliersController::class, 'toggleStatus'])->name('suppliers.toggle-status');

// Inventory fetch 
Route::get('/inventory', [ProductController::class, 'show'])->name('inventory'); // For inventory view
Route::get('/inventory/brands', [BrandController::class, 'inventoryBrand'])->name('inventory.brands'); //dropdown brands
Route::get('/inventory/categories', [CategoryController::class, 'inventorygetCategories'])->name('inventory.categories'); //dropdown categories

// Inventory_list fetch PRODUCTS
Route::get('/inventory/list', [ProductController::class, 'inventoryList'])->name('inventory.list'); //inventory list with search and sorting
Route::get('/inventory/list/categories', [CategoryController::class, 'inventoryListgetCategories'])->name('inventory.list.categories'); //dropdown categories

// Brand History and Category History fetch
Route::get('/brandcategory/list', [CategoryController::class, 'brandHistory'])->name('brandcategory.brands'); //dropdown categories
Route::get('/brandcategory/list', [CategoryController::class, 'categoryHistory'])->name('brandcategory.categories'); //dropdown categories

// POS BRAND DROPDOWN
Route::get('/PointOfSale/brand', [BrandController::class, 'posBrand'])->name('pos.brands');
// POS CATEGORIES DROPDOWN (JSON API)
Route::get('/PointOfSale/categories', [CategoryController::class, 'posCategories'])->name('pos.categories');
// POS PRODUCTS LIST WITH GROUPED STOCK


// Purchase Details Routes
Route::get('/suppliers/purchase-orders', [PurchaseDetailsController::class, 'create'])
    ->name('suppliers.purchase-orders');
Route::get('/purchase/create', [PurchaseDetailsController::class, 'create'])->name('purchase.create');
Route::post('/purchase/store', [PurchaseDetailsController::class, 'store'])->name('purchase.store');


Route::post('/customers', [CustomerController::class, 'store'])->name('customer.store');


// POS Routes
Route::get('/pos', [ProductController::class, 'posList'])->name('pos.itemlist');
Route::get('/pos/receipt', [CheckoutController::class, 'showReceipt'])->name('pos.purchasereceipt');

// Checkout Route
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Customer Routes
Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
Route::get('/api/customers/search', [CustomerController::class, 'search']);

// Product search by serial number for POS
Route::get('/api/products/search-pos', [ProductController::class, 'getProductBySerialNumber']);

// LOGIN FORM
Route::get('/LOGIN_FORM', function () {
    return view('LOGIN_FORM.login');
})->name('login');

    // Route::post('/login', [AuthController::class, 'login']);
