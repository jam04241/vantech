<?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\BrandController;
    use App\Http\Controllers\CategoryController;
    use App\Http\Controllers\ProductController;
    use App\Http\Controllers\SuppliersController;
    use App\Http\Controllers\PurchaseDetailsController;
    use App\Http\Controllers\ProductStocksController;
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

Route::get('/Suppliers', function () {
    return view('DASHBOARD.suppliers');
})->name('suppliers');

Route::get('/Suppliers/List', function () {
    return view('DASHBOARD.suppliers_orders');
})->name('suppliers.list');

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

Route::get('/Stock-Out', function () {
    return view('INVENTORY.stock_out');
})->name('inventory.stockout');

Route::get('/Total Stocks', function () {
    return view('partials.total_stock');
})->name('inventory.stocktotal');

// ROUTE FOR DATABASE
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

// Suppliers routes
Route::get('/Suppliers', [SuppliersController::class, 'index'])->name('suppliers');
Route::post('/suppliers', [SuppliersController::class, 'store'])->name('suppliers.store');
Route::post('/suppliers/{supplier}/toggle-status', [SuppliersController::class, 'toggleStatus'])->name('suppliers.toggle-status');
Route::get('/suppliers/{supplier}/edit', [SuppliersController::class, 'edit'])->name('suppliers.edit');
Route::put('/suppliers/{supplier}', [SuppliersController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{supplier}', [SuppliersController::class, 'destroy'])->name('suppliers.destroy');

// Inventory fetch 
Route::get('/inventory', [ProductController::class, 'show'])->name('inventory'); // For inventory view
Route::get('/inventory/brands', [BrandController::class, 'inventoryBrand'])->name('inventory.brands'); //dropdown brands
Route::get('/inventory/categories', [CategoryController::class, 'inventorygetCategories'])->name('inventory.categories'); //dropdown categories

// Inventory_list fetch PRODUCTS
Route::get('/inventory/list', [ProductController::class, 'inventoryList'])->name('inventory.list'); //inventory list with search and sorting
Route::get('/inventory/list/categories', [CategoryController::class, 'inventoryListgetCategories'])->name('inventory.list.categories'); //dropdown categories

    //USE FOR CHART.JS

    // Route::get('/api/sales/metrics', [SalesController::class, 'getMetrics']);
    // Route::get('/api/sales/trend', [SalesController::class, 'getTrendData']);
    // Route::get('/api/sales/by-category', [SalesController::class, 'getCategoryData']);
    // Route::get('/api/sales/top-products', [SalesController::class, 'getTopProducts']);
    // Route::get('/api/sales/hourly', [SalesController::class, 'getHourlyData']);
    // Route::get('/api/sales/transactions', [SalesController::class, 'getTransactions']);

// Brand History and Category History fetch
Route::get('/brandcategory/list', [CategoryController::class, 'brandHistory'])->name('brandcategory.brands'); //dropdown categories
Route::get('/brandcategory/list', [CategoryController::class, 'categoryHistory'])->name('brandcategory.categories'); //dropdown categories

// POS BRAND DROPDOWN
Route::get('/PointOfSale', [BrandController::class, 'posBrand'])->name('pos.brands');
// POS CATEGORIES DROPDOWN (JSON API)
Route::get('/PointOfSale/categories', [CategoryController::class, 'posCategories'])->name('pos.categories');

// Purchase Details Routes - CORRECTED
Route::get('/suppliers/purchase-orders', [PurchaseDetailsController::class, 'create'])
    ->name('suppliers.purchase-orders');
Route::post('/purchase/store', [PurchaseDetailsController::class, 'store'])->name('purchase.store');

// Purchase Orders List
Route::get('/Suppliers/List', [PurchaseDetailsController::class, 'index'])->name('suppliers.list');
// Confirm purchase order
Route::put('/purchase/{id}/confirm', [PurchaseDetailsController::class, 'confirm'])->name('purchase.confirm');
Route::get('/purchase/statistics', [PurchaseDetailsController::class, 'statistics'])->name('purchase.statistics');

    Route::get('/staff/AddEmployee', function () {
        return view(' Employee.employeeForm');
    })->name('add.employee');

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

    Route::get('/PointOfSale/AddCustomer', function () {
        return view('Customer.addCustomer');
    })->name('customer.addCustomer');

    Route::get('/PointOfSale/purchaseFrame', function () {
        return view('POS_SYSTEM.purchaseFrame');
    })->name('pointofsale.purchaseframe');

    // ROUTE FOR DATABAASE
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

    // ============= POS PRODUCT LOOKUP API ROUTE (Serial Number Search) =============
    // Used by purchaseFrame component to fetch product by serial number
    Route::get('/api/products/search-pos', [ProductController::class, 'getProductBySerialNumber'])->name('products.search-pos');
    // ============= END POS PRODUCT LOOKUP API ROUTE =============

    Route::get('/Suppliers', [SuppliersController::class, 'index'])->name('suppliers');
    Route::post('/suppliers', [SuppliersController::class, 'store'])->name('suppliers.store');
    Route::post('/suppliers/{supplier}/toggle-status', [SuppliersController::class, 'toggleStatus'])->name('suppliers.toggle-status');
    Route::get('/suppliers/{supplier}/edit', [SuppliersController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{supplier}', [SuppliersController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{supplier}', [SuppliersController::class, 'destroy'])->name('suppliers.destroy');


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
    Route::get('/PointOfSale', [BrandController::class, 'posBrand'])->name('pos.brands');
    // POS CATEGORIES DROPDOWN (JSON API)
    Route::get('/PointOfSale/categories', [CategoryController::class, 'posCategories'])->name('pos.categories');
    // POS PRODUCTS LIST WITH GROUPED STOCK
    Route::get('/PointOfSale/products', [ProductController::class, 'posList'])->name('pos.products');

    //Suppliers Purchase Orders Route - use controller so view has required data
    Route::get('/suppliers/purchase-orders', [PurchaseDetailsController::class, 'create'])
        ->name('suppliers.purchase-orders');

    // Store purchase order
    Route::get('/purchase/create', [PurchaseDetailsController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/store', [PurchaseDetailsController::class, 'store'])->name('purchase.store');


    // LOGIN FORM
    Route::get('/LOGIN_FORM', function () {
        return view('LOGIN_FORM.login');
    })->name('login');

    // Route::post('/login', [AuthController::class, 'login']);
