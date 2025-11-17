<table class="min-w-full mt-4">
    <thead>
        <tr class="bg-gray-200">
            <th class="px-4 py-2 text-left">Product Name</th>
            <th class="px-4 py-2 text-left">Brand</th>
            <th class="px-4 py-2 text-left">Category</th>
            <th class="px-4 py-2 text-left">Quantity</th>
            <th class="px-4 py-2 text-left">Price</th>
            <th class="px-4 py-2 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $product->product_name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $product->brand?->brand_name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $product->category?->category_name ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $product->quantity ?? 'N/A' }}</td>
                <td class="px-4 py-3 text-gray-800">
                    ₱{{ number_format($product->price ?? 0, 2) }}
                </td>
                <td class="px-4 py-3">
                    <button class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">View</button>
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

