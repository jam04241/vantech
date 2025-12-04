@extends('SIDEBAR.layouts')
@section('title', 'Sales')
@section('name', 'Sales')
@section('content')
    <div class="px-2 py-4">
        <h1 class="text-4xl font-bold mb-6">Sales Analytics Dashboard</h1>

        <!-- Date Range Filter -->
        <div class="mb-6 flex gap-4 flex-wrap items-center">
            <div class="flex gap-2 items-center">
                <input type="date" id="startDate"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="date" id="endDate"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button id="filterBtn"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Filter</button>
                <button id="clearBtn"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">Clear</button>
                <button id="refreshBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Refresh
                </button>
            </div>
            <div class="text-sm text-gray-600 ml-auto">
                <span id="dateRangeDisplay">Showing results for current month</span>
                <span class="ml-2 text-xs text-blue-600" id="lastUpdated"></span>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="hidden mb-4">
            <div class="flex items-center justify-center py-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="ml-2 text-gray-600">Loading sales data...</span>
            </div>
        </div>

        <!-- Error Message -->
        <div id="errorMessage" class="hidden mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Good Cost</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="totalGoodCost">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2">Cost for purchase order from Supplier</p>
                    </div>
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="totalOrders">0</p>
                        <p class="text-green-600 text-xs mt-2">Received Total Orders from Supplier</p>
                    </div>
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Avg Order Value</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="avgOrderValue">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2">Order value average from Supplier</p>
                    </div>
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Revenue</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="revenue">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2">Revenue from your retail products</p>
                    </div>
                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Profit</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2" id="profit">₱0.00</p>
                        <p class="text-green-600 text-xs mt-2">Total Good Cost - Total Revenue</p>
                    </div>
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Sales Trend</h2>
                <div class="h-80"> <!-- Fixed height container -->
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Top Products (by quantity sold)</h2>
                <div class="h-80"> <!-- Fixed height container -->
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Recent Transactions</h2>
                <div class="flex gap-2 items-center">
                    <!-- Search Bar -->
                    <div class="relative">
                        <input type="text" id="transactionSearch" placeholder="Search transactions..."
                            class="px-3 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm w-64">
                        <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <!-- Sort Dropdown -->
                    <select id="sortTransactions"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        <option value="date_desc">Newest First</option>
                        <option value="date_asc">Oldest First</option>
                        <option value="amount_desc">Highest Amount</option>
                        <option value="amount_asc">Lowest Amount</option>
                        <option value="customer">Customer A-Z</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Order ID</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Customer</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Amount</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Qty</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                </table>

                <!-- Scrollable Table Body -->
                <div class="max-h-80 overflow-y-auto border border-gray-200 rounded-b-lg">
                    <table class="w-full text-sm">
                        <tbody id="transactionsTable">
                            <!-- populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination Controls -->
            <div class="flex justify-between items-center mt-4">
                <div class="text-sm text-gray-600">
                    <span id="transactionInfo">Showing 0 - 0 of 0 transactions</span>
                </div>
                <div class="flex gap-2">
                    <button id="prevPage" disabled
                        class="px-3 py-1 bg-gray-200 text-gray-400 rounded-lg text-sm cursor-not-allowed">Previous</button>
                    <span id="pageInfo" class="px-3 py-1 text-sm">Page 1 of 1</span>
                    <button id="nextPage" disabled
                        class="px-3 py-1 bg-gray-200 text-gray-400 rounded-lg text-sm cursor-not-allowed">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Chart.js for charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Global variables for charts
        let salesTrendChart = null;
        let topProductsChart = null;

        // Transaction management variables
        let allTransactions = [];
        let filteredTransactions = [];
        let currentPage = 1;
        const itemsPerPage = 5;

        // API endpoints
        const API_ENDPOINTS = {
            salesData: '/api/sales/data',
            salesSummary: '/api/sales/summary',
            realTime: '/api/sales/realtime'
        };

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function () {
            initializeDashboard();
            setupEventListeners();

            // Load initial data
            loadSalesData();

            // Set up real-time updates every 30 seconds
            setInterval(updateRealTimeData, 30000);
        });

        function initializeDashboard() {
            // Set default dates (current month)
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            document.getElementById('startDate').value = formatDate(firstDay);
            document.getElementById('endDate').value = formatDate(lastDay);

            // Initialize charts with empty data
            initializeCharts();
        }

        function setupEventListeners() {
            document.getElementById('filterBtn').addEventListener('click', loadSalesData);
            document.getElementById('clearBtn').addEventListener('click', clearFilters);
            document.getElementById('refreshBtn').addEventListener('click', () => {
                loadSalesData();
                updateRealTimeData();
            });

            // Auto-apply filter when dates change
            document.getElementById('startDate').addEventListener('change', loadSalesData);
            document.getElementById('endDate').addEventListener('change', loadSalesData);

            // Transaction search and sort listeners
            document.getElementById('transactionSearch').addEventListener('input', filterAndDisplayTransactions);
            document.getElementById('sortTransactions').addEventListener('change', filterAndDisplayTransactions);
            document.getElementById('prevPage').addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    displayTransactions();
                }
            });
            document.getElementById('nextPage').addEventListener('click', () => {
                const totalPages = Math.ceil(filteredTransactions.length / itemsPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    displayTransactions();
                }
            });
        }

        function initializeCharts() {
            // Sales Trend Chart
            const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
            salesTrendChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Daily Sales',
                        data: [],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 10,
                            top: 10,
                            bottom: 10
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                callback: function (value) {
                                    return '₱' + value.toLocaleString();
                                },
                                font: {
                                    size: 11
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1,
                            callbacks: {
                                label: function (context) {
                                    return `Sales: ₱${context.parsed.y.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });

            // Top Products Chart
            const topCtx = document.getElementById('topProductsChart').getContext('2d');
            topProductsChart = new Chart(topCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Quantity Sold',
                        data: [],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(245, 101, 101, 0.8)',
                            'rgba(251, 191, 36, 0.8)',
                            'rgba(236, 72, 153, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(168, 85, 247, 0.8)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(139, 92, 246)',
                            'rgb(245, 101, 101)',
                            'rgb(251, 191, 36)',
                            'rgb(236, 72, 153)',
                            'rgb(34, 197, 94)',
                            'rgb(168, 85, 247)'
                        ],
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    indexAxis: 'y', // Horizontal bar chart
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: {
                            left: 10,
                            right: 20
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function (context) {
                                    // Show full product name in tooltip if available
                                    const dataIndex = context[0].dataIndex;
                                    return topProductsChart.data.datasets[0].fullNames?.[dataIndex] || context[0].label;
                                },
                                label: function (context) {
                                    return `Quantity: ${context.parsed.x} units`;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        async function loadSalesData() {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            if (!startDate || !endDate) {
                showError('Please select both start and end dates.');
                return;
            }

            showLoading(true);
            hideError();

            try {
                const params = new URLSearchParams({
                    start_date: startDate,
                    end_date: endDate
                });

                const response = await fetch(`${API_ENDPOINTS.salesData}?${params}`);
                const result = await response.json();

                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Failed to fetch sales data');
                }

                updateDashboard(result.data);
                updateDateRangeDisplay(startDate, endDate);
                updateLastUpdatedTime();

            } catch (error) {
                console.error('Error loading sales data:', error);
                showError('Error loading sales data: ' + error.message);

                // Show fallback empty state
                updateDashboard({
                    total_good_cost: 0,
                    total_orders: 0,
                    avg_order_value: 0,
                    revenue: 0,
                    profit: 0,
                    sales_trend: [],
                    top_products: [],
                    recent_transactions: []
                });
            } finally {
                showLoading(false);
            }
        }

        async function updateRealTimeData() {
            try {
                const response = await fetch(API_ENDPOINTS.realTime);
                const result = await response.json();

                if (response.ok && result.success) {
                    // Update today's metrics (could add small indicators)
                    updateLastUpdatedTime();
                }
            } catch (error) {
                console.error('Error updating real-time data:', error);
            }
        }

        function updateDashboard(data) {
            // Update metric cards
            updateMetrics(data);

            // Update charts
            updateSalesTrendChart(data.sales_trend);
            updateTopProductsChart(data.top_products);

            // Update transactions table
            updateTransactionsTable(data.recent_transactions);
        }

        function updateMetrics(data) {
            document.getElementById('totalGoodCost').textContent = formatCurrency(data.total_good_cost || 0);
            document.getElementById('totalOrders').textContent = (data.total_orders || 0).toLocaleString();
            document.getElementById('avgOrderValue').textContent = formatCurrency(data.avg_order_value || 0);
            document.getElementById('revenue').textContent = formatCurrency(data.revenue || 0);
            document.getElementById('profit').textContent = formatCurrency(data.profit || 0);
        }

        function updateSalesTrendChart(salesTrend) {
            if (!salesTrend || salesTrend.length === 0) {
                salesTrendChart.data.labels = ['No data'];
                salesTrendChart.data.datasets[0].data = [0];
            } else {
                // Limit to reasonable number of data points for better performance
                const maxDataPoints = 30;
                const limitedTrend = salesTrend.length > maxDataPoints
                    ? salesTrend.slice(-maxDataPoints)
                    : salesTrend;

                const labels = limitedTrend.map(item => formatChartDate(item.date));
                const data = limitedTrend.map(item => item.sales);

                salesTrendChart.data.labels = labels;
                salesTrendChart.data.datasets[0].data = data;
            }
            salesTrendChart.update('active');
        }

        function updateTopProductsChart(topProducts) {
            if (!topProducts || topProducts.length === 0) {
                topProductsChart.data.labels = ['No products'];
                topProductsChart.data.datasets[0].data = [0];
                topProductsChart.data.datasets[0].fullNames = ['No products'];
            } else {
                // Limit to maximum 8 products for optimal display
                const limitedProducts = topProducts.slice(0, 8);
                const labels = limitedProducts.map(item => item.product_name);
                const data = limitedProducts.map(item => item.quantity);
                const fullNames = limitedProducts.map(item => item.full_name || item.product_name);

                topProductsChart.data.labels = labels;
                topProductsChart.data.datasets[0].data = data;
                topProductsChart.data.datasets[0].fullNames = fullNames; // Store full names for tooltips
            }
            topProductsChart.update('active');
        }

        function updateTransactionsTable(transactions) {
            allTransactions = transactions || [];
            currentPage = 1;
            filterAndDisplayTransactions();
        }

        function filterAndDisplayTransactions() {
            const searchTerm = document.getElementById('transactionSearch').value.toLowerCase();
            const sortBy = document.getElementById('sortTransactions').value;

            // Filter transactions based on search term
            filteredTransactions = allTransactions.filter(transaction => {
                const searchableText = [
                    transaction.id.toString(),
                    transaction.customer_name,
                    transaction.amount.toString(),
                    transaction.items,
                    transaction.date
                ].join(' ').toLowerCase();

                return searchableText.includes(searchTerm);
            });

            // Sort transactions
            filteredTransactions.sort((a, b) => {
                switch (sortBy) {
                    case 'date_desc':
                        return new Date(b.date) - new Date(a.date);
                    case 'date_asc':
                        return new Date(a.date) - new Date(b.date);
                    case 'amount_desc':
                        return b.amount - a.amount;
                    case 'amount_asc':
                        return a.amount - b.amount;
                    case 'customer':
                        return a.customer_name.localeCompare(b.customer_name);
                    default:
                        return 0;
                }
            });

            currentPage = 1; // Reset to first page when filtering
            displayTransactions();
        }

        function displayTransactions() {
            const tbody = document.getElementById('transactionsTable');
            tbody.innerHTML = '';

            if (filteredTransactions.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No transactions found</td></tr>';
                updatePaginationInfo(0, 0, 0);
                return;
            }

            // Calculate pagination
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, filteredTransactions.length);
            const pageTransactions = filteredTransactions.slice(startIndex, endIndex);

            // Display transactions
            pageTransactions.forEach(transaction => {
                const row = document.createElement('tr');
                row.className = 'border-b hover:bg-gray-50';

                // Parse items to separate product name and quantity
                const itemMatch = transaction.items.match(/^(\d+)x\s+(.+)$/);
                const quantity = itemMatch ? itemMatch[1] : '1';
                const productName = itemMatch ? itemMatch[2] : transaction.items;

                row.innerHTML = `
                                                                <td class="px-4 py-3">#${transaction.id}</td>
                                                                <td class="px-4 py-3">${transaction.customer_name}</td>
                                                                <td class="px-4 py-3 font-semibold">${formatCurrency(transaction.amount)}</td>
                                                                <td class="px-4 py-3" title="${productName}">${productName.length > 20 ? productName.substring(0, 20) + '...' : productName}</td>
                                                                <td class="px-4 py-3 text-center font-medium">${quantity}</td>
                                                                <td class="px-4 py-3">${transaction.date}</td>
                                                                <td class="px-4 py-3">
                                                                    <span class="px-2 py-1 text-xs rounded-full ${getStatusClass(transaction.status)}">
                                                                        ${transaction.status}
                                                                    </span>
                                                                </td>
                                                            `;
                tbody.appendChild(row);
            });

            updatePaginationInfo(startIndex + 1, endIndex, filteredTransactions.length);
            updatePaginationControls();
        }

        function updatePaginationInfo(start, end, total) {
            document.getElementById('transactionInfo').textContent = `Showing ${start} - ${end} of ${total} transactions`;
        }

        function updatePaginationControls() {
            const totalPages = Math.ceil(filteredTransactions.length / itemsPerPage);
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageInfo = document.getElementById('pageInfo');

            // Update page info
            pageInfo.textContent = `Page ${currentPage} of ${Math.max(1, totalPages)}`;

            // Update previous button
            if (currentPage > 1) {
                prevBtn.disabled = false;
                prevBtn.className = 'px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 cursor-pointer';
            } else {
                prevBtn.disabled = true;
                prevBtn.className = 'px-3 py-1 bg-gray-200 text-gray-400 rounded-lg text-sm cursor-not-allowed';
            }

            // Update next button
            if (currentPage < totalPages) {
                nextBtn.disabled = false;
                nextBtn.className = 'px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 cursor-pointer';
            } else {
                nextBtn.disabled = true;
                nextBtn.className = 'px-3 py-1 bg-gray-200 text-gray-400 rounded-lg text-sm cursor-not-allowed';
            }
        }

        function clearFilters() {
            // Reset to current month
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);

            document.getElementById('startDate').value = formatDate(firstDay);
            document.getElementById('endDate').value = formatDate(lastDay);

            loadSalesData();
        }

        function showLoading(show) {
            document.getElementById('loadingIndicator').classList.toggle('hidden', !show);
        }

        function showError(message) {
            const errorElement = document.getElementById('errorMessage');
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }

        function hideError() {
            document.getElementById('errorMessage').classList.add('hidden');
        }

        function updateDateRangeDisplay(startDate, endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const display = `${start.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })} - ${end.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
            document.getElementById('dateRangeDisplay').textContent = `Showing results for ${display}`;
        }

        function updateLastUpdatedTime() {
            const now = new Date();
            document.getElementById('lastUpdated').textContent = `Last updated: ${now.toLocaleTimeString()}`;
        }

        // Utility functions
        function formatDate(date) {
            return date.toISOString().split('T')[0];
        }

        function formatChartDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }

        function formatCurrency(amount) {
            return '₱' + parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2 });
        }

        function getStatusClass(status) {
            switch (status.toLowerCase()) {
                case 'success':
                    return 'bg-green-100 text-green-800';
                case 'pending':
                    return 'bg-yellow-100 text-yellow-800';
                case 'cancelled':
                    return 'bg-red-100 text-red-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }
    </script>
@endsection