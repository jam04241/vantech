<!-- LEFT SIDE: PRODUCTS DISPLAY (4 columns) -->
<div class="container flex-1 overflow-y-auto scrollbar-hide">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6" id="productsGrid">
        @forelse($grouped as $product)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition product-card"
                data-category="{{ $product->category_id ?? '' }}" data-brand="{{ $product->brand_id ?? '' }}"
                data-quantity="{{ $product->stock ?? 0 }}">
                <div class="p-4">

                    <h3 class="text-lg font-semibold text-gray-800">{{ $product->product_name }}</h3>
                    <p class="text-gray-600 text-sm mt-2">
                        <b>Brand:</b> {{ $product->brand?->brand_name ?? 'N/A' }}
                    </p>
                    <p class="text-gray-600 text-sm mt-1">
                        <b>Type:</b> {{ $product->category?->category_name ?? 'N/A' }}
                    </p>
                    <p class="text-gray-600 text-sm mt-1">
                        <b>Stock:</b> <span class="font-semibold">{{ $product->stock }}</span>
                    </p>
                    <p class="text-sm mt-1">
                        <b>Availability:</b>
                        <span class="font-semibold {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                        </span>
                    </p>
                    <div class="mt-3 flex justify-between items-center">
                        <span class="text-indigo-600 font-bold text-base">
                            ₱{{ number_format($product->price, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-md p-6 text-center text-gray-500">
                    No products available yet.
                </div>
            </div>
        @endforelse
    </div>
</div>