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
    <!-- Success Message Container -->
    @if(session('success') && session('from_customer_add'))
        <div id="customerSuccessMessage"
            class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg w-full">
            <div class="flex justify-between items-center">
                <p>{{ session('success') }}</p>
                <button type="button" class="text-green-700"
                    onclick="document.getElementById('customerSuccessMessage').style.display = 'none';">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
        </div>
    @endif

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
                class=" px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2>
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
            console.log('=== ADD ITEM TO ORDER ===');
            console.log('Product data received from API:', product);

            // Check if product already exists in order (by product ID for grouping)
            const existingItemIndex = orderItems.findIndex(item =>
                item.id === product.id && item.serialNumber === product.serial_number
            );

            if (existingItemIndex !== -1) {
                // Product with same serial already exists - this should not happen due to duplicate check
                // But if it does, update quantity
                orderItems[existingItemIndex].qty += 1;
                console.log('Updated existing item quantity:', orderItems[existingItemIndex]);
            } else {
                // Add new product with serial number to order
                const itemData = {
                    id: product.id,
                    name: product.product_name,
                    price: parseFloat(product.price) || 0,
                    serialNumber: product.serial_number,
                    warranty: product.warranty_period || '1 Year',
                    qty: 1
                };

                console.log('📋 NEW ITEM DATA FOR PURCHASE ORDER:', {
                    product_id: itemData.id,
                    serial_number: itemData.serialNumber,
                    unit_price: itemData.price,
                    quantity: itemData.qty,
                    total_price: itemData.price * itemData.qty
                });

                orderItems.push(itemData);
            }

            console.log('Total items in order:', orderItems.length);
            console.log('All order items:', orderItems);

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
            console.log('=== UPDATE ORDER DISPLAY ===');
            const purchaseList = document.getElementById('purchaseOrderList');
            const emptyPurchaseMsg = document.getElementById('emptyOrderMsg');

            let html = '';
            let subtotal = 0;

            console.log('📊 DISPLAYING ORDER ITEMS:');
            orderItems.forEach((item, index) => {
                const itemSubtotal = item.price * item.qty;
                subtotal += itemSubtotal;
                const sequenceNumber = index + 1;

                console.log(`Item ${sequenceNumber}:`, {
                    product_id: item.id,
                    product_name: item.name,
                    serial_number: item.serialNumber,
                    warranty: item.warranty,
                    unit_price: item.price,
                    quantity: item.qty,
                    subtotal: itemSubtotal
                });

                html += `
                <li class="py-3 px-3 hover:bg-gray-100 transition"
                    data-product-id="${item.id}"
                    data-serial-number="${item.serialNumber}"
                    data-unit-price="${item.price}"
                    data-quantity="${item.qty}"
                    data-total-price="${itemSubtotal}">
                    <div class="grid grid-cols-12 gap-1 items-center text-xs">
                        <div class="col-span-1 text-center">
                            <span class="font-semibold text-gray-900">${sequenceNumber}</span>
                        </div>
                        <div class="col-span-3">
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

            console.log('💰 TOTALS UPDATED:');
            console.log('   Subtotal:', subtotal.toFixed(2));

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

            console.log('💰 PURCHASE TOTALS (Line 53-54):');
            console.log('   Unit Price (line 53): ₱' + subtotal.toFixed(2));
            console.log('   Total Price (line 54): ₱' + total.toFixed(2));
            console.log('   Discount: ₱' + discount.toFixed(2));
            console.log('   VAT (3%): ₱' + vat.toFixed(2));
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
                timer: 2000,
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

        // NOTE: Checkout form is in purchaseFrame.blade.php
        // Checkout flow: CheckoutController → Customer_Purchase_OrderController → Payment_MethodController
        // This prevents duplicate form conflicts and ensures proper separation of concerns
    </script>

    <!-- Auto-hide success message after 5 seconds -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('customerSuccessMessage');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
            }
        });
    </script>
@endsection