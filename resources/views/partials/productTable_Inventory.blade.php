{{-- TABLE --}}
<div class="bg-white rounded-2xl shadow-lg border overflow-x-auto mt-4">
    <table class="w-full text-left">
        <thead class="bg-gray-100 text-gray-700 text-base">
            <tr>
                <th class="p-4 font-semibold">Product</th>
                <th class="p-4 font-semibold">Serial Number</th>
                <th class="p-4 font-semibold">Warranty</th>
                <th class="p-4 font-semibold">Brand</th>
                <th class="p-4 font-semibold">Category</th>
                <th class="p-4 font-semibold" hidden>Price</th>
                <th class="p-4 font-semibold">Condition</th>
                <th class="p-4 font-semibold">Supplier</th>
                <th class="p-4 font-semibold">Added On</th>
                <th class="p-4">Actions</th>
            </tr>
        </thead>

        <tbody class="text-sm text-gray-700">
            @forelse($products as $product)
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="p-4 font-semibold">
                        <div class="flex items-center space-x-3">
                            <span>{{ $product->product_name }}</span>
                        </div>
                    </td>
                    <td class="p-4 font-mono text-sm">{{ $product->serial_number ?? 'N/A' }}</td>
                    <td class="p-4">
                        <span class="text-green-800 text-xs px-2 py-1 font-bold rounded-full">
                            {{ $product->warranty_period ?? 'N/A' }}
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
                    <td class="p-4">
                        <span
                            class="text-xs px-2 py-1 font-bold rounded-full {{ $product->product_condition === 'Second Hand' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ $product->product_condition }}
                        </span>
                    </td>
                    <td class="p-4">
                        @if($product->supplier)
                            <span class="text-indigo-800 text-xs font-bold">
                                {{$product->supplier->company_name ?? 'N/A' }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs italic">N/A</span>
                        @endif
                    </td>
                    <td>
                        <span>{{ $product->created_at->format('Y-m-d') }}</span>
                    </td>
                    <td class="p-4" hidden>
                        <span class="text-purple-800 text-xs font-bold px-2 py-1 rounded-full">
                            {{ $product->stock?->price ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="p-4">
                        @php
                            $payload = [
                                'id' => $product->id,
                                'product_name' => $product->product_name,
                                'serial_number' => $product->serial_number,
                                'warranty_period' => $product->warranty_period,
                                'brand_id' => $product->brand_id,
                                'category_id' => $product->category_id,
                                'product_condition' => $product->product_condition,
                                'supplier_id' => $product->supplier_id,
                                'price' => $product->stock?->price ?? 0,
                            ];
                        @endphp
                        <div class="flex space-x-2">
                            <!-- Edit Button -->
                            <button type="button" data-product-modal data-product='@json($payload)'
                                data-action="{{ route('products.update', $product->id) }}"
                                class="bg-[#46647F] hover:bg-[#3a556f] text-white transition-colors duration-200 px-3 py-2 rounded-lg text-sm font-semibold flex items-center space-x-1">
                                <i class="fa-regular fa-pen-to-square w-4 h-4" title="Edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="border-t">
                    <td colspan="9" class="p-8 text-center text-gray-500">
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

    {{-- Pagination Links --}}
    @if($products->hasPages())
        <div class="mt-6 px-4 py-3 border-t border-gray-200">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif
</div>