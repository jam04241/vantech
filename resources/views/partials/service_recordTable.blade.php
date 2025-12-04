{{-- Service Records Table with Pagination --}}
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-700 text-sm">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-700">Full Name</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Service</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Fee</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Item</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Date Received</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Date Completed</th>
                    <th class="px-6 py-4 font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($services as $service)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $service->customer ? $service->customer->first_name . ' ' . $service->customer->last_name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $service->serviceType ? $service->serviceType->name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-green-600 font-semibold">
                            ₱{{ number_format($service->total_price ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $service->type ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $service->date_in ? \Carbon\Carbon::parse($service->date_in)->format('M d, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $service->date_out ? \Carbon\Carbon::parse($service->date_out)->format('M d, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'In Progress' => 'bg-blue-100 text-blue-800',
                                    'On Hold' => 'bg-orange-100 text-orange-800',
                                    'Completed' => 'bg-green-100 text-green-800',
                                ];
                                $statusIcons = [
                                    'Pending' => 'fas fa-clock',
                                    'In Progress' => 'fas fa-hourglass-half',
                                    'On Hold' => 'fas fa-pause',
                                    'Completed' => 'fas fa-check-circle',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$service->status] ?? 'bg-gray-100 text-gray-800' }}">
                                <i class="{{ $statusIcons[$service->status] ?? 'fas fa-info-circle' }} mr-1"></i>
                                {{ $service->status }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-lg font-medium text-gray-900 mb-2">No service records found</p>
                                <p class="text-sm text-gray-500">Try adjusting your search or filter criteria</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($services->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} results
                </div>
                <div class="flex space-x-2">
                    {{-- Previous Button --}}
                    @if($services->onFirstPage())
                        <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded-lg text-sm cursor-not-allowed">
                            Previous
                        </span>
                    @else
                        <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition"
                                hx-get="{{ $services->previousPageUrl() }}"
                                hx-target="#service-records-table"
                                hx-include="[name='search'], [name='start_date'], [name='end_date'], [name='status'], [name='sort']">
                            Previous
                        </button>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                        @if($page == $services->currentPage())
                            <span class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium">
                                {{ $page }}
                            </span>
                        @else
                            <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition"
                                    hx-get="{{ $url }}"
                                    hx-target="#service-records-table"
                                    hx-include="[name='search'], [name='start_date'], [name='end_date'], [name='status'], [name='sort']">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach

                    {{-- Next Button --}}
                    @if($services->hasMorePages())
                        <button class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition"
                                hx-get="{{ $services->nextPageUrl() }}"
                                hx-target="#service-records-table"
                                hx-include="[name='search'], [name='start_date'], [name='end_date'], [name='status'], [name='sort']">
                            Next
                        </button>
                    @else
                        <span class="px-3 py-2 bg-gray-200 text-gray-400 rounded-lg text-sm cursor-not-allowed">
                            Next
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Include FontAwesome for icons --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
