@extends('SIDEBAR.layouts')
@section('title', 'Inventory Reports')
@section('name', 'INVENTORY REPORTS')
@section('content')

                <div class="bg-white border rounded-lg p-6 shadow-sm">
                    {{-- Header Section --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        {{-- Search Form --}}
                        <form method="GET" action="{{ route('inventory.reports') }}" class="flex-1 max-w-md" id="searchForm">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search products by name, category..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm search-input"
                                    aria-label="Search inventory reports">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                @if(request('search'))
                                    <button type="button" onclick="window.location.href='{{ route('inventory.reports') }}'"
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

                    {{-- Page Title --}}
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Inventory Reports</h2>
                            <p class="text-gray-600 mt-1">View detailed product sales and availability reports</p>
                        </div>
                    </div>

                    {{-- Date Range Filter (Auto-submit) --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                        <form method="GET" action="{{ route('inventory.reports') }}" id="dateFilterForm"
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-2">Category</label>
                                <select name="category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white text-sm date-filter">
                                    <option value="">All Categories</option>
                                    @isset($categories)
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->category_name }}
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>

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

                            <div class="flex gap-2 items-end">
                                <button type="button" onclick="window.location.href='{{ route('inventory.reports') }}'"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-white shadow hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out text-sm font-medium text-gray-700">
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Reports Table --}}
                    <div id="printable-report" class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                        {{-- Print Header (Hidden on screen, visible on print) --}}
                        <div class="print-header">
                            <div class="flex justify-between items-start">
                                <div class="w-3/5">
                                    <h1 class="text-4xl font-bold text-blue-700 mb-2" style="font-size: 16pt;">VANTECH COMPUTERS TRADING
                                    </h1>
                                    <p class="text-sm text-gray-700" style="font-size: 12pt;">Van Bryan C. Bardillas - Sole Proprietor
                                    </p>
                                    <p class="text-sm text-gray-700" style="font-size: 12pt;">Non VAT Reg. TIN 505-374240-00000</p>
                                    <p class="text-sm text-gray-700" style="font-size: 12pt;">758 F Purok 3, Brgy. Mintal</p>
                                    <p class="text-sm text-gray-700" style="font-size: 12pt;">Davao City, Davao del Sur, 8000</p>
                                </div>
                                <div class="w-2/5 flex flex-col items-end">
                                    <img src="{{ asset('images/logo.png') }}" class="w-28 h-auto mb-3" />
                                    <h3 class="text-lg font-bold text-blue-700 text-right mb-4" style="font-size: 14pt;">INVENTORY
                                        REPORT</h3>

                                    {{-- Summary Cards --}}
                                    <div class="print-summary-right">
                                        <div class="print-summary-card-right">
                                            <h3>TOTAL PRODUCTS</h3>
                                            <p>{{ $totalProducts }}</p>
                                        </div>
                                        <div class="print-summary-card-right">
                                            <h3>TOTAL SOLD</h3>
                                            <p>{{ $totalSold }}</p>
                                        </div>
                                        <div class="print-summary-card-right">
                                            <h3>AVAILABLE STOCK</h3>
                                            <p>{{ $totalAvailableStock }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Print Date Range (Hidden on screen, visible on print) --}}
                        <div class="print-date-range">
                            <strong>Report Date:</strong> {{ date('F d, Y') }}
                            @if(request('start_date') && request('end_date'))
                                <br><strong>Period:</strong> {{ date('M d, Y', strtotime(request('start_date'))) }} -
                                {{ date('M d, Y', strtotime(request('end_date'))) }}
                            @endif
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            #
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Product Name
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Condition
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Price
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Sold
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Availability
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($products as $index => $product)
                                        <tr class="hover:bg-gray-50 transition duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <div class="text-sm text-gray-900">
                                                    {{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $product['product_name'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product['product_condition'] == 'Brand New' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} badge">
                                                    {{ $product['product_condition'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 badge">
                                                    {{ $product['category'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                                ₱{{ number_format($product['price'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span
                                                    class="text-sm font-semibold {{ $product['sold'] > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                                    {{ $product['sold'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span
                                                    class="text-sm font-semibold {{ $product['availability'] <= 5 ? 'text-red-600' : 'text-green-600' }}">
                                                    {{ $product['availability'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                    </svg>
                                                    <p class="text-lg font-medium">No products found</p>
                                                    <p class="text-sm">Try adjusting your search or filter criteria</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Total Summary Section (Print only) --}}
                        <div class="print-total-summary">
                            <div class="total-summary-content">
                                <div class="total-row">
                                    <span class="total-label">Total Sold Items:</span>
                                    <span class="total-value">{{ number_format($totalSold) }}</span>
                                </div>
                                <div class="total-row">
                                    <span class="total-label">Total Price (Sum of All Prices):</span>
                                    <span class="total-value">₱{{ number_format($totalPrice, 2) }}</span>
                                </div>
                                <div class="total-row total-revenue">
                                    <span class="total-label">Total Revenue (Price × Sold):</span>
                                    <span class="total-value">₱{{ number_format($totalRevenue, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Prepared By Section (Print only) --}}
                        <div class="print-prepared-by">
                            <div class="text-right">
                                <p class="text-sm py-12 mb-8">Prepared by:</p>
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
                                <p class="text-sm font-semibold mb-1">{{ $fullName }}</p> <!-- Added mb-1 -->
                                <div class="border-t border-gray-400 w-20 mx-auto"></div> <!-- Set specific width -->
                                <p class="text-sm text-gray-600 mt-1">{{ $role }}</p> <!-- Added mt-1 -->
                            </div>
                        </div>

                        {{-- Print Footer --}}
                        <div class="print-footer">
                            <div class="text-center">
                                <p>Generated on {{ date('F d, Y g:i A') }}</p>
                                <p style="margin-top: 3px;">Page <span class="page-counter"></span></p>
                                <p style="margin-top: 3px;">{{ date('Y') }} Vantech Computers. All rights reserved.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Summary Info (Non-print) --}}
                    <div class="bg-white px-6 py-4 border-t border-gray-200 no-print">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> to
                                <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> of
                                <span class="font-medium">{{ $totalProducts }}</span>
                                {{ Str::plural('product', $totalProducts) }}
                                @if($search || $startDate || $endDate)
                                    <span class="text-gray-500">
                                        (filtered
                                        @if($search)
                                            by "{{ $search }}"
                                        @endif
                                        @if($startDate && $endDate)
                                            from {{ date('M d, Y', strtotime($startDate)) }} to {{ date('M d, Y', strtotime($endDate)) }}
                                        @endif
                                        )
                                    </span>
                                @endif
                            </div>
                            <div class="flex gap-4 text-sm">
                                <div class="text-gray-700">
                                    <span class="font-medium text-green-600">{{ $totalSold }}</span> sold
                                </div>
                                <div class="text-gray-700">
                                    <span class="font-medium text-blue-600">{{ $totalAvailableStock }}</span> in stock
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pagination Links (Non-print) --}}
                    @if($products->hasPages())
                        <div class="bg-white px-6 py-4 border-t border-gray-200 no-print">
                            <div class="flex justify-center">
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif

                    {{-- JavaScript for auto-submit date filter, real-time search, and page numbering --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Real-time search with debounce
                            const searchInput = document.querySelector('.search-input');
                            let searchTimeout;

                            if (searchInput) {
                                searchInput.addEventListener('input', function () {
                                    clearTimeout(searchTimeout);
                                    searchTimeout = setTimeout(() => {
                                        document.getElementById('searchForm').submit();
                                    }, 500); // 500ms delay for real-time search
                                });
                            }

                            // Auto-submit date filters
                            const dateFilters = document.querySelectorAll('.date-filter');
                            dateFilters.forEach(filter => {
                                filter.addEventListener('change', function () {
                                    document.getElementById('dateFilterForm').submit();
                                });
                            });
                        });

                        // Page numbering for print
                        window.addEventListener('beforeprint', function () {
                            let pageNum = 1;
                            const pageCounters = document.querySelectorAll('.page-counter');
                            pageCounters.forEach(counter => {
                                counter.textContent = pageNum;
                            });
                        });
                    </script>

    {{-- Print Styles --}}
    <style>
        @media print {

            /* Page setup for A4 (210mm x 297mm) with 1cm margins on top and bottom */
            @page {
                margin: 10mm 10mm 10mm 10mm;
                /* 1cm on all sides */
                size: A4 portrait;
            }

            /* Hide everything by default */
            body * {
                visibility: hidden;
            }

            /* Hide navigation, buttons, and filters */
            nav,
            button,
            form,
            .no-print,
            .bg-gray-50.border.border-gray-200.rounded-lg.p-4.mb-6,
            .border-t.border-gray-200.my-6 {
                display: none !important;
            }

            /* Show only the printable content */
            #printable-report,
            #printable-report * {
                visibility: visible;
            }

            /* Optimize printable area for A4 */
            #printable-report {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                border: none !important;
                box-shadow: none !important;
                background: white;
            }

            /* Print header - adjusted for 1cm top margin */
            .print-header {
                display: block !important;
                padding: 0 0 3mm 0 !important;
                margin-bottom: 3mm !important;
                border-bottom: 2px solid #1D4ED8;
                page-break-after: avoid;
            }

            .print-header h1 {
                font-size: 14pt !important;
                font-weight: bold;
                color: #1D4ED8;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin-bottom: 2mm !important;
                margin-top: 0 !important;
            }

            .print-header h3 {
                font-size: 12pt !important;
                font-weight: bold;
                color: #1D4ED8;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin-bottom: 2mm !important;
            }

            .print-header p {
                font-size: 9pt !important;
                color: #374151;
                margin-bottom: 1mm !important;
                line-height: 1.3;
            }

            .print-header img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin-bottom: 2mm !important;
                max-height: 45px;
            }

            /* Summary cards on right side */
            .print-summary-right {
                display: flex !important;
                flex-direction: column;
                gap: 2mm;
                width: 100%;
                margin-top: 2mm !important;
            }

            .print-summary-card-right {
                padding: 2mm 3mm !important;
                border: 1px solid #E5E7EB !important;
                border-radius: 2px;
                text-align: center;
                background: #f8fafc;
                page-break-inside: avoid;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .print-summary-card-right h3 {
                font-size: 7pt !important;
                color: #6B7280;
                margin-bottom: 1mm !important;
                font-weight: 600;
            }

            .print-summary-card-right p {
                font-size: 10pt !important;
                font-weight: bold;
                color: #1F2937;
                margin: 0 !important;
            }

            /* Print footer - adjusted for 1cm bottom margin */
            .print-footer {
                display: block !important;
                position: fixed;
                bottom: 10mm;
                /* Adjusted for 1cm bottom margin */
                left: 10mm;
                right: 10mm;
                text-align: center;
                padding: 2mm 0 !important;
                border-top: 1px solid #E5E7EB;
                font-size: 7pt;
                color: #6B7280;
                background: white;
                page-break-inside: avoid;
                z-index: 100;
            }

            .print-footer p {
                margin: 0 !important;
                font-size: 7pt !important;
                line-height: 1.3;
            }

            /* Prepared by section - positioned higher due to 1cm bottom margin */
            .print-prepared-by {
                display: block !important;
                position: fixed;
                bottom: 30mm;
                /* Moved up to avoid overlap with total summary */
                right: 15mm;
                text-align: right;
                page-break-inside: avoid;
                z-index: 50;
            }

            .print-prepared-by p {
                font-size: 9pt !important;
                color: #374151 !important;
                margin: 0 !important;
                line-height: 1.3;
            }

            .print-prepared-by .font-semibold {
                font-weight: 600;
            }

            .print-prepared-by .border-t {
                border-top: 1px solid #374151 !important;
                display: inline-block;
                width: 35mm;
                margin: 0 !important;
            }

            .print-prepared-by .text-gray-600 {
                font-size: 8pt !important;
            }

            /* Date range info */
            .print-date-range {
                display: block !important;
                text-align: right;
                font-size: 8pt;
                color: #6B7280;
                margin-bottom: 3mm !important;
                padding: 0 !important;
            }

            /* Total Summary Section */
            .print-total-summary {
                display: block !important;
                margin: 5mm 0 !important;
                padding: 3mm 5mm !important;
                border-top: 2px solid #1D4ED8;
                page-break-inside: avoid;
            }

            .total-summary-content {
                display: flex;
                flex-direction: column;
                gap: 2mm;
                max-width: 60mm;
                margin-right: auto;
            }

            .total-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1mm 0;
                font-size: 9pt;
            }

            .total-row.total-revenue {
                border-top: 1px solid #374151;
                padding-top: 2mm;
                margin-top: 1mm;
                font-weight: bold;
                font-size: 10pt;
            }

            .total-label {
                color: #374151;
                font-weight: 500;
            }

            .total-value {
                color: #1F2937;
                font-weight: 600;
                text-align: right;
                margin-left: 5mm;
            }

            .total-revenue .total-value {
                color: #1D4ED8;
                font-weight: bold;
            }

            /* Table styling for print - optimized for A4 */
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8pt !important;
                margin: 0 !important;
                table-layout: fixed;
                margin-bottom: 0 !important;
            }

            /* Fix product name overflow */
            table td:first-child {
                max-width: 45mm;
                word-wrap: break-word;
                overflow-wrap: break-word;
                white-space: normal !important;
            }

            thead {
                background-color: #F3F4F6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            th {
                padding: 4px 3px !important;
                border: 1px solid #D1D5DB;
                font-weight: 600;
                font-size: 8pt !important;
                word-wrap: break-word;
            }

            td {
                padding: 3px 3px !important;
                border: 1px solid #E5E7EB;
                font-size: 9pt !important;
                word-wrap: break-word;
                vertical-align: top;
            }

            /* Column widths for A4 fit with sequence number */
            /* # (Sequence) */
            td:nth-child(1),
            th:nth-child(1) {
                width: 5%;
                text-align: center;
            }

            /* Product Name */
            td:nth-child(2),
            th:nth-child(2) {
                width: 28%;
                max-width: 40mm;
                word-wrap: break-word;
                overflow-wrap: break-word;
                white-space: normal !important;
            }

            /* Condition */
            td:nth-child(3),
            th:nth-child(3) {
                width: 12%;
            }

            /* Category */
            td:nth-child(4),
            th:nth-child(4) {
                width: 12%;
            }

            /* Price */
            td:nth-child(5),
            th:nth-child(5) {
                width: 13%;
            }

            /* Sold */
            td:nth-child(6),
            th:nth-child(6) {
                width: 10%;
                text-align: center;
            }

            /* Availability */
            td:nth-child(7),
            th:nth-child(7) {
                width: 10%;
                text-align: center;
            }

            /* Badge colors */
            .badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                padding: 1px 4px !important;
                font-size: 7pt !important;
                white-space: nowrap;
            }

            /* Ensure table fits within page */
            .overflow-x-auto {
                overflow: visible !important;
                width: 100% !important;
                margin-bottom: 20mm !important;
                /* Ensure space for prepared by section */
            }

            /* Page breaks for better printing */
            tbody tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            /* Remove any background colors that might waste ink */
            .bg-white {
                background: white !important;
            }

            /* Page number counter */
            .page-number:after {
                content: counter(page);
            }

            .total-pages:after {
                content: counter(pages);
            }

            /* Adjust spacing for A4 with 1cm margins */
            body {
                width: 21cm;
                height: 29.7cm;
                font-family: Arial, sans-serif;
                margin: 0 !important;
            }
        }

        /* Hide print elements on screen */
        .print-header,
        .print-footer,
        .print-summary-right,
        .print-date-range,
        .print-prepared-by,
        .print-total-summary {
            display: none;
        }
    </style>

@endsection