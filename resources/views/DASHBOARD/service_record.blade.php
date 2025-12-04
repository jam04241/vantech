@extends('SIDEBAR.layouts')

@section('title', 'Service Records')
@section('name', 'Service Records')

@section('content')
    <div class="p-6">
        {{-- Page Title and Description --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Service Records</h1>
            <p class="text-gray-600">Manage and track all service requests and repair orders with real-time updates</p>
        </div>

        {{-- Statistics Cards (3x2 grid for 6 cards) --}}
        <div id="statistics-cards" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6"
            hx-get="{{ route('services.getserviceRecords') }}?target=stats"
            hx-trigger="load, refreshStats from:body, refreshServiceRecords from:body"
            hx-include="[name='start_date'], [name='end_date']" hx-target="#statistics-cards" hx-swap="innerHTML">
            {{-- Loading cards --}}
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Services</p>
                        <p class="text-2xl font-bold text-gray-900">...</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-5 border border-yellow-200 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">...</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">...</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">In Progress</p>
                        <p class="text-2xl font-bold text-gray-900">...</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-5 border border-orange-200 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-orange-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">On Hold</p>
                        <p class="text-2xl font-bold text-gray-900">...</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-5 border border-indigo-200 shadow-sm">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-lg font-bold text-gray-900">...</p>
                        <p class="text-xs text-gray-500">Based on Date Range</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filter Section --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                {{-- Search Bar --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <input type="text" id="search-input" name="search" placeholder="Search all content..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            hx-get="{{ route('services.getserviceRecords') }}"
                            hx-trigger="input changed delay:500ms, search" hx-target="#service-records-table"
                            hx-include="[name='start_date'], [name='end_date'], [name='status'], [name='sort']"
                            hx-indicator="#search-loading">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <div id="search-loading" class="htmx-indicator absolute right-3 top-2.5">
                            <svg class="animate-spin h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                                </circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                    <select name="status" id="status-filter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        hx-get="{{ route('services.getserviceRecords') }}" hx-trigger="change"
                        hx-target="#service-records-table"
                        hx-include="[name='search'], [name='start_date'], [name='end_date'], [name='sort']">
                        <option value="all">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

                {{-- Date Range --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" name="start_date" id="start-date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        hx-get="{{ route('services.getserviceRecords') }}" hx-trigger="change"
                        hx-target="#service-records-table"
                        hx-include="[name='search'], [name='end_date'], [name='status'], [name='sort']" hx-swap="innerHTML"
                        hx-on="htmx:afterRequest: document.body.dispatchEvent(new CustomEvent('refreshStats'))">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="date" name="end_date" id="end-date"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        hx-get="{{ route('services.getserviceRecords') }}" hx-trigger="change"
                        hx-target="#service-records-table"
                        hx-include="[name='search'], [name='start_date'], [name='status'], [name='sort']"
                        hx-swap="innerHTML"
                        hx-on="htmx:afterRequest: document.body.dispatchEvent(new CustomEvent('refreshStats'))">
                </div>

                {{-- Sort Order --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort by Date</label>
                    <select name="sort" id="sort-order"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        hx-get="{{ route('services.getserviceRecords') }}" hx-trigger="change"
                        hx-target="#service-records-table"
                        hx-include="[name='search'], [name='start_date'], [name='end_date'], [name='status']">
                        <option value="newest">Newest First</option>
                        <option value="oldest">Oldest First</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Service Records Table --}}
        <div id="service-records-table" hx-get="{{ route('services.getserviceRecords') }}"
            hx-trigger="load, refreshServiceRecords from:body" hx-swap="innerHTML">
            {{-- Loading State --}}
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-12">
                <div class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-gray-600">Loading service records...</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Include HTMX --}}
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <script>
        // Enable debugging for real-time updates
        const DEBUG_REALTIME = true;

        function debugLog(message, data = null) {
            if (DEBUG_REALTIME) {
                console.log(`🔄 [Service Records] ${message}`, data || '');
            }
        }

        // Listen for filter changes to refresh stats
        document.addEventListener('htmx:afterRequest', function (evt) {
            if (evt.detail.target.id === 'service-records-table') {
                debugLog('Service records table updated via HTMX - triggering stats refresh');
                document.body.dispatchEvent(new CustomEvent('refreshStats'));
            }
        });

        // Listen for service updates from CardServices component
        document.addEventListener('refreshServices', function (evt) {
            debugLog('Received refreshServices event from CardServices - updating table and stats');
            // Refresh the service records table
            document.body.dispatchEvent(new CustomEvent('refreshServiceRecords'));
            // Refresh the statistics cards  
            document.body.dispatchEvent(new CustomEvent('refreshStats'));
        });

        // Listen for service record updates and propagate to other components
        document.addEventListener('htmx:afterRequest', function (evt) {
            // If service records table was updated, notify other components
            if (evt.detail.target.id === 'service-records-table' ||
                evt.detail.target.id === 'statistics-cards') {
                debugLog('Service records/stats updated - broadcasting refresh events to other components');
                // Notify CardServices component if it exists on the same page
                document.body.dispatchEvent(new CustomEvent('refreshServices'));
            }
        });

        // Cross-tab/window communication for real-time updates
        if (typeof (Storage) !== "undefined") {
            window.addEventListener('storage', function (e) {
                if (e.key === 'serviceUpdated') {
                    debugLog('Service updated in another tab/window - refreshing data');
                    document.body.dispatchEvent(new CustomEvent('refreshServiceRecords'));
                    document.body.dispatchEvent(new CustomEvent('refreshStats'));
                    // Clear the storage event
                    localStorage.removeItem('serviceUpdated');
                }
            });
        }

        // Global refresh function for manual triggers
        window.refreshServiceRecords = function () {
            debugLog('Manual refresh triggered via window.refreshServiceRecords()');
            document.body.dispatchEvent(new CustomEvent('refreshServiceRecords'));
            document.body.dispatchEvent(new CustomEvent('refreshStats'));
        };

        // Test function to verify real-time updates are working
        window.testRealTimeUpdates = function () {
            debugLog('Testing real-time updates...');
            window.refreshServiceRecords();
            return 'Test completed - check console for debug messages';
        };

        debugLog('Service Records real-time system initialized successfully');
    </script>
@endsection