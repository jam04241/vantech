@extends('SIDEBAR.layouts')
@section('title', 'Sales')
@section('name', 'Sales')
@section('content')
    <div class="px-2 py-4">
        <h1 class="text-4xl font-bold mb-6">Sales Dashboard</h1>

        <!-- Date Range Filter -->
        <div class="mb-6 flex gap-4 flex-wrap">
            <div class="flex gap-2">
                <input type="date" id="startDate"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="date" id="endDate"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button onclick="filterSales()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Filter</button>
            </div>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Sales Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Sales</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="totalSales">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2">+12% from last period</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Orders Count Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="totalOrders">0</p>
                        <p class="text-green-600 text-xs mt-2">+8% from last period</p>
                    </div>
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>

            <!-- Average Order Value Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Avg Order Value</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="avgOrderValue">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2">+5% from last period</p>
                    </div>
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>

            <!-- Revenue Card -->
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Revenue</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="revenue">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2">+15% from last period</p>
                    </div>
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Sales Trend Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Sales Trend</h2>
                <canvas id="salesTrendChart"></canvas>
            </div>

            {{-- <!-- Revenue by Category Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Revenue by Category</h2>
                <canvas id="categoryChart"></canvas>
            </div> --}}

            <!-- Top Products Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Top Products</h2>
                <canvas id="topProductsChart"></canvas>
            </div>

            {{-- <!-- Sales by Hour Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Sales by Hour</h2>
                <canvas id="hourlyChart"></canvas>
            </div> --}}
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Transactions</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Order ID</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Customer</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Amount</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Items</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody id="transactionsTable">
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-600">#ORD001</td>
                            <td class="px-4 py-3 text-gray-600">John Doe</td>
                            <td class="px-4 py-3 font-semibold text-gray-800">₱2,500.00</td>
                            <td class="px-4 py-3 text-gray-600">3</td>
                            <td class="px-4 py-3 text-gray-600">Nov 22, 2025</td>
                            <td class="px-4 py-3"><span
                                    class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Completed</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize charts with sample data
        let salesTrendChart, categoryChart, topProductsChart, hourlyChart;

        function initializeCharts() {
            // Sales Trend Chart
            const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
            salesTrendChart = new Chart(salesTrendCtx, {
                type: 'line',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales (₱)',
                        data: [12000, 15000, 13000, 18000, 22000, 25000, 20000],
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Revenue by Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            categoryChart = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Laptops', 'Desktops', 'Peripherals', 'Accessories', 'Software'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            '#3b82f6',
                            '#10b981',
                            '#f59e0b',
                            '#ef4444',
                            '#8b5cf6'
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Top Products Chart
            const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
            topProductsChart = new Chart(topProductsCtx, {
                type: 'bar',
                data: {
                    labels: ['Product A', 'Product B', 'Product C', 'Product D', 'Product E'],
                    datasets: [{
                        label: 'Units Sold',
                        data: [45, 38, 32, 28, 22],
                        backgroundColor: '#10b981',
                        borderRadius: 5,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Sales by Hour Chart
            const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
            hourlyChart = new Chart(hourlyCtx, {
                type: 'bar',
                data: {
                    labels: ['9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM'],
                    datasets: [{
                        label: 'Sales (₱)',
                        data: [2000, 3500, 4200, 5800, 6200, 5500, 4800, 3200, 2100],
                        backgroundColor: '#f59e0b',
                        borderRadius: 5,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Update metrics
            updateMetrics();
        }

        function updateMetrics() {
            document.getElementById('totalSales').textContent = '₱125,000.00';
            document.getElementById('totalOrders').textContent = '45';
            document.getElementById('avgOrderValue').textContent = '₱2,777.78';
            document.getElementById('revenue').textContent = '₱125,000.00';
        }

        function filterSales() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (startDate && endDate) {
                console.log('Filtering sales from', startDate, 'to', endDate);
                // TODO: Make API call to fetch filtered data
                // Example: fetch(`/api/sales?start=${startDate}&end=${endDate}`)
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', initializeCharts);
    </script>
@endsection