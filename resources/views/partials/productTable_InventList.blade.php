{{-- MINIMALIST TABLE --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto mt-4">
    <table class="min-w-full border-collapse">
        <thead>
            <tr class="border-b border-gray-200 bg-white">
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Brand</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions
                </th>
            </tr>
        </thead>

        <tbody class="text-sm text-gray-700">
            @forelse($products as $product)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $product->product_name }}
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700 font-medium">
                            {{ $product->brand?->brand_name ?? 'N/A' }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        <span class="text-xs px-2 py-1 rounded-full bg-purple-50 text-purple-700 font-medium">
                            {{ $product->category?->category_name ?? 'N/A' }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-gray-600">
                        {{ $product->quantity ?? 'N/A' }}
                    </td>

                    <td class="px-4 py-3 text-gray-800">
                        ₱{{ number_format($product->price ?? 0, 2) }}
                    </td>

                    <td class="px-4 py-3">
                        <button
                            class="text-gray-600 hover:text-gray-900 hover:bg-gray-100 px-3 py-1 rounded-md transition text-xs font-medium">
                            View
                        </button>
                    </td>
                </tr>
            @empty
                <tr class="border-b border-gray-200">
                    <td colspan="6" class="px-4 py-10 text-center text-gray-500">
                        No products available.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>