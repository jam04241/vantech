<!-- Audit Logs Table Partial -->
<div id="logs-table" class="space-y-4">
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead class="bg-gray-100 sticky top-0">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">User</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Module</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Action</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Description</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Date & Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($auditLogs as $log)
                    <tr class="hover:bg-gray-50 transition">
                        <!-- User Column -->
                        <td class="px-4 py-3">
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-semibold text-blue-600">
                                        {{ strtoupper(substr($log->user->first_name, 0, 1)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium">
                                    {{ $log->user->first_name }} {{ $log->user->last_name }}
                                </span>
                            </div>
                        </td>

                        <!-- Module Column -->
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                {{ $log->module }}
                            </span>
                        </td>

                        <!-- Action Column -->
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    @if($log->action === 'CREATE') bg-green-100 text-green-800
                                                    @elseif($log->action === 'UPDATE') bg-yellow-100 text-yellow-800
                                                    @elseif($log->action === 'DELETE') bg-red-100 text-red-800
                                                    @elseif($log->action === 'LOGIN') bg-green-100 text-green-800
                                                    @elseif($log->action === 'LOGOUT') bg-orange-100 text-orange-800
                                                    @elseif($log->action === 'PURCHASE') bg-purple-100 text-purple-800
                                                    @elseif($log->action === 'ACKNOWLEDGE') bg-purple-100 text-purple-800
                                                    @elseif($log->action === 'COMPLETED SERVICE') bg-purple-100 text-purple-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                {{ $log->action }}
                            </span>
                        </td>

                        <!-- Description Column -->
                        <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate" title="{{ $log->description }}">
                            {{ $log->description }}
                        </td>

                        <!-- Date & Time Column -->
                        <td class="px-4 py-3 text-sm text-gray-500">
                            <div>{{ $log->created_at->format('Y-m-d') }}</div>
                            <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            No audit logs found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-6 p-6 border-t border-gray-200">
        <div class="text-sm text-gray-600">
            Showing {{ $auditLogs->firstItem() ?? 0 }} to {{ $auditLogs->lastItem() ?? 0 }}
            of {{ $auditLogs->total() }} results
        </div>
        <div class="space-x-2">
            @if ($auditLogs->onFirstPage())
                <button class="px-4 py-2 bg-gray-100 text-gray-400 rounded cursor-not-allowed" disabled>
                    ← Previous
                </button>
            @else
                <a href="{{ $auditLogs->previousPageUrl() }}&search={{ request('search', '') }}&module={{ request('module', '') }}&action={{ request('action', '') }}&sortBy={{ request('sortBy', 'created_at') }}&sortOrder={{ request('sortOrder', 'desc') }}"
                    hx-get="{{ $auditLogs->previousPageUrl() }}&search={{ request('search', '') }}&module={{ request('module', '') }}&action={{ request('action', '') }}&sortBy={{ request('sortBy', 'created_at') }}&sortOrder={{ request('sortOrder', 'desc') }}"
                    hx-target="#logs-table" hx-swap="outerHTML"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                    ← Previous
                </a>
            @endif

            @if ($auditLogs->hasMorePages())
                <a href="{{ $auditLogs->nextPageUrl() }}&search={{ request('search', '') }}&module={{ request('module', '') }}&action={{ request('action', '') }}&sortBy={{ request('sortBy', 'created_at') }}&sortOrder={{ request('sortOrder', 'desc') }}"
                    hx-get="{{ $auditLogs->nextPageUrl() }}&search={{ request('search', '') }}&module={{ request('module', '') }}&action={{ request('action', '') }}&sortBy={{ request('sortBy', 'created_at') }}&sortOrder={{ request('sortOrder', 'desc') }}"
                    hx-target="#logs-table" hx-swap="outerHTML"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                    Next →
                </a>
            @else
                <button class="px-4 py-2 bg-gray-100 text-gray-400 rounded cursor-not-allowed" disabled>
                    Next →
                </button>
            @endif
        </div>
    </div>
</div>