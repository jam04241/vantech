@extends('SIDEBAR.layouts')
@section('title', 'Dashboard')
@section('name', 'Dashboard')

@section('content')
    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Top Selling Products -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                </svg>
                Top Selling Products
            </h2>
            <div class="space-y-3" id="topProducts">
                <div class="text-gray-500 text-sm">Loading...</div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-xl shadow border border-yellow-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4v2m0 4v2M6.228 6.228a9 9 0 1012.544 0M4.5 12a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0z">
                    </path>
                </svg>
                Low Stock Alert
            </h2>
            <div class="space-y-3" id="lowStockItems">
                <div class="text-gray-500 text-sm">Loading...</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Recent Activity
            </h2>
            <div class="space-y-3" id="recentActivity">
                <div class="text-gray-500 text-sm">Loading...</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Supplier Status -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Supplier Status</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Active Suppliers</span>
                    <span class="text-2xl font-bold text-green-600" id="activeSuppliers">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Inactive Suppliers</span>
                    <span class="text-2xl font-bold text-red-600" id="inactiveSuppliers">0</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden mt-4">
                    <div class="h-full bg-green-500" id="supplierBar" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Inventory Status -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Inventory Status</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">New Products</span>
                    <span class="text-2xl font-bold text-blue-600" id="newProducts">0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Used Products</span>
                    <span class="text-2xl font-bold text-orange-600" id="usedProducts">0</span>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden mt-4">
                    <div class="h-full bg-blue-500" id="inventoryBar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize dashboard with sample data
            // In production, these would be fetched from API endpoints

            // Update metrics
            document.getElementById('employeeCount').textContent = '12';
            document.getElementById('customerCount').textContent = '156';
            document.getElementById('productCount').textContent = '342';
            document.getElementById('todaysSales').textContent = '₱45,230.00';

            // Supplier stats
            document.getElementById('activeSuppliers').textContent = '8';
            document.getElementById('inactiveSuppliers').textContent = '2';
            document.getElementById('supplierBar').style.width = '80%';

            // Inventory stats
            document.getElementById('newProducts').textContent = '245';
            document.getElementById('usedProducts').textContent = '97';
            document.getElementById('inventoryBar').style.width = '72%';

            // Top Products
            const topProducts = [
                { name: 'Dell Laptop XPS 13', sales: 45 },
                { name: 'HP Monitor 24"', sales: 38 },
                { name: 'Logitech Keyboard', sales: 32 }
            ];

            const topProductsHtml = topProducts.map(product => `
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700 text-sm">${product.name}</span>
                        <span class="text-indigo-600 font-semibold">${product.sales} sold</span>
                    </div>
                `).join('');
            document.getElementById('topProducts').innerHTML = topProductsHtml;

            // Low Stock Items
            const lowStockItems = [
                { name: 'USB-C Cable', stock: 3 },
                { name: 'HDMI Cable', stock: 5 },
                { name: 'Mouse Pad', stock: 2 }
            ];

            const lowStockHtml = lowStockItems.map(item => `
                    <div class="flex justify-between items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <span class="text-gray-700 text-sm">${item.name}</span>
                        <span class="text-yellow-600 font-semibold">${item.stock} left</span>
                    </div>
                `).join('');
            document.getElementById('lowStockItems').innerHTML = lowStockHtml;

            // Recent Activity
            const activities = [
                { action: 'Product Added', time: '2 hours ago' },
                { action: 'Order Completed', time: '4 hours ago' },
                { action: 'Supplier Updated', time: '1 day ago' }
            ];

            const activityHtml = activities.map(activity => `
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-gray-700 text-sm">${activity.action}</span>
                        <span class="text-gray-500 text-xs">${activity.time}</span>
                    </div>
                `).join('');
            document.getElementById('recentActivity').innerHTML = activityHtml;
        });
    </script>
@endsection