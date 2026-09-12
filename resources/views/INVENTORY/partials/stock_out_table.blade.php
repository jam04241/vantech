{{-- Stock-Out Table Partial --}}
<div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-300 bg-gray-50">
                <th class="px-4 py-3 text-left font-semibold text-gray-700">#</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Product</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Serial Number</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Receipt No</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Warranty</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Brand</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Category</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-700">Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-gray-600 font-medium text-center">
                        {{ $products->firstItem() + $loop->iteration - 1 }}</td>
                    <td class="px-4 py-3 text-gray-800 font-medium">
                        {{ $product->product_name }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $product->serial_number ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $product->customerPurchaseOrders->first()?->drTransaction?->receipt_no ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $product->warranty_label }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $product->brand?->brand_name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600">
                        {{ $product->category?->category_name ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                        {{ $product->created_at->format('M. d Y h:i:s A')  }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p>No stock-out records found</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
@if($products->hasPages())
    <div class="px-4 py-4 border-t border-gray-200 flex items-center justify-between">
        <div class="text-sm text-gray-600">
            Showing <span class="font-semibold">{{ $products->firstItem() }}</span> to
            <span class="font-semibold">{{ $products->lastItem() }}</span> of
            <span class="font-semibold">{{ $products->total() }}</span> results
        </div>

        <div class="flex gap-2">
            @if($products->onFirstPage())
                <button disabled class="px-3 py-1 text-gray-400 bg-gray-100 rounded text-sm cursor-not-allowed">
                    ← Previous
                </button>
            @else
                <a href="{{ $products->previousPageUrl() }}" hx-get="{{ $products->previousPageUrl() }}"
                    hx-target="#stockout-table" hx-swap="innerHTML"
                    class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 transition">
                    ← Previous
                </a>
            @endif

            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                    <span class="px-3 py-1 bg-indigo-600 text-white rounded text-sm font-semibold">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" hx-get="{{ $url }}" hx-target="#stockout-table" hx-swap="innerHTML"
                        class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 transition">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}" hx-get="{{ $products->nextPageUrl() }}" hx-target="#stockout-table"
                    hx-swap="innerHTML"
                    class="px-3 py-1 text-gray-700 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50 transition">
                    Next →
                </a>
            @else
                <button disabled class="px-3 py-1 text-gray-400 bg-gray-100 rounded text-sm cursor-not-allowed">
                    Next →
                </button>
            @endif
        </div>
    </div>
@endif