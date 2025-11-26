<!-- 
    PURCHASE ORDER FORM
    Logic: Handles barcode scanning and order management
    Basis: Serial number for scanning, groups items by product name in order list
    Key Feature: Multiple serials of same product increase quantity
-->
<div class="container w-full lg:w-1/2">
    <!-- Success Message Container -->
    @if(session('success') && session('from_customer_add'))
        <div id="customerSuccessMessage" class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
            <div class="flex justify-between items-center">
                <p>{{ session('success') }}</p>
                <button type="button" class="text-green-700"
                    onclick="document.getElementById('customerSuccessMessage').style.display = 'none';">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden h-fit sticky top-6">

        <!-- Barcode Input -->
        <div class="p-4 bg-white border-b border-gray-200">
            <label class="block text-xs font-semibold text-gray-600 mb-2">Scan Barcode / Serial No.</label>
            <input type="text" id="productSerialNo" placeholder="Scan product barcode here..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-150 text-sm"
                autofocus />
            <input type="hidden" id="scannedSerialNumbers" />
        </div>

        <!-- Tab Content Container -->
        <div class="p-6">

            <!-- PURCHASE TAB CONTENT -->
            <div id="purchaseContent" class="tab-content">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Purchase Order</h2>

                <!-- Order Info -->
                <div class="mb-4 text-xs text-gray-600 space-y-1">
                    <p>Date: <span class="font-medium text-gray-900">{{ now()->format('M d, Y h:i A') }}</span></p>
                </div>

                <!-- Order List -->
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Order Items</h3>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        <div
                            class="grid grid-cols-12 gap-1 px-3 py-2 bg-gray-100 text-xs font-semibold text-gray-700 border-b border-gray-200">
                            <div class="col-span-1 text-center">No.</div>
                            <div class="col-span-3">Product</div>
                            <div class="col-span-2 text-center">Warranty</div>
                            <div class="col-span-2 text-center">Unit Price</div>
                            <div class="col-span-3 text-right">Subtotal</div>
                            <div class="col-span-1 text-center">Remove</div>
                        </div>
                        <ul id="purchaseOrderList"
                            class="divide-y divide-gray-200 max-h-56 overflow-y-auto scrollbar-hide">
                            <!-- Items will be added dynamically here -->
                        </ul>
                    </div>
                    <p id="emptyOrderMsg" class="text-center text-gray-500 text-sm py-4">No items added yet. Scan a
                        barcode to start.</p>
                </div>

                <!-- Summary -->
                <div class="border-t border-gray-200 pt-4 text-sm space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-gray-900">₱<span id="purchaseSubtotalDisplay">0.00</span></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Discount</span>
                        <div class="flex gap-2 items-center">
                            <input type="number" id="purchaseDiscountInput" value="0"
                                class="w-20 px-2 py-1 border border-gray-300 rounded text-right text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                step="0.01" oninput="updatePurchaseTotal()" placeholder="0.00" />
                            <span class="text-red-500 font-medium w-24 text-right text-sm" hidden>-₱<span
                                    id="purchaseDiscountDisplay" hidden>0.00</span></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">VAT (3%)</span>
                        <span class="font-semibold text-gray-900">₱<span id="purchaseVAT">0.00</span></span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 pt-2 mt-2">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="font-bold text-gray-900 text-lg">₱<span
                                id="purchaseTotalDisplay">0.00</span></span>
                    </div>
                </div>

                <!-- Button -->
                <div class="mt-5">
                    <button id="checkout-btn" onclick="openCheckoutModal()"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 110 2h-3V6a1 1 0 011-1v3a1 1 0 011 1H10a1 1 0 01-1 1H7a1 1 0 00-1 1h-3V11a1 1 0 00 1-1V7a1 1 0 011-1h2V3a2 2 0 012 2z" />
                        </svg>
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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
        <form id="checkoutForm" action="{{ route('checkout.store') }}" method="POST" onsubmit="handleCheckout(event)">
            @csrf
            <!-- Customer ID (Hidden) -->
            <input type="hidden" id="customerId" name="customer_id" value="">

            <!-- Customer Name -->
            <div class="mb-4 relative">
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name</label>
                <input type="text" id="customerName" name="customerName"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                    placeholder="Enter customer name" required autocomplete="off">
                <div id="customerSuggestions"
                    class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-48 overflow-y-auto z-10 hidden">
                </div>
            </div>

            <!-- Payment Method -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <select id="paymentMethod" name="payment_method"
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

            <!-- Items (Hidden) -->
            <div id="itemsContainer"></div>

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
                    Print Receipt
                </button>
            </div>
        </form>
    </div>
</div>

<!-- POS Product Lookup Script (Serial Number Search) -->
<script>
    // Auto-hide success message after 1.75 seconds
    document.addEventListener('DOMContentLoaded', function () {
        const successMessage = document.getElementById('customerSuccessMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 1750);
        }
    });

    // Debounce timer to prevent rapid API calls
    let scanDebounceTimer = null;
    let lastFetchTime = 0;
    const MIN_FETCH_INTERVAL = 300; // Minimum 300ms between API calls

    /**
     * Fetch product from API endpoint by serial number
     * Used when purchaseFrame is used independently
     * Basis: Search by serial_number, then group by name, brand, category, condition, price
     * Includes timeout, validation, and error handling
     */
    async function fetchProductFromAPI(serialNumber) {
        try {
            // Create abort controller for timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 5000); // 5 second timeout

            const response = await fetch(`/api/products/search-pos?serial=${encodeURIComponent(serialNumber)}`, {
                signal: controller.signal,
                headers: {
                    'Accept': 'application/json'
                }
            });

            clearTimeout(timeoutId);

            // Check if response is ok
            if (!response.ok) {
                console.error(`API Error: ${response.status} ${response.statusText}`);
                return null;
            }

            // Validate response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Invalid response type. Expected JSON, got:', contentType);
                return null;
            }

            const data = await response.json();

            // Validate response structure
            if (!data || typeof data !== 'object') {
                console.error('Invalid response structure:', data);
                return null;
            }

            if (data.product && typeof data.product === 'object') {
                // Validate required product fields
                if (!data.product.id || !data.product.product_name) {
                    console.error('Product missing required fields:', data.product);
                    return null;
                }
                // Ensure price is a valid number
                if (data.product.price === null || data.product.price === undefined || isNaN(parseFloat(data.product.price))) {
                    console.error('Product price is invalid:', data.product.price);
                    return null;
                }
                return data.product;
            } else {
                console.warn('Product not found:', data.message || 'Unknown error');
                return null;
            }
        } catch (error) {
            if (error.name === 'AbortError') {
                console.error('API request timeout - server may be overloaded');
            } else {
                console.error('Error fetching product:', error.message);
            }
            return null;
        }
    }

    /**
     * Open checkout modal
     */
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

    /**
     * Close checkout modal
     */
    function closeCheckoutModal() {
        const checkoutModal = document.getElementById('checkoutModal');
        if (checkoutModal) {
            checkoutModal.classList.add('hidden');
            checkoutModal.classList.remove('flex');
        }
    }

    /**
     * Handle checkout form submission
     */
    function handleCheckout(event) {
        event.preventDefault();

        console.log('=== CHECKOUT PROCESS STARTED ===');
        console.log('Timestamp:', new Date().toISOString());

        // DEBUG: Check if orderItems is accessible
        console.log('orderItems accessible?', typeof orderItems !== 'undefined');
        console.log('orderItems value:', typeof orderItems !== 'undefined' ? orderItems : 'NOT DEFINED');

        const customerName = document.getElementById('customerName').value;
        const customerId = document.getElementById('customerId').value;
        const paymentMethod = document.getElementById('paymentMethod').value;
        const amount = document.getElementById('amount').value;

        console.log('Checkout Data:', {
            customerName: customerName,
            customerId: customerId,
            paymentMethod: paymentMethod,
            amount: amount
        });

        // Validate: Check if order has items
        const orderListItems = document.querySelectorAll('#purchaseOrderList li');
        console.log('Order list items count:', orderListItems.length);

        if (orderListItems.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Items',
                text: 'Please add items to the order before checkout.',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }

        // Validate: Customer name is filled
        if (!customerName || customerName.trim() === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a customer before checkout.',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        // Validate: Customer ID is set
        if (!customerId || customerId === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Customer ID not found. Please select a customer from the suggestions.',
                confirmButtonColor: '#ef4444'
            });
            console.log('Debug: customerId =', customerId);
            return;
        }

        // Validate: Payment method is selected
        if (!paymentMethod || paymentMethod === '') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select a payment method.',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        // Validate: Amount is entered
        if (!amount || parseFloat(amount) <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter a valid amount.',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        // Clear previous items container
        const itemsContainer = document.getElementById('itemsContainer');
        itemsContainer.innerHTML = '';

        // Get items from DOM instead of relying on orderItems variable
        // This is more reliable than depending on JavaScript scope
        const purchaseOrderListItems = document.querySelectorAll('#purchaseOrderList li');
        let itemsToSubmit = [];

        purchaseOrderListItems.forEach((li, index) => {
            // Extract product data from the DOM
            const productIdAttr = li.getAttribute('data-product-id');
            const unitPriceAttr = li.getAttribute('data-unit-price');
            const quantityAttr = li.getAttribute('data-quantity');
            const totalPriceAttr = li.getAttribute('data-total-price');
            const serialNumberAttr = li.getAttribute('data-serial-number');

            if (productIdAttr && unitPriceAttr && quantityAttr && totalPriceAttr) {
                const purchaseOrderData = {
                    product_id: parseInt(productIdAttr),
                    unit_price: parseFloat(unitPriceAttr),
                    quantity: parseInt(quantityAttr),
                    total_price: parseFloat(totalPriceAttr),
                    serial_number: serialNumberAttr || '',
                    order_date: new Date().toISOString().split('T')[0],
                    status: 'Success'
                };

                console.log(`Item ${index + 1}:`, purchaseOrderData);
                itemsToSubmit.push(purchaseOrderData);

                // Create hidden inputs for each item field
                const inputs = [
                    { name: `items[${index}][product_id]`, value: purchaseOrderData.product_id },
                    { name: `items[${index}][unit_price]`, value: purchaseOrderData.unit_price },
                    { name: `items[${index}][quantity]`, value: purchaseOrderData.quantity },
                    { name: `items[${index}][total_price]`, value: purchaseOrderData.total_price },
                    { name: `items[${index}][serial_number]`, value: purchaseOrderData.serial_number },
                    { name: `items[${index}][order_date]`, value: purchaseOrderData.order_date },
                    { name: `items[${index}][status]`, value: purchaseOrderData.status }
                ];

                inputs.forEach(input => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = input.value;
                    itemsContainer.appendChild(hiddenInput);
                });
            }
        });

        if (itemsToSubmit.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not extract item data from order list. Please try again.',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        console.log('📋 CUSTOMER PURCHASE ORDER DATA:');
        console.log('Total items to process:', itemsToSubmit.length);
        console.log('Items:', itemsToSubmit);

        // Prepare payment method data
        const paymentMethodData = {
            method_name: paymentMethod,
            payment_date: new Date().toISOString().split('T')[0],
            amount: parseFloat(amount)
        };

        console.log('💳 PAYMENT METHOD DATA:', paymentMethodData);

        // Store data in sessionStorage for receipt page
        const receiptData = {
            customerName: customerName,
            customerId: customerId,
            paymentMethod: paymentMethod,
            amount: amount,
            subtotal: document.getElementById('purchaseSubtotalDisplay').textContent,
            discount: document.getElementById('purchaseDiscountDisplay').textContent,
            vat: document.getElementById('purchaseVAT').textContent,
            total: document.getElementById('purchaseTotalDisplay').textContent,
            items: orderItems
        };
        sessionStorage.setItem('receiptData', JSON.stringify(receiptData));

        console.log('📋 CHECKOUT FLOW:');
        console.log('1️⃣  CheckoutController receives and validates all data');
        console.log('   - customer_id:', customerId);
        console.log('   - payment_method:', paymentMethod);
        console.log('   - amount:', amount);
        console.log('   - items_count:', orderItems.length);
        console.log('2️⃣  Customer_Purchase_OrderController::store() creates purchase orders');
        console.log('   - Loops through each item');
        console.log('   - Creates customer_purchase_order record for each item');
        console.log('3️⃣  Payment_MethodController::store() creates payment method');
        console.log('   - Links to first purchase order (foreign key)');
        console.log('   - Stores payment details');
        console.log('Submitting form to backend...');

        // Show processing modal before submitting
        Swal.fire({
            title: '🛒 Processing Purchase',
            html: `<p>Customer: <strong>${customerName}</strong></p><p>Processing order in <b></b> seconds...</p>`,
            timer: 3000,
            timerProgressBar: true,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                const timer = Swal.getPopup().querySelector("b");
                let timerInterval = setInterval(() => {
                    const secondsLeft = Math.ceil(Swal.getTimerLeft() / 1000);
                    timer.textContent = secondsLeft;
                }, 100);
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('Processing timer completed, submitting form via AJAX...');

                // Get form data
                const form = document.getElementById('checkoutForm');
                const formData = new FormData(form);

                console.log('Form data being sent:');
                for (let [key, value] of formData.entries()) {
                    console.log(`  ${key}: ${value}`);
                }

                // Submit via AJAX instead of form submission
                fetch('{{ route("checkout.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response text:', response.statusText);

                        // Check if response is JSON
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json().then(data => {
                                if (!response.ok) {
                                    console.error('Server returned error:', data);
                                    throw new Error(data.message || data.error || 'Server error: ' + response.status);
                                }
                                return data;
                            });
                        } else {
                            // If not JSON, it's likely a redirect (success)
                            if (response.ok || response.status === 200 || response.status === 302 || response.status === 301) {
                                console.log('✓ Checkout successful! Redirecting to receipt...');
                                window.location.href = '{{ route("pos.purchasereceipt") }}';
                                return;
                            } else {
                                throw new Error('Server error: ' + response.status + ' ' + response.statusText);
                            }
                        }
                    })
                    .then(data => {
                        if (data) {
                            console.log('✓ Checkout successful! Response:', data);
                            window.location.href = '{{ route("pos.purchasereceipt") }}';
                        }
                    })
                    .catch(error => {
                        console.error('❌ Checkout error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Checkout Failed',
                            html: `<p>${error.message || 'An error occurred during checkout'}</p><p style="font-size: 0.8em; color: #666; margin-top: 10px;">Check browser console for details</p>`,
                            confirmButtonColor: '#ef4444'
                        });
                    });
            }
        });
    }

    /**
     * Handle barcode input with API lookup
     * This script runs when purchaseFrame is used as a standalone component
     * Searches by serial number and groups by product attributes
     * Includes debouncing and prevents duplicate rapid scans
     */
    document.addEventListener('DOMContentLoaded', function () {
        // Checkout button event listener
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', openCheckoutModal);
        }

        const productSerialInput = document.getElementById('productSerialNo');

        if (productSerialInput) {
            productSerialInput.addEventListener('input', async function (e) {
                const serialNumber = this.value.trim();

                if (!serialNumber) return;

                // Clear previous debounce timer
                if (scanDebounceTimer) {
                    clearTimeout(scanDebounceTimer);
                }

                // Debounce: wait 300ms before making API call
                scanDebounceTimer = setTimeout(async () => {
                    // Check minimum interval between fetches
                    const now = Date.now();
                    if (now - lastFetchTime < MIN_FETCH_INTERVAL) {
                        return;
                    }
                    lastFetchTime = now;

                    // Fetch product from API by serial number
                    const product = await fetchProductFromAPI(serialNumber);

                    if (!product) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Serial Number Not Found',
                            text: 'The serial number could not be found in the database or product is out of stock.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#ef4444',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        this.value = '';
                        this.focus();
                        return;
                    }

                    // Check if serial number already exists in order (by serial number, not product ID)
                    // This prevents the same physical item from being scanned twice
                    const existingOrderItems = typeof orderItems !== 'undefined' ? orderItems : [];
                    const isDuplicate = existingOrderItems.some(item => item.serialNumber === product.serial_number);

                    if (isDuplicate) {
                        // Show alert for duplicate scan to make user aware
                        console.warn(`Duplicate scan prevented: Serial ${product.serial_number} already in order`);
                        Swal.fire({
                            icon: 'warning',
                            title: 'Item Already Scanned',
                            html: `<p>Product <strong>${product.product_name}</strong><br/>Serial: <strong>${product.serial_number}</strong><br/>is already in the order</p>`,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#f59e0b',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        this.value = '';
                        this.focus();
                        return;
                    }

                    // Add product to order (if addItemToOrder function exists from item_list.blade.php)
                    if (typeof addItemToOrder === 'function') {
                        try {
                            addItemToOrder(product);
                        } catch (error) {
                            console.error('Error adding item to order:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error Adding Item',
                                text: 'Failed to add item to order. Please try again.',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#ef4444',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    } else {
                        console.error('addItemToOrder function not found. Make sure item_list.blade.php is loaded.');
                        Swal.fire({
                            icon: 'error',
                            title: 'System Error',
                            text: 'Order list component not loaded. Please refresh the page.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#ef4444'
                        });
                    }

                    // Clear input
                    this.value = '';
                    this.focus();
                }, 300); // Debounce delay
            });
        }
    });

    /**
     * Customer Name Autosuggestion
     * Fetches customers by first name, last name, or contact number
     */
    let customerSearchDebounceTimer = null;
    const customerNameInput = document.getElementById('customerName');
    const customerSuggestionsContainer = document.getElementById('customerSuggestions');

    if (customerNameInput) {
        customerNameInput.addEventListener('input', function (e) {
            clearTimeout(customerSearchDebounceTimer);
            const query = e.target.value.trim();

            if (query.length < 2) {
                customerSuggestionsContainer.classList.add('hidden');
                return;
            }

            customerSearchDebounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/customers/search?query=${encodeURIComponent(query)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        console.error('Failed to fetch customers');
                        return;
                    }

                    const customers = await response.json();

                    if (customers.length === 0) {
                        customerSuggestionsContainer.classList.add('hidden');
                        return;
                    }

                    // Build suggestions HTML
                    customerSuggestionsContainer.innerHTML = customers.map(customer => `
                        <div class="px-4 py-2 hover:bg-indigo-50 cursor-pointer border-b border-gray-100 last:border-b-0 text-sm"
                            onclick="selectCustomer('${customer.first_name}', '${customer.last_name}', ${customer.id})">
                            <div class="font-medium text-gray-900">${customer.full_name}</div>
                            <div class="text-xs text-gray-600">${customer.contact_no}</div>
                        </div>
                    `).join('');

                    customerSuggestionsContainer.classList.remove('hidden');
                } catch (error) {
                    console.error('Error fetching customers:', error);
                }
            }, 300);
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', function (e) {
            if (e.target !== customerNameInput && !customerSuggestionsContainer.contains(e.target)) {
                customerSuggestionsContainer.classList.add('hidden');
            }
        });
    }

    /**
     * Select customer from suggestions
     * Populates the customer name field with full name and stores customer ID in hidden field
     */
    function selectCustomer(firstName, lastName, customerId) {
        document.getElementById('customerName').value = `${firstName} ${lastName}`;
        document.getElementById('customerId').value = customerId;
        document.getElementById('customerSuggestions').classList.add('hidden');

        console.log('Customer selected:', {
            name: `${firstName} ${lastName}`,
            id: customerId
        });

        // Store customer ID in all order items (for reference)
        if (typeof orderItems !== 'undefined') {
            orderItems.forEach(item => {
                item.customerId = customerId;
            });
        }
    }

    /**
     * Test checkout data - sends form data to test endpoint
     */
    function testCheckoutData() {
        const form = document.getElementById('checkoutForm');
        const formData = new FormData(form);

        console.log('=== TESTING CHECKOUT DATA ===');
        console.log('Sending to: /debug/test-checkout');
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}: ${value}`);
        }

        fetch('/debug/test-checkout', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log('✓ Test endpoint response:', data);
                Swal.fire({
                    icon: 'success',
                    title: 'Test Successful',
                    html: `<p>Form data was received by backend!</p><p style="font-size: 0.8em; color: #666; margin-top: 10px;">Check browser console for details</p>`,
                    confirmButtonColor: '#10b981'
                });
            })
            .catch(error => {
                console.error('❌ Test error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Test Failed',
                    text: error.message,
                    confirmButtonColor: '#ef4444'
                });
            });
    }

    /**
     * Debug checkout - sends form data to debug endpoint
     */
    function debugCheckout() {
        const form = document.getElementById('checkoutForm');
        const formData = new FormData(form);

        console.log('=== DEBUG CHECKOUT ===');
        console.log('Sending to: /debug/checkout-test');
        console.log('Form data:');
        for (let [key, value] of formData.entries()) {
            console.log(`  ${key}: ${value}`);
        }

        fetch('/debug/checkout-test', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log('✓ Debug response:', data);
                Swal.fire({
                    icon: 'success',
                    title: 'Debug Checkout Sent',
                    html: `<p>Data sent to debug endpoint!</p><p style="font-size: 0.8em; color: #666; margin-top: 10px;">Check browser console and Laravel logs</p>`,
                    confirmButtonColor: '#10b981'
                });
            })
            .catch(error => {
                console.error('❌ Debug error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Debug Failed',
                    text: error.message,
                    confirmButtonColor: '#ef4444'
                });
            });
    }
</script>