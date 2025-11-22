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
    <div class="flex items-center space-between gap-2 w-full sm:w-auto flex-1 flex-wrap">

        {{-- Search Bar --}}
        <input type="text" id="productSearch" placeholder="Search products by name, brand, or category..."
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white flex-1 min-w-64" />

        {{-- Category Filter --}}
        <select id="categoryFilter"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" selected hidden>Select Category</option>
            <option value="all">All Categories</option>
            @isset($categories)
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
            @endisset
        </select>

        {{-- Brand Filter --}}
        <select id="brandFilter"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" selected hidden>Select Brand</option>
            <option value="all">All Brands</option>
            @isset($brands)
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                @endforeach
            @endisset
        </select>

        {{-- Condition Filter --}}
        <select id="conditionFilter"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" selected hidden>Select Condition</option>
            <option value="all">All Conditions</option>
            <option value="Brand New">Brand New</option>
            <option value="Second Hand">Second Hand</option>
        </select>

        {{-- Sort Filter --}}
        <select id="sortFilter"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="name_asc" selected>Sort by Name (A-Z)</option>
            <option value="name_desc">Sort by Name (Z-A)</option>
            <option value="price_asc">Sort by Price (Low to High)</option>
            <option value="price_desc">Sort by Price (High to Low)</option>
            <option value="qty_asc">Sort by Stock (Low to High)</option>
            <option value="qty_desc">Sort by Stock (High to Low)</option>
        </select>

        <div class="gap-3">
            <a href="{{ route('customer.addCustomer') }}"
                class=" px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2
                                                                                                                                                                                                                                                                        focus:ring-indigo-500 transition duration-150 ease-in-out">Add
                Customer</a>
        </div>
    </div>
    <div class="flex flex-col lg:flex-row gap-6 mt-6">

        <!-- LEFT SIDE: PRODUCTS DISPLAY (Included from display_productFrame) -->
        @include('POS_SYSTEM.display_productFrame')

        <!-- RIGHT SIDE: RECEIPT WITH TAB SWITCHER (Component) -->
        @include('POS_SYSTEM.purchaseFrame')
    </div>

    <!-- Tab Switching Script -->
    <script>
        // Store order items
        let orderItems = [];
        const allProducts = @json($grouped);

        // Filter and sort products by category, brand, condition, and search query
        function filterProducts() {
            const categoryFilter = document.getElementById('categoryFilter').value;
            const brandFilter = document.getElementById('brandFilter').value;
            const conditionFilter = document.getElementById('conditionFilter').value;
            const sortFilter = document.getElementById('sortFilter').value;
            const searchQuery = document.getElementById('productSearch').value.toLowerCase();
            const productCards = Array.from(document.querySelectorAll('.product-card'));

            // Filter products
            const filteredCards = productCards.filter(card => {
                const cardCategory = card.getAttribute('data-category');
                const cardBrand = card.getAttribute('data-brand');
                const cardCondition = card.getAttribute('data-condition');
                const productName = card.querySelector('h3').textContent.toLowerCase();
                const brandName = card.querySelector('p:nth-of-type(1)').textContent.toLowerCase();
                const typeName = card.querySelector('p:nth-of-type(2)').textContent.toLowerCase();

                const categoryMatch = categoryFilter === '' || categoryFilter === 'all' || cardCategory === categoryFilter;
                const brandMatch = brandFilter === '' || brandFilter === 'all' || cardBrand === brandFilter;
                const conditionMatch = conditionFilter === '' || conditionFilter === 'all' || cardCondition === conditionFilter;
                const searchMatch = searchQuery === '' || productName.includes(searchQuery) || brandName.includes(searchQuery) || typeName.includes(searchQuery);

                return categoryMatch && brandMatch && conditionMatch && searchMatch;
            });

            // Sort products
            filteredCards.sort((a, b) => {
                const aName = a.querySelector('h3').textContent;
                const bName = b.querySelector('h3').textContent;
                const aPrice = parseFloat(a.getAttribute('data-price')) || 0;
                const bPrice = parseFloat(b.getAttribute('data-price')) || 0;
                const aQty = parseInt(a.getAttribute('data-quantity')) || 0;
                const bQty = parseInt(b.getAttribute('data-quantity')) || 0;

                switch (sortFilter) {
                    case 'name_desc':
                        return bName.localeCompare(aName);
                    case 'price_asc':
                        return aPrice - bPrice;
                    case 'price_desc':
                        return bPrice - aPrice;
                    case 'qty_asc':
                        return aQty - bQty;
                    case 'qty_desc':
                        return bQty - aQty;
                    default: // name_asc
                        return aName.localeCompare(bName);
                }
            });

            // Update display
            const grid = document.getElementById('productsGrid');
            productCards.forEach(card => card.style.display = 'none');
            filteredCards.forEach(card => card.style.display = '');

            // Reorder cards in DOM
            filteredCards.forEach(card => {
                grid.appendChild(card);
            });
        }

        // Add product to order (populate serial number)
        function addProductToOrder(element, serial, name, price) {
            // Check if serial number already exists in order (basis is serial number, not brand)
            const scannedSerials = document.getElementById('scannedSerialNumbers').value.split(',').filter(s => s);
            if (scannedSerials.includes(serial)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Duplicate Product',
                    html: `<p>Product with serial <strong>${serial}</strong> has been input already</p>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f59e0b',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const serialInput = document.getElementById('productSerialNo');
            serialInput.value = serial;
            serialInput.focus();
        }

        // Barcode scanning logic is handled in purchaseFrame.blade.php
        // This prevents duplicate event listeners and alerts

        // Add item to order list (basis: quantity, not serial number)
        function addItemToOrder(product) {
            // Add product ID to tracked list
            const productId = product.id;
            const scannedProductIds = document.getElementById('scannedSerialNumbers').value.split(',').filter(s => s);
            const updatedProductIds = scannedProductIds.filter(s => s !== productId.toString()).join(',');
            document.getElementById('scannedSerialNumbers').value = updatedProductIds;

            // Add product with serial number to order
            orderItems.push({
                id: product.id,
                name: product.product_name,
                price: product.price,
                serialNumber: product.serial_number, // Store serial number
                warranty: product.warranty_period || '1 Year', // Store warranty from database
                qty: 1 // Each scanned item = 1 unit
            });

            // Update display
            updateOrderDisplay();

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Product Added!',
                html: `<p><strong>${product.product_name}</strong> (SN: ${product.serial_number}) added to order</p>`,
                timer: 2000,
                showConfirmButton: false,
                position: 'top-end'
            });
        }

        // Update order display in both tabs (basis: quantity)
        function updateOrderDisplay() {
            const purchaseList = document.getElementById('purchaseOrderList');
            const emptyPurchaseMsg = document.getElementById('emptyOrderMsg');

            let html = '';
            let subtotal = 0;

            orderItems.forEach((item, index) => {
                const itemSubtotal = item.price * item.qty; // Price × Quantity
                subtotal += itemSubtotal;

                html += `
                                                                                                                                                                                                            <li class="py-3 px-3 hover:bg-gray-100 transition">
                                                                                                                                                                                                                <div class="grid grid-cols-12 gap-1 items-center text-xs">
                                                                                                                                                                                                                    <div class="col-span-4">
                                                                                                                                                                                                                        <p class="font-medium text-gray-900 truncate">${item.name}</p>
                                                                                                                                                                                                                        <p class="text-gray-500 text-xs">SN: ${item.serialNumber}</p>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                    <div class="col-span-2 text-center">
                                                                                                                                                                                                                        <span class="text-gray-700 text-xs">${item.warranty}</span>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                    <div class="col-span-2 text-center">
                                                                                                                                                                                                                        <span class="text-gray-700 font-semibold">₱${item.price.toFixed(2)}</span>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                    <div class="col-span-3 text-right">
                                                                                                                                                                                                                        <span class="font-semibold text-gray-900">₱${itemSubtotal.toFixed(2)}</span>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                    <div class="col-span-1 text-center">
                                                                                                                                                                                                                        <button onclick="removeItem(${index})" class="text-red-500 hover:text-red-700 font-bold text-lg">−</button>
                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                </div>
                                                                                                                                                                                                            </li>
                                                                                                                                                                                                        `;
            });

            purchaseList.innerHTML = html;

            // Show/hide empty messages
            emptyPurchaseMsg.style.display = orderItems.length === 0 ? 'block' : 'none';

            // Update subtotal
            document.getElementById('purchaseSubtotalDisplay').textContent = subtotal.toFixed(2);

            // Recalculate totals
            updatePurchaseTotal();
        }

        // Remove item from order (basis: product ID)
        function removeItem(index) {
            // Remove product ID from tracked list
            const productId = orderItems[index].id;
            const scannedProductIds = document.getElementById('scannedSerialNumbers').value.split(',').filter(s => s);
            const updatedProductIds = scannedProductIds.filter(s => s !== productId.toString()).join(',');
            document.getElementById('scannedSerialNumbers').value = updatedProductIds;

            // Remove item from order
            orderItems.splice(index, 1);
            updateOrderDisplay();
        }

        // Update Purchase Total
        function updatePurchaseTotal() {
            const subtotal = parseFloat(document.getElementById('purchaseSubtotalDisplay').textContent) || 0;
            const discount = parseFloat(document.getElementById('purchaseDiscountInput').value) || 0;
            // CALCULATION HERE
            const vat = (subtotal - discount) * 0.03;
            const total = subtotal - discount + vat;

            document.getElementById('purchaseDiscountDisplay').textContent = discount.toFixed(2);
            document.getElementById('purchaseVAT').textContent = vat.toFixed(2);
            document.getElementById('purchaseTotalDisplay').textContent = total.toFixed(2);
        }

        // Event listeners for filters and search
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('brandFilter').addEventListener('change', filterProducts);
        document.getElementById('conditionFilter').addEventListener('change', filterProducts);
        document.getElementById('sortFilter').addEventListener('change', filterProducts);
        document.getElementById('productSearch').addEventListener('input', filterProducts);

        // Generalized Confirmation Handler
        function showConfirmation(title, text, icon, confirmButtonText, confirmButtonColor, actionType) {
            const productSerialNo = document.getElementById('productSerialNo').value.trim();

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
                    showProcessingTimer(actionType, productSerialNo);
                }
            });
        }

        // Generalized Processing Timer
        function showProcessingTimer(actionType, productSerialNo = '') {
            let timerInterval;
            let title = '';
            let html = '';

            if (actionType === 'checkout') {
                title = '🛒 Processing Purchase';
                html = `<p>Product Serial: <strong>${productSerialNo}</strong></p><p>Processing order in <b></b> seconds...</p>`;
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
                    showSuccessMessage(actionType, productSerialNo);
                }
            });
        }

        // Success Message
        function showSuccessMessage(actionType, productSerialNo = '') {
            if (actionType === 'checkout') {
                Swal.fire({
                    icon: 'success',
                    title: 'Purchase Complete!',
                    html: `<p>Order for product <strong>${productSerialNo}</strong> has been processed successfully.</p>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#6366f1'
                });
            }
        }

        // Checkout Modal Functions
        function openCheckoutModal() {
            const checkoutModal = document.getElementById('checkoutModal');
            if (checkoutModal) {
                checkoutModal.classList.remove('hidden');
                checkoutModal.classList.add('flex');
                // Pre-fill amount with current total
                const totalAmount = document.getElementById('purchaseTotalDisplay').textContent;
                document.getElementById('amount').value = totalAmount;
            }
        }

        function closeCheckoutModal() {
            const checkoutModal = document.getElementById('checkoutModal');
            if (checkoutModal) {
                checkoutModal.classList.add('hidden');
                checkoutModal.classList.remove('flex');
            }
        }

        // Handle checkout form submission with SweetAlert flow
        function handleCheckout(event) {
            event.preventDefault();

            const customerName = document.getElementById('customerName').value;
            const paymentMethod = document.getElementById('paymentMethod').value;
            const amount = document.getElementById('amount').value;

            // Get order items
            const orderListItems = document.querySelectorAll('#purchaseOrderList li');
            if (orderListItems.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Items',
                    text: 'Please add items to the order before checkout.',
                    confirmButtonColor: '#f59e0b'
                });
                return;
            }

            // Close modal first
            closeCheckoutModal();

            // Show confirmation alert
            Swal.fire({
                icon: 'question',
                title: 'Confirm Purchase',
                html: `<p><strong>${customerName}</strong></p><p>Payment Method: <strong>${paymentMethod}</strong></p><p>Amount: <strong>₱${parseFloat(amount).toFixed(2)}</strong></p>`,
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Proceed!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show processing timer
                    showCheckoutProcessing(customerName, paymentMethod, amount);
                }
            });
        }

        // Processing timer for checkout
        function showCheckoutProcessing(customerName, paymentMethod, amount) {
            let timerInterval;

            Swal.fire({
                title: '🛒 Processing Purchase',
                html: `<p>Customer: <strong>${customerName}</strong></p><p>Processing order in <b></b> seconds...</p>`,
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
                    showCheckoutSuccess(customerName, paymentMethod, amount);
                }
            });
        }

        // Success message and redirect
        function showCheckoutSuccess(customerName, paymentMethod, amount) {
            Swal.fire({
                icon: 'success',
                title: 'Purchase Complete!',
                html: `<p>Order for <strong>${customerName}</strong> has been processed successfully.</p>`,
                confirmButtonText: 'View Receipt',
                confirmButtonColor: '#6366f1'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Prepare order data
                    const orderListItems = document.querySelectorAll('#purchaseOrderList li');
                    const orderData = {
                        customerName: customerName,
                        paymentMethod: paymentMethod,
                        amount: amount,
                        subtotal: document.getElementById('purchaseSubtotalDisplay').textContent,
                        discount: document.getElementById('purchaseDiscountDisplay').textContent,
                        vat: document.getElementById('purchaseVAT').textContent,
                        total: document.getElementById('purchaseTotalDisplay').textContent,
                        items: []
                    };

                    // Collect order items from orderItems array (more reliable)
                    orderItems.forEach(item => {
                        const subtotal = (item.price * item.qty).toFixed(2);

                        orderData.items.push({
                            productName: item.name,
                            price: item.price.toFixed(2),
                            warranty: item.warranty || '1 Year',
                            quantity: item.qty,
                            subtotal: subtotal
                        });
                    });

                    // Store data in sessionStorage for receipt page
                    sessionStorage.setItem('receiptData', JSON.stringify(orderData));

                    // Redirect to receipt page
                    window.location.href = '{{ route("pos.purchasereceipt") }}';
                }
            });
        }

        // Attach checkout form submission handler
        const checkoutForm = document.getElementById('checkoutForm');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', handleCheckout);
        }
    </script>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative">
            <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
                onclick="closeCheckoutModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h3>
            <form id="checkoutForm">
                <!-- Customer Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                    <input type="text" id="customerName" name="customerName"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        placeholder="Enter customer name" required>
                </div>

                <!-- Payment Method -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select id="paymentMethod" name="paymentMethod"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        required>
                        <option value="">Select Payment Method</option>
                        <option value="Cash">Cash</option>
                        <option value="Gcash">Gcash</option>
                        <option value="BPI">BPI</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="Others">Others</option>
                    </select>
                </div>

                <!-- Amount -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-700 font-semibold">₱</span>
                        <input type="number" id="amount" name="amount" step="0.01" min="0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-8 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="0.00" required>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="closeCheckoutModal()"
                        class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition duration-150">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold transition duration-150 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M17.778 8.222c-4.296-4.296-11.26-4.296-15.556 0A1 1 0 01.808 6.808c5.076-5.077 13.308-5.077 18.384 0a1 1 0 01-1.414 1.414zM14.95 11.05a7 7 0 00-9.9 0 1 1 0 01-1.414-1.414 9 9 0 0112.728 0 1 1 0 01-1.414 1.414zM12.12 13.88a3 3 0 00-4.242 0 1 1 0 01-1.415-1.415 5 5 0 017.072 0 1 1 0 01-1.415 1.415zM9 16a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Proceed
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection