@extends('POS_SYSTEM.sidebar.app')

@section('title', 'POS')
@section('name', 'POS')
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
    <div class="flex items-center space-between gap-2 w-full sm:w-auto flex-1">

        {{-- SEARCH BAR --}}
        <input type="text" placeholder="Search inventory..."
            class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">

        {{-- Brand --}}
        <select
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" selected hidden>Select Brand</option>
            <option value="all">All</option>
            @isset($brands)
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
            @endisset
        </select>

        <div class="gap-3">
            <button
                class=" px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">Add
                Customer</button>
        </div>
    </div>
    <div class="flex flex-col lg:flex-row gap-6 mt-6">

        <!-- LEFT SIDE: PRODUCTS (4 columns) -->
        <div class="container flex-1 overflow-y-auto scrollbar-hide">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="p-4">
                            @if (!empty($product->image_path))
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->product_name }}"
                                    class="w-full h-40 object-cover rounded-lg mb-3">
                            @else
                                <div class="w-full h-40 flex items-center justify-center bg-gray-100 text-gray-500 rounded-lg mb-3">
                                    No image
                                </div>
                            @endif
                            <h3 class="text-lg font-semibold text-gray-800">{{ $product->product_name }}</h3>
                            <p class="text-gray-600 text-sm mt-1">
                                <b>Serial #:</b> {{ $product->serial_number ?? 'N/A' }}
                            </p>
                            <p class="text-gray-600 text-sm mt-1">
                                <b>Brand:</b> {{ $product->brand?->brand_name ?? 'N/A' }}
                            </p>
                            <p class="text-gray-600 text-sm mt-1">
                                <b>Type:</b> {{ $product->category?->category_name ?? 'N/A' }}
                            </p>
                            <div class="mt-3 flex justify-between items-center">
                                <span class="text-indigo-600 font-bold text-base">
                                    ₱{{ number_format($product->price ?? 0, 2) }}
                                </span>
                                <button
                                    class="bg-indigo-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-indigo-700">Add</button>
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

        <!-- RIGHT SIDE: RECEIPT WITH TAB SWITCHER -->
        <div class="container w-full lg:w-1/3">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden h-fit sticky top-6">
                <!-- Customer Name Input -->
                <div class="p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                    <input type="text" id="customerName" placeholder="Enter customer name"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out text-sm" />
                </div>
                <!-- Tab Buttons -->
                <div class="flex border-b border-gray-200">
                    <button onclick="switchTab('purchase')" id="purchaseTab"
                        class="flex-1 py-3 px-4 text-center font-semibold transition-all duration-200 border-b-2 border-indigo-600 text-indigo-600 bg-white">
                        Purchase
                    </button>
                    <button onclick="switchTab('quotation')" id="quotationTab"
                        class="flex-1 py-3 px-4 text-center font-semibold transition-all duration-200 border-b-2 border-transparent text-gray-500 bg-gray-50 hover:bg-gray-100">
                        Quotation
                    </button>
                </div>

                <!-- Tab Content Container -->
                <div class="p-6">

                    <!-- PURCHASE TAB CONTENT -->
                    <div id="purchaseContent" class="tab-content">
                        <h2 class="text-xl font-extrabold text-gray-900 mb-4 border-b pb-2">🧾 Purchase</h2>

                        <!-- Customer Info -->
                        <div class="mb-4 text-sm">
                            <p class="text-gray-700">Order #: <span class="font-semibold text-gray-900">Serial Number</span>
                            </p>
                            <p class="text-gray-700">Date: <span
                                    class="font-semibold text-gray-900">{{ now()->format('M d, Y h:i A') }}</span></p>
                        </div>

                        <!-- Order List -->
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Order Items</h3>
                            <ul class="orderList divide-y divide-gray-200 max-h-48 overflow-y-auto scrollbar-hide">
                                <li class="py-2">
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium text-gray-900 text-sm">Gaming Mouse</span>
                                        <span class="text-gray-800 text-sm font-semibold">₱2,500.00</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex justify-between">
                                        <span>Qty: 1 @ ₱2,500</span>
                                        <span>Subtotal: ₱2,500</span>
                                    </div>
                                </li>

                                <li class="py-2">
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium text-gray-900 text-sm">Mechanical Keyboard</span>
                                        <span class="text-gray-800 text-sm font-semibold">₱4,999.00</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex justify-between">
                                        <span>Qty: 1 @ ₱4,999</span>
                                        <span>Subtotal: ₱4,999</span>
                                    </div>
                                </li>

                                <li class="py-2">
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium text-gray-900 text-sm">Gaming Headset</span>
                                        <span class="text-gray-800 text-sm font-semibold">₱7,500.00</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex justify-between">
                                        <span>Qty: 2 @ ₱3,750</span>
                                        <span>Subtotal: ₱7,500</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Summary -->
                        <div class="border-t border-gray-300 pt-3 text-sm">
                            <div class="flex justify-between mb-1.5">
                                <span class="text-gray-700">Subtotal</span>
                                <span class="font-medium text-gray-900">₱14,999.00</span>
                            </div>
                            <div class="flex justify-between mb-1.5">

                                <span class="text-gray-700 mt-3">
                                    <input class="" type="text" placeholder="Discount">
                                </span>
                                <span class="font-medium text-red-500">-₱750.00</span>
                            </div>
                            <div class="flex justify-between mb-1.5 mt-3">
                                <span class="text-gray-700">VAT (12%)</span>
                                <span class="font-medium text-gray-900">₱1,710.00</span>
                            </div>
                            <div
                                class="flex justify-between font-extrabold text-gray-900 text-base border-t border-gray-300 pt-2 mt-2">
                                <span>Total</span>
                                <span>₱15,959.00</span>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="mt-5">
                            <button id="checkout-btn"
                                class="w-full bg-indigo-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path
                                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                </svg>
                                Proceed to Checkout
                            </button>
                        </div>
                    </div>

                    <!-- QUOTATION TAB CONTENT -->
                    <div id="quotationContent" class="tab-content hidden">
                        <h2 class="text-xl font-extrabold text-gray-900 mb-4 border-b pb-2">📋 Quotation</h2>

                        <!-- Customer Info (No Customer Name) -->
                        <div class="mb-4 text-sm">
                            <p class="text-gray-700">Order #: <span class="font-semibold text-gray-900">Serial Number</span>
                            </p>
                            <p class="text-gray-700">Date: <span
                                    class="font-semibold text-gray-900">{{ now()->format('M d, Y h:i A') }}</span></p>
                        </div>

                        <!-- Order List -->
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Order Items</h3>
                            <ul class="orderList divide-y divide-gray-200 max-h-48 overflow-y-auto scrollbar-hide">
                                <li class="py-2">
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium text-gray-900 text-sm">Gaming Mouse</span>
                                        <span class="text-gray-800 text-sm font-semibold">₱2,500.00</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex justify-between">
                                        <span>Qty: 1 @ ₱2,500</span>
                                        <span>Subtotal: ₱2,500</span>
                                    </div>
                                </li>

                                <li class="py-2">
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium text-gray-900 text-sm">Mechanical Keyboard</span>
                                        <span class="text-gray-800 text-sm font-semibold">₱4,999.00</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex justify-between">
                                        <span>Qty: 1 @ ₱4,999</span>
                                        <span>Subtotal: ₱4,999</span>
                                    </div>
                                </li>

                                <li class="py-2">
                                    <div class="flex justify-between items-start">
                                        <span class="font-medium text-gray-900 text-sm">Gaming Headset</span>
                                        <span class="text-gray-800 text-sm font-semibold">₱7,500.00</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex justify-between">
                                        <span>Qty: 2 @ ₱3,750</span>
                                        <span>Subtotal: ₱7,500</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Summary -->
                        <div class="border-t border-gray-300 pt-3 text-sm">
                            <div class="flex justify-between mb-1.5">
                                <span class="text-gray-700">Subtotal</span>
                                <span class="font-medium text-gray-900">₱14,999.00</span>
                            </div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-gray-700">Discount (5%)</span>
                                <span class="font-medium text-red-500">-₱750.00</span>
                            </div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-gray-700">VAT (12%)</span>
                                <span class="font-medium text-gray-900">₱1,710.00</span>
                            </div>
                            <div
                                class="flex justify-between font-extrabold text-gray-900 text-base border-t border-gray-300 pt-2 mt-2">
                                <span>Total</span>
                                <span>₱15,959.00</span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-5 flex flex-col gap-2">
                            <button id="print-quotation-btn"
                                class="w-full bg-green-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-green-700 transition duration-150 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Proceed to Print Quotation
                            </button>
                            <button id="add-pc-build-btn"
                                class="w-full bg-indigo-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Add to PC BUILD
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Tab Switching Script -->
    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active styles from all tabs
            const purchaseTab = document.getElementById('purchaseTab');
            const quotationTab = document.getElementById('quotationTab');

            purchaseTab.classList.remove('border-indigo-600', 'text-indigo-600', 'bg-white');
            purchaseTab.classList.add('border-transparent', 'text-gray-500', 'bg-gray-50', 'hover:bg-gray-100');

            quotationTab.classList.remove('border-indigo-600', 'text-indigo-600', 'bg-white');
            quotationTab.classList.add('border-transparent', 'text-gray-500', 'bg-gray-50', 'hover:bg-gray-100');

            // Show selected tab content and apply active styles
            if (tabName === 'purchase') {
                document.getElementById('purchaseContent').classList.remove('hidden');
                purchaseTab.classList.add('border-indigo-600', 'text-indigo-600', 'bg-white');
                purchaseTab.classList.remove('border-transparent', 'text-gray-500', 'bg-gray-50', 'hover:bg-gray-100');
            } else if (tabName === 'quotation') {
                document.getElementById('quotationContent').classList.remove('hidden');
                quotationTab.classList.add('border-indigo-600', 'text-indigo-600', 'bg-white');
                quotationTab.classList.remove('border-transparent', 'text-gray-500', 'bg-gray-50', 'hover:bg-gray-100');
            }
        }

        // Generalized Confirmation Handler
        function showConfirmation(title, text, icon, confirmButtonText, confirmButtonColor, actionType) {
            const customerName = document.getElementById('customerName').value.trim();

            // Validate customer name
            if (!customerName && actionType === 'checkout') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Customer Name Required',
                    text: 'Please enter a customer name before proceeding.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                });
                return;
            }

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#d33',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show processing timer
                    showProcessingTimer(actionType, customerName);
                }
            });
        }

        // Generalized Processing Timer
        function showProcessingTimer(actionType, customerName = '') {
            let timerInterval;
            let title = '';
            let html = '';

            if (actionType === 'checkout') {
                title = '🛒 Processing Purchase';
                html = `<p>Customer: <strong>${customerName}</strong></p><p>Processing order in <b></b> seconds...</p>`;
            } else if (actionType === 'print') {
                title = '🖨️ Preparing Quotation';
                html = 'Generating quotation document in <b></b> seconds...';
            } else if (actionType === 'pcbuild') {
                title = '🖥️ Adding to PC Build';
                html = 'Adding items to PC build in <b></b> seconds...';
            }

            Swal.fire({
                title: title,
                html: html,
                timer: 3000,
                timerProgressBar: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                        const secondsLeft = Math.ceil(Swal.getTimerLeft() / 1000);
                        timer.textContent = secondsLeft;
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                }
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    showSuccessMessage(actionType, customerName);
                }
            });
        }

        // Success Message
        function showSuccessMessage(actionType, customerName = '') {
            if (actionType === 'checkout') {
                Swal.fire({
                    icon: 'success',
                    title: 'Purchase Complete!',
                    html: `<p>Order for <strong>${customerName}</strong> has been processed successfully.</p>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                });
            } else if (actionType === 'print') {
                Swal.fire({
                    icon: 'success',
                    title: 'Quotation Ready!',
                    text: 'The quotation document is ready to print.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981'
                });
            } else if (actionType === 'pcbuild') {
                Swal.fire({
                    icon: 'success',
                    title: 'Added to PC Build!',
                    text: 'Items have been successfully added to PC build.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                });
            }
        }

        // Event Listeners
        document.getElementById('checkout-btn').addEventListener('click', function () {
            showConfirmation(
                'Confirm Purchase',
                'Are you sure you want to proceed with this purchase?',
                'question',
                'Yes, Proceed!',
                '#6366f1',
                'checkout'
            );
        });

        document.getElementById('print-quotation-btn').addEventListener('click', function () {
            showConfirmation(
                'Confirm Print',
                'Are you sure you want to print this quotation?',
                'question',
                'Yes, Print!',
                '#10b981',
                'print'
            );
        });

        document.getElementById('add-pc-build-btn').addEventListener('click', function () {
            showConfirmation(
                'Confirm Add to PC Build',
                'Are you sure you want to add these items to PC build?',
                'question',
                'Yes, Add!',
                '#6366f1',
                'pcbuild'
            );
        });
    </script>
@endsection