{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow-lg border overflow-x-auto mt-4">
    <table class="w-full text-left">
        <thead class="bg-gray-100 text-gray-700 text-base">
            <tr>
                <th class="p-4 font-semibold">Product</th>
                <th class="p-4 font-semibold">Serial Number</th>
                <th class="p-4 font-semibold">Warranty</th>
                <th class="p-4 font-semibold">Brand</th>
                <th class="p-4 font-semibold">Categories</th>
                <th class="p-4 font-semibold">Date Added</th>
                <th class="p-4">Actions</th>
            </tr>
        </thead>

        <tbody class="text-base">
            @forelse($products as $product)
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="p-4 font-semibold">
                        <div class="flex items-center space-x-3">
                            <span>{{ $product->product_name }}</span>
                        </div>
                    </td>
                    <td class="p-4 font-mono text-sm">{{ $product->serial_number }}</td>
                    <td class="p-4">
                        <span class="text-green-800 text-xs px-2 py-1 font-bold rounded-full">
                            {{ $product->warranty_period }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="text-blue-800 text-xs font-bold px-2 py-1 rounded-full">
                            {{ $product->brand?->brand_name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="text-purple-800 text-xs font-bold px-2 py-1 rounded-full">
                            {{ $product->category?->category_name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="p-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($product->created_at)->format('M d, Y') }}
                    </td>
                    <td class="p-4">
                        <div class="flex space-x-2">
                            <!-- Edit Icon -->
                            <a href=""
                                class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-1 rounded hover:bg-blue-50"
                                title="Edit Product">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="border-t">
                    <td colspan="7" class="p-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-lg font-medium">No products found</p>
                            <p class="text-sm">Add your first product to get started</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>