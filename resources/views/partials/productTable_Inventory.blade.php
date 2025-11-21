{{-- TABLE --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-x-auto mt-4">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-200 bg-white">
                <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Serial Number</th>
                <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Warranty</th>
                <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Condition</th>
                <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Brand</th>
                <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Category</th>
                <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Date Added</th>
                <th class="p-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>

        <tbody class="text-sm text-gray-700">
            @forelse($products as $product)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="p-4 font-medium text-gray-800">
                            {{ $product->product_name }}
                        </td>

                        <td class="p-4 font-mono text-xs text-gray-600">
                            {{ $product->serial_number }}
                        </td>

                        <td class="p-4">
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700 font-medium">
                                {{ $product->warranty_period }}
                            </span>
                        </td>

                        <td class="p-4">
                            <span class="text-xs px-2 py-1 rounded-full font-medium
                                    {{ $product->product_condition === 'Second Hand'
                ? 'bg-yellow-50 text-yellow-700'
                : 'bg-green-50 text-green-700' }}">
                                {{ $product->product_condition }}
                            </span>
                        </td>

                        <td class="p-4">
                            <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-700 font-medium">
                                {{ $product->brand?->brand_name ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="p-4">
                            <span class="text-xs px-2 py-1 rounded-full bg-purple-50 text-purple-700 font-medium">
                                {{ $product->category?->category_name ?? 'N/A' }}
                            </span>
                        </td>

                        <td class="p-4">
                            <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-700 font-medium">
                                {{ $product->supplier
                ? $product->supplier->supplier_name . ' - ' . $product->supplier->company_name
                : 'N/A' }}
                            </span>
                        </td>

                        <td class="p-4 text-gray-500">
                            {{ \Carbon\Carbon::parse($product->created_at)->format('M d, Y') }}
                        </td>

                        <td class="p-4">
                            <a href="" class="text-gray-500 hover:text-gray-800 transition p-1 rounded hover:bg-gray-100"
                                title="Edit Product">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
            @empty
                <tr class="border-b border-gray-200">
                    <td colspan="9" class="p-10 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            <p class="text-base font-medium">No products found</p>
                            <p class="text-sm text-gray-400">Add your first product to get started</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>