<!-- LEFT SIDE: PRODUCTS DISPLAY (4 columns) -->
<div class="container flex-1 overflow-y-auto scrollbar-hide">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6" id="productsGrid">
        @forelse($products as $product)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition product-card cursor-pointer"
                data-category="{{ $product->category_id ?? '' }}" data-brand="{{ $product->brand_id ?? '' }}"
                data-condition="{{ $product->product_condition ?? '' }}"
                data-quantity="{{ $product->stock?->stock_quantity ?? 0 }}" data-price="{{ $product->stock?->price ?? 0 }}"
                data-id="{{ $product->id }}"
                onclick="addToCart({{ $product->id }}, '{{ addslashes($product->product_name) }}', {{ $product->stock?->price ?? 0 }}, '{{ addslashes($product->serial_number) }}', '{{ addslashes($product->brand?->brand_name ?? 'N/A') }}', '{{ addslashes($product->category?->category_name ?? 'N/A') }}', '{{ $product->product_condition }}')">

                <div
                    class="p-4 border-b {{ $product->product_condition === 'Brand New' ? 'bg-green-50' : 'bg-orange-50' }}">
                    <div class="flex justify-between items-start">
                        <span
                            class="text-xs font-semibold px-2 py-1 rounded-full {{ $product->product_condition === 'Brand New' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                            {{ $product->product_condition }}
                        </span>
                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                            #{{ $product->id }}
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->product_name }}</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Brand:</span>
                            <span class="brand-name text-gray-800">{{ $product->brand?->brand_name ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Category:</span>
                            <span
                                class="category-name text-gray-800">{{ $product->category?->category_name ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Serial No:</span>
                            <span class="serial-number text-gray-800 font-mono text-xs">{{ $product->serial_number }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Warranty:</span>
                            <span class="warranty-info text-gray-800">{{ $product->warranty_period ?? 'N/A' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-600 font-medium">Stock:</span>
                            <span class="text-gray-800">{{ $product->stock?->stock_quantity ?? 0 }} available</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition product-card cursor-pointer">
                <div class="bg-white rounded-xl shadow-md p-8 text-center text-gray-500 col-span-4">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg font-medium text-gray-600">No products available</p>
                    <p class="text-sm text-gray-500 mt-1">Try changing your filters or search term</p>
                </div>
            </div>
        @endforelse
    </div>
</div>