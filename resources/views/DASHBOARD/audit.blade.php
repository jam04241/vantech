@extends('SIDEBAR.layouts')
@section('title', 'Audit Logs')
@section('name', 'Audit Logs')

@section('content')
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Filters Section -->
        <div class="mb-6 space-y-4">
            <!-- Search Bar -->
            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="text-gray-600 font-medium mb-2 block">Search:</label>
                    <input type="text" id="search-input" name="search"
                        placeholder="Search by user name, action, module, description, or date..."
                        hx-get="{{ route('audit.logs') }}" hx-target="#logs-table" hx-swap="outerHTML"
                        hx-trigger="input changed delay:500ms"
                        hx-include="[name='module'], [name='action'], [name='sortBy'], [name='sortOrder']"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ $search ?? '' }}">
                </div>
            </div>

            <!-- Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Filter by Module -->
                <div>
                    <label class="text-gray-600 font-medium mb-2 block">Module:</label>
                    <select name="module" hx-get="{{ route('audit.logs') }}" hx-target="#logs-table" hx-swap="outerHTML"
                        hx-include="[id='search-input'], [name='action'], [name='sortBy'], [name='sortOrder']"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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

                <!-- Filter by Action -->
                <div>
                    <label class="text-gray-600 font-medium mb-2 block">Action:</label>
                    <select name="action" hx-get="{{ route('audit.logs') }}" hx-target="#logs-table" hx-swap="outerHTML"
                        hx-include="[id='search-input'], [name='module'], [name='sortBy'], [name='sortOrder']"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Actions</option>
                        <option value="CREATE" @selected(($action ?? '') === 'CREATE')>Create</option>
                        <option value="UPDATE" @selected(($action ?? '') === 'UPDATE')>Update</option>
                        <option value="DELETE" @selected(($action ?? '') === 'DELETE')>Delete</option>
                        <option value="LOGIN" @selected(($action ?? '') === 'LOGIN')>Login</option>
                        <option value="LOGOUT" @selected(($action ?? '') === 'LOGOUT')>Logout</option>
                        <option value="VIEW" @selected(($action ?? '') === 'VIEW')>View</option>
                    </select>
                </div>

                <!-- Sort By -->
                <div>
                    <label class="text-gray-600 font-medium mb-2 block">Sort By:</label>
                    <select name="sortBy" hx-get="{{ route('audit.logs') }}" hx-target="#logs-table" hx-swap="outerHTML"
                        hx-include="[id='search-input'], [name='module'], [name='action'], [name='sortOrder']"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="created_at" @selected(($sortBy ?? 'created_at') === 'created_at')>Date & Time</option>
                        <option value="action" @selected(($sortBy ?? 'created_at') === 'action')>Action</option>
                        <option value="module" @selected(($sortBy ?? 'created_at') === 'module')>Module</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div>
                    <label class="text-gray-600 font-medium mb-2 block">Order:</label>
                    <select name="sortOrder" hx-get="{{ route('audit.logs') }}" hx-target="#logs-table" hx-swap="outerHTML"
                        hx-include="[id='search-input'], [name='module'], [name='action'], [name='sortBy']"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="desc" @selected(($sortOrder ?? 'desc') === 'desc')>Newest First</option>
                        <option value="asc" @selected(($sortOrder ?? 'desc') === 'asc')>Oldest First</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Logs Table with HTMX -->
        <div id="logs-table"
            hx-include="[id='search-input'], [name='module'], [name='action'], [name='sortBy'], [name='sortOrder']">
            @include('DASHBOARD.audit-table', compact('auditLogs', 'search', 'module', 'action', 'sortBy', 'sortOrder'))
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