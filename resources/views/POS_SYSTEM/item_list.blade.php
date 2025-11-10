@extends('POS_SYSTEM.sidebar.app')
@section('title', 'Item List')
<style>
    .scrollbar-hide {
        overflow-y: auto;
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }
</style>
@section('content_items')
    <div class="flex items-center gap-2 w-full sm:w-auto flex-1">
        <input type="text" placeholder="Search inventory..."
            class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">

        {{-- SORT BY --}}
        <select
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" selected>Sort by</option>
            <!-- Price Sorting -->
            <option value="price_low_high">Cheapest</option>
            <option value="price_high_low">Expensive</option>

            <!-- Name Sorting -->
            <option value="name_a_z">Name: A to Z</option>
            <option value="name_z_a">Name: Z to A</option>

            <!-- Stock/Quantity -->
            <option value="stock_low_high">Stock: Low to High</option>
            <option value="stock_high_low">Stock: High to Low</option>

            <!-- Date Added -->
            <option value="newest">Newest First</option>
            <option value="oldest">Oldest First</option>
            <!-- Best Sellers -->
            <option value="best_selling">Best Selling</option>
        </select>

        {{-- Brand --}}
        <select
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" selected>Brand</option>
            <!-- Price Sorting -->
            <option value="AMD">AMD</option>
            <option value="Intel">Intel</option>
            <option value="GeForce">GeForce</option>
            <option value="Logitech">Logitech</option>
        </select>
    </div>
    <div class="flex flex-col lg:flex-row gap-6 mt-6">

        <!-- LEFT SIDE: PRODUCTS (4 columns) -->
        <div class="container flex-1 overflow-y-auto scrollbar-hide">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <!-- Product 1 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-4">
                        <img src="{{ asset('images/CPU.jpg') }}" alt="Product 1"
                            class="w-50 h-50 object-cover rounded-lg mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">AMD Ryzen 5 5600g</h3>
                        <p class="text-gray-600 text-sm mt-1"><b>Type:</b> CPU</p>
                        <p class="text-gray-600 text-sm mt-1"><b>Serial #:</b> 5436547646</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-indigo-600 font-bold text-base">₱2,500</span>
                            <button
                                class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-700">Add</button>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-4">
                        <img src="{{ asset('images/GPU.jpg') }}" alt="Product 2"
                            class="w-50 h-30 object-cover rounded-lg mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">ASUS RTX 3060 8GB</h3>
                        <p class="text-gray-600 text-sm mt-1"><b>Type:</b> GPU</p>
                        <p class="text-gray-600 text-sm mt-1"><b>Serial #:</b> 5436547646</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-indigo-600 font-bold text-base">₱4,999</span>
                            <button
                                class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-700">Add</button>
                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-4">
                        <img src="{{ asset('images/mouse.jpeg') }}" alt="Product 3"
                            class="w-full h-50 object-cover rounded-lg mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">Logitech G102</h3>
                        <p class="text-gray-600 text-sm mt-1"><b>Type:</b> Peripheral</p>
                        <p class="text-gray-600 text-sm mt-1"><b>Serial #:</b> 5436547646</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-indigo-600 font-bold text-base">₱3,750</span>
                            <button
                                class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-700">Add</button>
                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="p-4">
                        <img src="{{ asset('images/monitor.png') }}" alt="Product 4"
                            class="w-full h-40 object-cover rounded-lg mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">Dell 144Hz 27-Inch Gaming Monitor</h3>
                        <p class="text-gray-600 text-sm mt-1"><b>Type:</b> Monitor</p>
                        <p class="text-gray-600 text-sm mt-1"><b>Serial #:</b> 5436547646</p>
                        <div class="mt-3 flex justify-between items-center">
                            <span class="text-indigo-600 font-bold text-base">₱15,999</span>
                            <button
                                class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-700">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: RECEIPT -->
        <div class="container w-full lg:w-1/4">
            <div class="bg-white rounded-2xl shadow-lg p-6 h-fit sticky top-6">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-5 border-b pb-3">🧾 Receipt</h2>

                <!-- Customer Info -->
                <div class="mb-6 text-lg">
                    <p class="text-gray-700">Customer: <span class="font-semibold text-gray-900">JOSH MAGCALAS</span></p>
                    <p class="text-gray-700">Order #: <span class="font-semibold text-gray-900">Serial Number</span></p>
                    <p class="text-gray-700">Date:
                        <span class="font-semibold text-gray-900">{{ now()->format('M d, Y h:i A') }}</span>
                    </p>
                </div>

                <!-- Order List -->
                <ul class="orderList divide-y divide-gray-200 mb-6 text-lg overflow-y-auto scrollbar-hide">
                    <li class="py-3">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900">Gaming Mouse</span>
                            <span class="text-gray-800">₱2,500.00</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1 flex justify-between">
                            <span>Qty: 1 @ ₱2,500</span>
                            <span>Subtotal: ₱2,500</span>
                        </div>
                    </li>

                    <li class="py-3">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900">Mechanical Keyboard</span>
                            <span class="text-gray-800">₱4,999.00</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1 flex justify-between">
                            <span>Qty: 1 @ ₱4,999</span>
                            <span>Subtotal: ₱4,999</span>
                        </div>
                    </li>

                    <li class="py-3">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-900">Gaming Headset</span>
                            <span class="text-gray-800">₱7,500.00</span>
                        </div>
                        <div class="text-sm text-gray-500 mt-1 flex justify-between">
                            <span>Qty: 2 @ ₱3,750</span>
                            <span>Subtotal: ₱7,500</span>
                        </div>
                    </li>
                </ul>

                <!-- Summary -->
                <div class="border-t border-gray-300 pt-4 text-lg">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span class="font-medium">₱14,999.00</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Discount (5%)</span>
                        <span class="font-medium text-red-500">-₱750.00</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>VAT (12%)</span>
                        <span class="font-medium">₱1,710.00</span>
                    </div>
                    <div
                        class="flex justify-between font-extrabold text-gray-900 text-xl border-t border-gray-300 pt-3 mt-3">
                        <span>Total</span>
                        <span>₱15,959.00</span>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-8 flex flex-col gap-3">
                    <button
                        class="w-full bg-indigo-600 text-white py-4 rounded-xl text-xl font-semibold shadow hover:bg-indigo-700 transition duration-150">
                        Proceed to Checkout
                    </button>

                </div>
            </div>
        </div>
    </div>
@endsection