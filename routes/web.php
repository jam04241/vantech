<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\PurchaseDetailsController;
use App\Http\Controllers\ProductStocksController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\ServiceReplacementController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\AuditlogController;

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
// ============= AUTHENTICATION ROUTES (PUBLIC) =============
Route::get('/LOGIN_FORM', [AuthController::class, 'create'])->name('login');
Route::post('/LOGIN_FORM', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
Route::post('/verify-admin-password', [AuthController::class, 'verifyAdminPassword'])->name('verify.admin.password');


// ============= PROTECTED ROUTES (AUTHENTICATED USERS ONLY) =============
Route::middleware(['auth'])->group(function () {
    // Root redirect
    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('redirect');

    // ============= DASHBOARD (ACCESSIBLE BY ALL AUTHENTICATED USERS) =============
    Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // ============= ADMIN ONLY ROUTES =============
    Route::middleware(['admin.only'])->group(function () {
        // Place admin-only routes here
        Route::get('/POS', function () {
            return view('DASHBOARD.Sales');
        })->name('Sales');

        // Sales Dashboard Route
        Route::get('/sales', [SalesController::class, 'index'])->name('sales.dashboard');

        Route::get('/tester', function () {
            return view('tester.testscanner');
        })->name('tester.testscanner');

        Route::get('/brandcategory', function () {
            return view('INVENTORY.brandcategoryHistory');
        })->name('inventory.brandcategory');

        // POS ITEM LIST
        Route::get('/Suppliers/Create_Orders', function () {
            return view('SUPPLIERS.suppliers_purchase');
        })->name('Supplier.CreateOrders');

        Route::get('/Total Stocks', function () {
            return view('partials.total_stock');
        })->name('inventory.stocktotal');

        Route::get('/Audit', [AuditlogController::class, 'index'])->name('audit.logs');
    });
    // ============= STAFF AND ADMIN SHARED ROUTES =============
    Route::middleware(['staff.only'])->group(function () {
        // Place staff-and-admin shared routes here
        Route::get('/PointOfSale/AddCustomer', function () {
            return view('Customer.addCustomer');
        })->name('customer.addCustomer');

        Route::get('/PointOfSale/purchaseFrame', function () {
            return view('POS_SYSTEM.purchaseFrame');
        })->name('pointofsale.purchaseframe');

        Route::get('/Service', function () {
            return view('ServicesOrder.Services');
        })->name('services.dashboard');

        // Service Record Route
        Route::get('/Service_Records', function () {
            return view('DASHBOARD.service_record');
        })->name('service.records');

        // Acknowledgement Receipt Route (view only)
        Route::get('/acknowledgement-receipt', function () {
            $authenticatedUser = Auth::user();
            $preparedBy = 'N/A';
            $preparedByRole = 'N/A';
            if ($authenticatedUser) {
                $preparedBy = trim($authenticatedUser->first_name . ' ' .
                    ($authenticatedUser->middle_name ? $authenticatedUser->middle_name . ' ' : '') .
                    $authenticatedUser->last_name);

                // Translate role based on conditions
                if ($authenticatedUser->role === 'admin') {
                    $preparedByRole = $authenticatedUser->id === 1 ? 'Owner' : 'Co Owner';
                } else {
                    $preparedByRole = ucfirst($authenticatedUser->role ?? 'N/A');
                }
            }
            return view('ServicesOrder.components.AcknowledgementReceipt', compact('preparedBy', 'preparedByRole'));
        })->name('acknowledgement.receipt');

        // Service Receipt Route (view only)
        Route::get('/service-receipt', function () {
            $authenticatedUser = Auth::user();
            $preparedBy = 'N/A';
            $preparedByRole = 'N/A';
            if ($authenticatedUser) {
                $preparedBy = trim($authenticatedUser->first_name . ' ' .
                    ($authenticatedUser->middle_name ? $authenticatedUser->middle_name . ' ' : '') .
                    $authenticatedUser->last_name);

                // Translate role based on conditions
                if ($authenticatedUser->role === 'admin') {
                    $preparedByRole = $authenticatedUser->id === 1 ? 'Owner' : 'Co Owner';
                } else {
                    $preparedByRole = ucfirst($authenticatedUser->role ?? 'N/A');
                }
            }
            return view('ServicesOrder.components.ServiceReceipt', compact('preparedBy', 'preparedByRole'));
        })->name('service.receipt');

        // Services/Job Order Routes
        Route::resource('services', ServicesController::class);
        // Service audit logging routes
        Route::post('/services/{service}/log-acknowledgment', [ServicesController::class, 'logAcknowledgmentReceipt'])->name('services.log-acknowledgment');
        Route::post('/services/{service}/log-receipt', [ServicesController::class, 'logServiceReceipt'])->name('services.log-receipt');

        // Service Records HTMX Routes
        Route::get('/service-records', function () {
            return view('DASHBOARD.service_record');
        })->name('servicerecords.index');
        Route::get('/service-records/data', [ServicesController::class, 'getserviceRecords'])->name('services.getserviceRecords');
    });

    // Purchase Orders List
    Route::get('/Suppliers/List', [PurchaseDetailsController::class, 'index'])->name('suppliers.list');
    // Confirm purchase order
    Route::put('/purchase/{id}/confirm', [PurchaseDetailsController::class, 'confirm'])->name('purchase.confirm');
    Route::get('/purchase/statistics', [PurchaseDetailsController::class, 'statistics'])->name('purchase.statistics');
    Route::put('/purchase/{id}/cancel', [PurchaseDetailsController::class, 'cancel'])->name('purchase.cancel');

    // Employee Routes

    Route::get('/staff/AddEmployee', function () {
        return view('Employee.addEmployee');
    })->name('add.employee');
    Route::get('/staff/Records', [EmployeeController::class, 'show'])->name('staff.record');
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');

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

    // ============= API ROUTES (ACCESSIBLE BY ALL AUTHENTICATED USERS) =============
    Route::get('/api/products/recent', [ProductController::class, 'getRecentProducts'])->name('products.recent');
    Route::get('/api/products/check-serial', [ProductController::class, 'checkSerialNumber'])->name('products.check-serial');
    Route::get('/api/customers/search', [CustomerController::class, 'search'])->name('api.customers.search');
    Route::get('/api/products/search-pos', [ProductController::class, 'getProductBySerialNumber']);

    // API: Brands
    Route::get('/api/brands', [BrandController::class, 'index']);

    // API: Customers
    Route::get('/api/customers', [CustomerController::class, 'getApiList']);

    // API: Service Types
    Route::get('/api/service-types', [ServiceTypeController::class, 'getApiList']);
    Route::post('/api/service-types', [ServiceTypeController::class, 'store'])->name('api.service-types.store');
    Route::put('/api/service-types/{serviceType}', [ServiceTypeController::class, 'update'])->name('api.service-types.update');

    // API: Service Items (distinct types from services)
    Route::get('/api/service-items', function () {
        $items = \App\Models\Service::distinct()->whereNotNull('type')->pluck('type')->sort()->values();
        return response()->json($items);
    });

    // Services API routes
    Route::get('/api/services', [ServicesController::class, 'apiList'])->name('api.services.list');
    Route::get('/api/services/{service}', [ServicesController::class, 'show'])->name('api.services.show');
    Route::post('/api/services', [ServicesController::class, 'store'])->name('api.services.store');
    Route::put('/api/services/{service}', [ServicesController::class, 'update'])->name('api.services.update');
    Route::put('/api/services/{service}/archive', [ServicesController::class, 'archive'])->name('api.services.archive');
    Route::post('/api/service-replacements', [ServiceReplacementController::class, 'store'])->name('api.service-replacements.store');
    Route::put('/api/service-replacements/{serviceReplacement}', [ServiceReplacementController::class, 'update'])->name('api.service-replacements.update');
    Route::delete('/api/service-replacements/{serviceReplacement}', [ServiceReplacementController::class, 'destroy'])->name('api.service-replacements.destroy');

    // ============= END CHECKOUT API ROUTE =============

    // Suppliers routes
    Route::get('/suppliers', [SuppliersController::class, 'index'])->name('suppliers');
    Route::post('/suppliers', [SuppliersController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{id}', [SuppliersController::class, 'show']);
    Route::put('/suppliers/{id}', [SuppliersController::class, 'update'])->name('suppliers.update');
    Route::post('/suppliers/{supplier}/toggle-status', [SuppliersController::class, 'toggleStatus'])->name('suppliers.toggle-status');

    // Inventory fetch 
    Route::get('/inventory', [ProductController::class, 'show'])->name('inventory'); // For inventory view
    Route::get('/inventory/brands', [BrandController::class, 'inventoryBrand'])->name('inventory.brands'); //dropdown brands
    Route::get('/inventory/categories', [CategoryController::class, 'inventorygetCategories'])->name('inventory.categories'); //dropdown categories

    // Inventory_list fetch PRODUCTS
    Route::get('/inventory/list', [ProductController::class, 'inventoryList'])->name('inventory.list'); //inventory list with search and sorting
    Route::get('/inventory/list/categories', [CategoryController::class, 'inventoryListgetCategories'])->name('inventory.list.categories'); //dropdown categories

    // Stock-Out Records (products with stock_quantity = 0)
    Route::get('/inventory/stock-out', [StockOutController::class, 'index'])->name('inventory.stock-out');

    // Brand History and Category History fetch
    Route::get('/brandcategory/brands', [CategoryController::class, 'brandHistory'])->name('brandcategory.brands');
    Route::get('/brandcategory/categories', [CategoryController::class, 'categoryHistory'])->name('brandcategory.categories');

    // POS BRAND DROPDOWN
    Route::get('/PointOfSale/brand', [BrandController::class, 'posBrand'])->name('pos.brands');
    // POS CATEGORIES DROPDOWN (JSON API)
    Route::get('/PointOfSale/categories', [CategoryController::class, 'posCategories'])->name('pos.categories');
    // POS PRODUCTS LIST WITH GROUPED STOCK


    // Purchase Details Routes


    Route::get('/purchase/create', [PurchaseDetailsController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/store', [PurchaseDetailsController::class, 'store'])->name('purchase.store');


    Route::post('/customers', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/CustomerRecords', [CustomerController::class, 'index'])->name('customer.records');
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    //kani sad josh


    // POS Routes
    Route::get('/pos', [ProductController::class, 'posList'])->name('pos.itemlist');
    Route::get('/pos/receipt', [CheckoutController::class, 'showReceipt'])->name('pos.purchasereceipt');

    // Checkout Route
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});
