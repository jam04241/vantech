<table class="min-w-full mt-4">
    <thead>
        <tr class="bg-gray-200">
            <th class="px-4 py-2 text-left">Product Name</th>
            <th class="px-4 py-2 text-left">Brand</th>
            <th class="px-4 py-2 text-left">Category</th>
            <th class="px-4 py-2 text-left">Quantity</th>
            <th class="px-4 py-2 text-left">Price</th>
            <th class="px-4 py-2 text-left">Condition</th>
            <th class="px-4 py-2 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $product->product_name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $product->brand?->brand_name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $product->category?->category_name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $product->quantity }}</td>
                <td class="px-4 py-3 text-gray-600">₱{{ number_format($product->price ?? 0, 2) }}</td>
                {{-- ADDED: Colored condition text --}}
                <td class="px-4 py-3">
                    @if($product->product_condition === 'Brand New')
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Brand New</span>
                    @elseif($product->product_condition === 'Second Hand')
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">Second
                            Hand</span>
                    @else
                        <span
                            class="px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">{{ $product->product_condition }}</span>
                    @endif
                </td>

                <td class="px-4 py-3">
                    @php
                        $payload = [
                            'product_name' => $product->product_name,
                            'brand_name' => $product->brand?->brand_name,
                            'category_name' => $product->category?->category_name,
                            'price' => $product->price,
                            'brand_id' => $product->brand_id,
                            'category_id' => $product->category_id,
                            'product_condition' => $product->product_condition
                        ];
                    @endphp
                    <button type="button"
                        class="text-indigo-600 hover:text-indigo-800 p-2 rounded-full hover:bg-indigo-50 transition edit-price-btn"
                        data-action="{{ route('products.update_price', $product->id) }}" data-product='@json($payload)'
                        title="Edit Price">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    No products available.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
