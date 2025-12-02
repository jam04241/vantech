@extends('SIDEBAR.layouts')
@section('title', 'Dashboard')
@section('name', 'Dashboard')

@section('content')
    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Employees Card -->
        <div class="bg-white p-6 rounded-xl shadow border border-gray-200 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Employees</p>
                    <h1 class="text-3xl font-bold text-gray-800 mt-2" id="employeeCount">0</h1>
                    <p class="text-green-600 text-xs mt-2">Active staff members</p>
                </div>
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Total Customers Card -->
        <div class="bg-white p-6 rounded-xl shadow border border-gray-200 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Customers</p>
                    <h1 class="text-3xl font-bold text-gray-800 mt-2" id="customerCount">0</h1>
                    <p class="text-green-600 text-xs mt-2">Registered customers</p>
                </div>
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="bg-white p-6 rounded-xl shadow border border-gray-200 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Products</p>
                    <h1 class="text-3xl font-bold text-gray-800 mt-2" id="productCount">0</h1>
                    <p class="text-green-600 text-xs mt-2">Items in stock</p>
                </div>
                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>

        <!-- Today's Sales Card -->
        <div class="bg-white p-6 rounded-xl shadow border border-gray-200 hover:shadow-lg transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Today's Sales</p>
                    <h1 class="text-3xl font-bold text-gray-800 mt-2" id="todaysSales">₱0.00</h1>
                    <p class="text-green-600 text-xs mt-2">Daily revenue</p>
                </div>
                <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Dashboard Insights Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top Selling Products -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-3">
                <div class="bg-indigo-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                Top Selling Products
            </h2>
            <div class="h-64 overflow-y-auto space-y-3 pr-2" id="topProducts">
                <div class="text-gray-500 text-center py-8">Loading...</div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-xl shadow-lg border border-yellow-200 p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-3">
                <div class="bg-yellow-100 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                Low Stock Alert
            </h2>
            <div class="h-64 overflow-y-auto space-y-3 pr-2" id="lowStockItems">
                <div class="text-gray-500 text-center py-8">Loading...</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Supplier Status -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Supplier Status</h2>
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Active Suppliers</span>
                    <span class="text-2xl font-bold text-green-600" id="activeSuppliers">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Inactive Suppliers</span>
                    <span class="text-2xl font-bold text-red-600" id="inactiveSuppliers">-</span>
                </div>
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Active Percentage</span>
                        <span id="supplierPercentage">0%</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full transition-all duration-500" id="supplierBar"
                            style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Status -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Inventory Status</h2>
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Brand New</span>
                    <span class="text-2xl font-bold text-blue-600" id="brandNewProducts">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 font-medium">Used Products</span>
                    <span class="text-2xl font-bold text-orange-600" id="usedProducts">-</span>
                </div>
                <div class="mt-6">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>New Products Percentage</span>
                        <span id="inventoryPercentage">0%</span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full transition-all duration-500" id="inventoryBar"
                            style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            loadDashboardData();

            // Auto-refresh every 30 seconds
            setInterval(loadDashboardData, 30000);
        });

        async function loadDashboardData() {
            try {
                const response = await fetch('/api/dashboard/data');
                const result = await response.json();

                if (result.success) {
                    const data = result.data;
                    updateMetrics(data.metrics);
                    updateTopProducts(data.top_products);
                    updateLowStockAlerts(data.low_stock_alerts);
                    updateSupplierStatus(data.supplier_status);
                    updateInventoryStatus(data.inventory_status);
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                // Show fallback data
                showFallbackData();
            }
        }

        function updateMetrics(metrics) {
            document.getElementById('employeeCount').textContent = metrics.employees;
            document.getElementById('customerCount').textContent = metrics.customers;
            document.getElementById('productCount').textContent = metrics.products;
            document.getElementById('todaysSales').textContent = '₱' + new Intl.NumberFormat('en-PH').format(metrics.daily_sales);
        }

        function updateTopProducts(topProducts) {
            const container = document.getElementById('topProducts');

            if (topProducts.length === 0) {
                container.innerHTML = '<div class="text-gray-500 text-center py-8">No sales data available</div>';
                return;
            }

            const html = topProducts.map(product => `
                                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-800 text-sm">${product.name}</p>
                                                <p class="text-xs text-gray-500">${product.price} • ${product.sold} sold</p>
                                            </div>
                                            <div class="text-lg font-bold text-indigo-600">${product.sold}</div>
                                        </div>
                                    `).join('');
            container.innerHTML = html;
        }

        function updateLowStockAlerts(lowStockItems) {
            const container = document.getElementById('lowStockItems');

            if (lowStockItems.length === 0) {
                container.innerHTML = '<div class="text-green-600 text-center py-8 font-medium">All items well stocked!</div>';
                return;
            }

            const html = lowStockItems.map(item => `
                                        <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200 hover:bg-yellow-100 transition-colors">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-800 text-sm">${item.name}</p>
                                                <p class="text-xs text-yellow-600">${item.price} • ${item.left} left in stock</p>
                                            </div>
                                            <div class="text-lg font-bold text-yellow-600">${item.left}</div>
                                        </div>
                                    `).join('');
            container.innerHTML = html;
        }

        function updateSupplierStatus(supplierStatus) {
            document.getElementById('activeSuppliers').textContent = supplierStatus.active;
            document.getElementById('inactiveSuppliers').textContent = supplierStatus.inactive;
            document.getElementById('supplierPercentage').textContent = supplierStatus.percentage + '%';
            document.getElementById('supplierBar').style.width = supplierStatus.percentage + '%';
        }

        function updateInventoryStatus(inventoryStatus) {
            document.getElementById('brandNewProducts').textContent = inventoryStatus.brand_new;
            document.getElementById('usedProducts').textContent = inventoryStatus.used;
            document.getElementById('inventoryPercentage').textContent = inventoryStatus.percentage + '%';
            document.getElementById('inventoryBar').style.width = inventoryStatus.percentage + '%';
        }

        function showFallbackData() {
            // Show placeholder data when API fails
            updateMetrics({ employees: 0, customers: 0, products: 0, daily_sales: 0 });
            document.getElementById('topProducts').innerHTML = '<div class="text-gray-500 text-center py-8">Unable to load data</div>';
            document.getElementById('lowStockItems').innerHTML = '<div class="text-gray-500 text-center py-8">Unable to load data</div>';
            updateSupplierStatus({ active: 0, inactive: 0, percentage: 0 });
            updateInventoryStatus({ brand_new: 0, used: 0, percentage: 0 });
        }
    </script>
@endsection