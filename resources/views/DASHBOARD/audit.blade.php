@extends('SIDEBAR.layouts')
@section('title', 'Audit Logs')
@section('name', 'Audit Logs')

@section('content')
    <div class="bg-white border rounded-lg p-6 shadow-sm">
        {{-- Header Section with Search and Filter --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('audit.logs') }}" class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" id="search-input" name="search" value="{{ $search ?? '' }}"
                        placeholder="Search by user, action, module..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                        aria-label="Search audit logs" hx-get="{{ route('audit.logs') }}"
                        hx-trigger="input changed delay:500ms, search" hx-target="#logs-table" hx-swap="outerHTML"
                        hx-include="select[name='module'], select[name='action'], select[name='sortOrder'], input[name='date_from'], input[name='date_to']"
                        hx-indicator="#search-loading">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <!-- Loading indicator -->
                    <div id="search-loading" class="htmx-indicator absolute inset-y-0 right-8 pr-3 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                    @if($search)
                        <a href="{{ route('audit.logs') }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200"
                            title="Clear search">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="border-t border-gray-200 my-6"></div>

        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Audit Logs</h2>
                <p class="text-gray-600 mt-1">Track and view system activities</p>
            </div>
        </div>

        {{-- Filter Card --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                {{-- Filter by Module --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Module</label>
                    <select name="module" hx-get="{{ route('audit.logs') }}" hx-trigger="change" hx-target="#logs-table"
                        hx-swap="outerHTML"
                        hx-include="input[name='search'], select[name='action'], select[name='sortOrder'], input[name='date_from'], input[name='date_to']"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="">All Modules</option>
                        <option value="Authentication" @selected(($module ?? '') === 'Authentication')>Authentication</option>
                        <option value="POS" @selected(($module ?? '') === 'POS')>POS</option>
                        <option value="Inventory" @selected(($module ?? '') === 'Inventory')>Inventory</option>
                        <option value="Services" @selected(($module ?? '') === 'Services')>Services</option>
                        <option value="Customer" @selected(($module ?? '') === 'Customer')>Customer</option>
                        <option value="Supplier" @selected(($module ?? '') === 'Supplier')>Supplier</option>
                        <option value="Staff" @selected(($module ?? '') === 'Staff')>Staff</option>
                        <option value="Admin" @selected(($module ?? '') === 'Admin')>Admin</option>
                    </select>
                </div>

                {{-- Filter by Action --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Action</label>
                    <select name="action" hx-get="{{ route('audit.logs') }}" hx-trigger="change" hx-target="#logs-table"
                        hx-swap="outerHTML"
                        hx-include="input[name='search'], select[name='module'], select[name='sortOrder'], input[name='date_from'], input[name='date_to']"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="">All Actions</option>
                        <option value="CREATE" @selected(($action ?? '') === 'CREATE')>Create</option>
                        <option value="UPDATE" @selected(($action ?? '') === 'UPDATE')>Update</option>
                        <option value="PURCHASE" @selected(($action ?? '') === 'PURCHASE')>Purchase</option>
                        <option value="COMPLETED SERVICE" @selected(($action ?? '') === 'COMPLETED SERVICE')>Completed Service
                        </option>
                        <option value="ACKNOWLEDGE" @selected(($action ?? '') === 'ACKNOWLEDGE')>Acknowledge</option>
                        <option value="LOGIN" @selected(($action ?? '') === 'LOGIN')>Login</option>
                        <option value="LOGOUT" @selected(($action ?? '') === 'LOGOUT')>Logout</option>
                        <option value="VIEW" @selected(($action ?? '') === 'VIEW')>View</option>
                    </select>
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Order</label>
                    <select name="sortOrder" hx-get="{{ route('audit.logs') }}" hx-trigger="change" hx-target="#logs-table"
                        hx-swap="outerHTML"
                        hx-include="input[name='search'], select[name='module'], select[name='action'], input[name='date_from'], input[name='date_to']"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="desc" @selected(($sortOrder ?? 'desc') === 'desc')>Newest First</option>
                        <option value="asc" @selected(($sortOrder ?? 'desc') === 'asc')>Oldest First</option>
                    </select>
                </div>

                {{-- Date Range --}}
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-2">Date Range</label>
                    <div class="flex gap-2">
                        <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}" hx-get="{{ route('audit.logs') }}"
                            hx-trigger="change" hx-target="#logs-table" hx-swap="outerHTML"
                            hx-include="input[name='search'], select[name='module'], select[name='action'], select[name='sortOrder'], input[name='date_to']"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white"
                            title="From Date">
                        <input type="date" name="date_to" value="{{ $dateTo ?? '' }}" hx-get="{{ route('audit.logs') }}"
                            hx-trigger="change" hx-target="#logs-table" hx-swap="outerHTML"
                            hx-include="input[name='search'], select[name='module'], select[name='action'], select[name='sortOrder'], input[name='date_from']"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white"
                            title="To Date">
                    </div>
                </div>
            </div>
        </div>

        {{-- Audit Logs Table in Card Container --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div id="logs-table"
                hx-include="[id='search-input'], [name='module'], [name='action'], [name='sortOrder'], [name='date_from'], [name='date_to']">
                @include('DASHBOARD.audit-table', compact('auditLogs', 'search', 'module', 'action', 'sortOrder', 'dateFrom', 'dateTo'))
            </div>
        </div>
    </div>

    <script>
        // Add search functionality with debounce
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                // HTMX will handle the debounce via hx-trigger
            });
        }
    </script>
@endsection