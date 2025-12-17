@extends('SIDEBAR.layouts')
@section('title', 'Sales Reports')
@section('name', 'SALES REPORTS')
@section('content')

    <div class="bg-white border rounded-lg p-6 shadow-sm">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('sales.reports') }}" class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search transactions by customer, receipt no..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                        aria-label="Search sales reports">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if(request('search'))
                        <button type="button" onclick="window.location.href='{{ route('sales.reports') }}'"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200"
                            title="Clear search">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </form>

            <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg font-medium hover:shadow-xl transform hover:-translate-y-0.5"
                aria-label="Print report">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Report
            </button>
        </div>

        <div class="border-t border-gray-200 my-6"></div>

        {{-- Date Range Filter (Auto-submit) - Moved to top --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('sales.reports') }}" id="dateFilterForm"
                class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white text-sm date-filter">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white text-sm date-filter">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Customer</label>
                    <select name="customer"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white text-sm">
                        <option value="">All Customers</option>
                        <!-- Populate with customer options -->
                    </select>
                </div>

                <div class="flex gap-2 items-end">
                    <button type="button" onclick="window.location.href='{{ route('sales.reports') }}'"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-white shadow hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out text-sm font-medium text-gray-700">
                        Reset
                    </button>
                </div>
            </form>
        </div>

        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Sales Transaction Reports</h2>
                <p class="text-gray-600 mt-1">View sales transactions through date range</p>
            </div>
        </div>

        {{-- Top Products and Top Customers Section (Screen only) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 no-print">
            {{-- Top Products --}}
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Top Selling Products
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Product</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Qty Sold</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Sales</th>
                            </tr>
                        </thead>
                        <tbody id="screen-top-products" class="divide-y divide-gray-200">
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">No data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Top Customers --}}
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Top Customers
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">#</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Customer</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Orders</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Total Spent
                                </th>
                            </tr>
                        </thead>
                        <tbody id="screen-top-customers" class="divide-y divide-gray-200">
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">No data available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Reports Table --}}
        <div id="printable-report" class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
            {{-- Print Header (Hidden on screen, visible on print) --}}
            <div class="print-header">
                <div class="flex justify-between items-start">
                    <div class="w-1/3">
                        <img src="{{ asset('images/logo.png') }}" class="w-28 h-auto mb-2" />
                        <h1 class="text-xl font-bold text-blue-700 mb-1">VANTECH COMPUTERS TRADING</h1>
                        <p class="text-xs text-gray-700">Van Bryan C. Bardillas - Sole Proprietor</p>
                        <p class="text-xs text-gray-700">Non VAT Reg. TIN 505-374240-00000</p>
                        <p class="text-xs text-gray-700">758 F Purok 3, Brgy. Mintal</p>
                        <p class="text-xs text-gray-700">Davao City, Davao del Sur, 8000</p>
                    </div>
                    <div class="w-2/3 text-right">
                        <h2 class="text-lg font-bold text-blue-700 mb-2">SALES TRANSACTION REPORT</h2>
                        <div class="print-date-range text-sm text-gray-700">
                            <strong>Report Date:</strong> {{ date('F d, Y') }}
                            @if(request('start_date') && request('end_date'))
                                <br><strong>Period:</strong> {{ date('M d, Y', strtotime(request('start_date'))) }} -
                                {{ date('M d, Y', strtotime(request('end_date'))) }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sales Summary (Print only) - 3 Cards --}}
            <div class="print-summary">
                <div class="grid grid-cols-3 gap-4">
                    <div class="print-summary-card">
                        <h4>TOTAL TRANSACTION</h4>
                        <p id="print-total-revenue">₱0.00</p>
                    </div>
                    <div class="print-summary-card">
                        <h4>TOTAL DISCOUNT</h4>
                        <p id="print-total-discount">₱0.00</p>
                    </div>
                    <div class="print-summary-card">
                        <h4>TOTAL TRANSACTIONS</h4>
                        <p id="print-total-transactions">0</p>
                    </div>
                </div>
            </div>

            {{-- Receipt Table Header (Print only) - Second Row --}}
            <div class="print-receipt-section">
                <h3 class="print-section-title">RECEIPT TRANSACTIONS</h3>
                <table class="print-receipt-table">
                    <thead>
                        <tr>
                            <th>Receipt No</th>
                            <th>Customer</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody id="print-receipt-body">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            {{-- Top Products and Top Customers Section (Print only) --}}
            <div class="print-top-sections">
                <div class="grid grid-cols-2 gap-4">
                    {{-- Top Products --}}
                    <div>
                        <h3 class="font-bold text-sm mb-2 text-blue-700">TOP SELLING PRODUCTS</h3>
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-2 py-1 text-left text-xs">#</th>
                                    <th class="border border-gray-300 px-2 py-1 text-left text-xs">Product Name</th>
                                    <th class="border border-gray-300 px-2 py-1 text-right text-xs">Qty</th>
                                    <th class="border border-gray-300 px-2 py-1 text-right text-xs">Sales</th>
                                </tr>
                            </thead>
                            <tbody id="print-top-products">
                                <tr>
                                    <td colspan="4"
                                        class="border border-gray-300 px-2 py-2 text-center text-xs text-gray-500">No data
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Top Customers --}}
                    <div>
                        <h3 class="font-bold text-sm mb-2 text-blue-700">TOP CUSTOMERS</h3>
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-2 py-1 text-left text-xs">#</th>
                                    <th class="border border-gray-300 px-2 py-1 text-left text-xs">Customer Name</th>
                                    <th class="border border-gray-300 px-2 py-1 text-right text-xs">Transactions</th>
                                    <th class="border border-gray-300 px-2 py-1 text-right text-xs">Total Spent</th>
                                </tr>
                            </thead>
                            <tbody id="print-top-customers">
                                <tr>
                                    <td colspan="4"
                                        class="border border-gray-300 px-2 py-2 text-center text-xs text-gray-500">No data
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto no-print">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Receipt No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Customer
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Qty
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Subtotal Price
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Discount
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Total Price
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Date & Time
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="sales-report-body">
                        <!-- Will be populated by JavaScript -->
                        <tr id="no-data-row">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2">No sales transactions found for the selected period</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Prepared By Section (Print only) --}}
            <div class="print-prepared-by">
                <div class="text-right">
                    <p class="text-sm py-12 mb-12">Prepared by:</p>
                    @php
                        $user = auth()->user();
                        $fullName = 'ADMIN';
                        $role = 'Administrator';
                        if ($user) {
                            $firstName = $user->first_name ?? '';
                            $middleName = $user->middle_name ?? '';
                            $lastName = $user->last_name ?? '';

                            if ($middleName) {
                                $fullName = strtoupper(trim($firstName . ' ' . $middleName . ' ' . $lastName));
                            } else {
                                $fullName = strtoupper(trim($firstName . ' ' . $lastName));
                            }
                            $role = $user->role ?? 'Administrator';
                        }
                    @endphp
                    <div class="h-10">

                    </div>
                    <p class="text-sm font-semibold mb-1">{{ $fullName }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $role }}</p>
                </div>
            </div>

            {{-- Print Footer --}}
            <div class="print-footer">
                <div class="text-center">
                    <p>Generated on {{ date('F d, Y g:i A') }}</p>
                    <p style="margin-top: 5px;">Page <span class="page-number"></span></p>
                    <p style="margin-top: 5px;">© {{ date('Y') }} Vantech Computers Trading. All rights reserved.</p>
                    <p>Document is confidential and intended for internal use only.</p>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="bg-white px-6 py-4 border-t border-gray-200 no-print">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">0</span> of
                    <span class="font-medium">0</span> results
                </div>
                <nav class="inline-flex rounded-md shadow-sm" aria-label="Pagination">
                    <!-- Pagination links will be added here -->
                </nav>
            </div>
        </div>
    </div>

    {{-- JavaScript for sales report --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-submit date filter
            const dateFilters = document.querySelectorAll('.date-filter');
            dateFilters.forEach(filter => {
                filter.addEventListener('change', function () {
                    document.getElementById('dateFilterForm').submit();
                });
            });

            // Load sales data
            loadSalesReportData();
        });

        async function loadSalesReportData() {
            const startDate = document.querySelector('input[name="start_date"]').value;
            const endDate = document.querySelector('input[name="end_date"]').value;

            try {
                const response = await fetch(`/api/sales/report?start_date=${startDate}&end_date=${endDate}`);
                const result = await response.json();

                if (result.success) {
                    updateSalesReportTable(result.data.transactions);
                    updatePrintSummary(result.data.summary);
                    updateTopProducts(result.data.top_products);
                    updateTopCustomers(result.data.top_customers);
                }
            } catch (error) {
                console.error('Error loading sales report:', error);
            }
        }

        function updateSalesReportTable(transactions) {
            const tbody = document.getElementById('sales-report-body');
            const noDataRow = document.getElementById('no-data-row');
            const printTbody = document.getElementById('print-receipt-body');

            if (transactions.length === 0) {
                noDataRow.classList.remove('hidden');
                if (printTbody) {
                    printTbody.innerHTML = '<tr><td colspan="7" class="text-center">No transactions found</td></tr>';
                }
                return;
            }

            noDataRow.classList.add('hidden');
            tbody.innerHTML = '';
            if (printTbody) {
                printTbody.innerHTML = '';
            }

            transactions.forEach(transaction => {
                // Screen table row
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition duration-150';
                row.innerHTML = `
                                                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                                                                    ${transaction.receipt_no || '-'}
                                                                                                </td>
                                                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                                                    ${transaction.customer_name}
                                                                                                </td>
                                                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                                                                    ${transaction.qty || 0}
                                                                                                </td>
                                                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                                                                    ₱${parseFloat(transaction.subtotal || transaction.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}
                                                                                                </td>
                                                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                                                                    ₱${parseFloat(transaction.discount || 0).toLocaleString('en-US', { minimumFractionDigits: 2 })}
                                                                                                </td>
                                                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                                                                                    ₱${parseFloat(transaction.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}
                                                                                                </td>
                                                                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                                                    ${new Date(transaction.date).toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                })}<br>
                                                                                                    <span class="text-gray-500 text-xs">${new Date(transaction.date).toLocaleTimeString()}</span>
                                                                                                </td>
                                                                                            `;
                tbody.appendChild(row);

                // Print table row
                if (printTbody) {
                    const printRow = document.createElement('tr');
                    printRow.innerHTML = `
                                                                                                    <td>${transaction.receipt_no || '-'}</td>
                                                                                                    <td>${transaction.customer_name}</td>
                                                                                                    <td class="text-center">${transaction.qty || 0}</td>
                                                                                                    <td class="text-right">₱${parseFloat(transaction.subtotal || transaction.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                                                                                    <td class="text-right">₱${parseFloat(transaction.discount || 0).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                                                                                    <td class="text-right">₱${parseFloat(transaction.amount).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                                                                                    <td>${new Date(transaction.date).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    })} ${new Date(transaction.date).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</td>
                                                                                                `;
                    printTbody.appendChild(printRow);
                }
            });
        }

        function updatePrintSummary(summary) {
            if (!summary) return;

            document.getElementById('print-total-revenue').textContent =
                `₱${parseFloat(summary.revenue || 0).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
            document.getElementById('print-total-discount').textContent =
                `₱${parseFloat(summary.discount || 0).toLocaleString('en-US', { minimumFractionDigits: 2 })}`;
            document.getElementById('print-total-transactions').textContent =
                (summary.total_orders || 0).toLocaleString();
        }

        function updateTopProducts(products) {
            // Update print table
            const printTbody = document.getElementById('print-top-products');
            printTbody.innerHTML = '';

            // Update screen table
            const screenTbody = document.getElementById('screen-top-products');
            screenTbody.innerHTML = '';

            if (!products || products.length === 0) {
                printTbody.innerHTML = '<tr><td colspan="4" class="border border-gray-300 px-2 py-2 text-center text-xs text-gray-500">No data</td></tr>';
                screenTbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No data available</td></tr>';
                return;
            }

            products.forEach((product, index) => {
                // Print table row
                const printRow = document.createElement('tr');
                printRow.innerHTML = `
                                                                                                                        <td class="border border-gray-300 px-2 py-1 text-xs">${index + 1}</td>
                                                                                                                        <td class="border border-gray-300 px-2 py-1 text-xs">${product.product_name}</td>
                                                                                                                        <td class="border border-gray-300 px-2 py-1 text-right text-xs">${product.quantity}</td>
                                                                                                                        <td class="border border-gray-300 px-2 py-1 text-right text-xs">₱${parseFloat(product.sales).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                                                                                                    `;
                printTbody.appendChild(printRow);

                // Screen table row
                const screenRow = document.createElement('tr');
                screenRow.className = 'hover:bg-gray-50 transition duration-150';
                screenRow.innerHTML = `
                                                                                                                        <td class="px-4 py-3 text-sm text-gray-900">${index + 1}</td>
                                                                                                                        <td class="px-4 py-3 text-sm text-gray-900">${product.product_name}</td>
                                                                                                                        <td class="px-4 py-3 text-sm text-gray-900 text-right font-semibold">${product.quantity}</td>
                                                                                                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">₱${parseFloat(product.sales).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                                                                                                    `;
                screenTbody.appendChild(screenRow);
            });
        }

        function updateTopCustomers(customers) {
            // Update print table
            const printTbody = document.getElementById('print-top-customers');
            printTbody.innerHTML = '';

            // Update screen table
            const screenTbody = document.getElementById('screen-top-customers');
            screenTbody.innerHTML = '';

            if (!customers || customers.length === 0) {
                printTbody.innerHTML = '<tr><td colspan="4" class="border border-gray-300 px-2 py-2 text-center text-xs text-gray-500">No data</td></tr>';
                screenTbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No data available</td></tr>';
                return;
            }

            customers.forEach((customer, index) => {
                // Print table row
                const printRow = document.createElement('tr');
                printRow.innerHTML = `
                                                                                                                        <td class="border border-gray-300 px-2 py-1 text-xs">${index + 1}</td>
                                                                                                                        <td class="border border-gray-300 px-2 py-1 text-xs">${customer.customer_name}</td>
                                                                                                                        <td class="border border-gray-300 px-2 py-1 text-right text-xs">${customer.transaction_count}</td>
                                                                                                                        <td class="border border-gray-300 px-2 py-1 text-right text-xs">₱${parseFloat(customer.total_spent).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                                                                                                    `;
                printTbody.appendChild(printRow);

                // Screen table row
                const screenRow = document.createElement('tr');
                screenRow.className = 'hover:bg-gray-50 transition duration-150';
                screenRow.innerHTML = `
                                                                                                                        <td class="px-4 py-3 text-sm text-gray-900">${index + 1}</td>
                                                                                                                        <td class="px-4 py-3 text-sm text-gray-900">${customer.customer_name}</td>
                                                                                                                        <td class="px-4 py-3 text-sm text-gray-900 text-right font-semibold">${customer.transaction_count}</td>
                                                                                                                        <td class="px-4 py-3 text-sm text-gray-900 text-right">₱${parseFloat(customer.total_spent).toLocaleString('en-US', { minimumFractionDigits: 2 })}</td>
                                                                                                                    `;
                screenTbody.appendChild(screenRow);
            });
        }
    </script>

    {{-- Print Styles --}}
    <style>
        @media print {

            /* Set 0.8cm margins */
            @page {
                margin: 0.8cm !important;
                size: auto;
            }

            /* Hide everything except printable content */
            body * {
                visibility: hidden;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Hide navigation, buttons, and filters */
            nav,
            button,
            form,
            .no-print,
            .bg-gray-50.border.border-gray-200.rounded-lg.p-4.mb-6,
            .border-t.border-gray-200.my-6,
            #no-data-row svg {
                display: none !important;
            }

            /* Show only the printable content */
            #printable-report,
            #printable-report * {
                visibility: visible;
                margin: 0 !important;
                padding: 0 !important;
            }

            #printable-report {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }

            /* Print header */
            .print-header {
                display: block !important;
                padding: 10px 0 !important;
                border-bottom: 2px solid #1D4ED8 !important;
                margin-bottom: 15px !important;
                page-break-after: avoid;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-header img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin-bottom: 5px !important;
            }

            .print-header h1 {
                font-size: 18px !important;
                font-weight: bold;
                color: #1D4ED8 !important;
                margin-bottom: 3px !important;
            }

            .print-header h2 {
                font-size: 16px !important;
                font-weight: bold;
                color: #1D4ED8 !important;
                margin-bottom: 5px !important;
            }

            .print-header p {
                font-size: 10px !important;
                color: #374151 !important;
                margin-bottom: 1px !important;
            }

            .print-date-range {
                font-size: 11px !important;
                color: #6B7280 !important;
                margin-top: 5px !important;
            }

            /* Print summary cards */
            .print-summary {
                display: block !important;
                margin-bottom: 12px !important;
                page-break-inside: avoid;
            }

            /* Print receipt section */
            .print-receipt-section {
                display: block !important;
                margin-bottom: 12px !important;
                page-break-inside: avoid;
            }

            .print-section-title {
                font-size: 11px !important;
                color: #1D4ED8 !important;
                margin-bottom: 6px !important;
                font-weight: bold;
                text-align: center;
            }

            .print-receipt-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8px !important;
            }

            .print-receipt-table th {
                background-color: #F3F4F6 !important;
                padding: 4px 3px !important;
                border: 1px solid #D1D5DB !important;
                font-weight: 600;
                font-size: 8px !important;
                text-align: left;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-receipt-table td {
                padding: 3px !important;
                border: 1px solid #E5E7EB !important;
                font-size: 8px !important;
            }

            .print-receipt-table td.text-center {
                text-align: center !important;
            }

            .print-receipt-table td.text-right {
                text-align: right !important;
            }

            /* Print top sections (products and customers) */
            .print-top-sections {
                display: block !important;
                margin-bottom: 12px !important;
                page-break-inside: avoid;
            }

            .print-top-sections h3 {
                font-size: 11px !important;
                color: #1D4ED8 !important;
                margin-bottom: 5px !important;
                font-weight: bold;
            }

            .print-top-sections table {
                width: 100%;
                border-collapse: collapse;
                font-size: 9px !important;
            }

            .print-top-sections th {
                background-color: #F3F4F6 !important;
                padding: 4px 6px !important;
                border: 1px solid #D1D5DB !important;
                font-weight: 600;
                font-size: 8px !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-top-sections td {
                padding: 3px 6px !important;
                border: 1px solid #E5E7EB !important;
                font-size: 8px !important;
            }

            .print-summary-card {
                padding: 8px 10px !important;
                border: 1px solid #D1D5DB !important;
                border-radius: 4px;
                text-align: center;
                background: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-summary-card h4 {
                font-size: 9px !important;
                color: #6B7280 !important;
                margin-bottom: 4px !important;
                font-weight: 600;
            }

            .print-summary-card p {
                font-size: 13px !important;
                font-weight: bold;
                color: #1F2937 !important;
                margin: 0 !important;
            }

            /* Table styling for print */
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10px !important;
                margin: 0 !important;
            }

            thead {
                background-color: #F3F4F6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            th {
                padding: 6px 4px !important;
                border: 1px solid #D1D5DB !important;
                font-weight: 600;
                font-size: 9px !important;
                text-align: left;
            }

            td {
                padding: 5px 4px !important;
                border: 1px solid #E5E7EB !important;
                font-size: 9px !important;
            }

            /* Center align for qty column */
            td:nth-child(3) {
                text-align: center !important;
            }

            /* Right align for money columns */
            td:nth-child(4),
            td:nth-child(5),
            td:nth-child(6) {
                text-align: right !important;
            }

            /* Prepared by section */
            .print-prepared-by {
                display: block !important;
                margin-top: 40px !important;
                text-align: right;
                page-break-inside: avoid;
            }

            .print-prepared-by p {
                font-size: 11px !important;
                color: #374151 !important;
            }

            /* Print footer - centered */
            .print-footer {
                display: block !important;
                position: fixed;
                bottom: 0.8cm;
                left: 0.8cm;
                right: 0.8cm;
                text-align: center;
                padding: 8px 0 !important;
                border-top: 1px solid #E5E7EB !important;
                font-size: 9px !important;
                color: #6B7280 !important;
                background: white !important;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-footer p {
                margin: 0 !important;
                font-size: 9px !important;
                line-height: 1.2;
            }

            /* Page breaks */
            tr {
                page-break-inside: avoid;
            }

            /* Grid layout for summary */
            .grid {
                display: grid !important;
            }
        }

        /* Hide print elements on screen */
        .print-header,
        .print-footer,
        .print-summary,
        .print-receipt-section,
        .print-top-sections,
        .print-prepared-by {
            display: none;
        }
    </style>

@endsection