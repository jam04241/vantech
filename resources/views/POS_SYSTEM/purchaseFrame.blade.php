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
                            <span class="text-red-500 font-medium w-24 text-right text-sm">-₱<span
                                    id="purchaseDiscountDisplay">0.00</span></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-200 pt-2 mt-2">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="font-bold text-gray-900 text-lg">₱<span
                                id="purchaseTotalDisplay">0.00</span></span>
                    </div>

                    <!-- Display Total Price Only Checkbox -->
                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-200">
                        <input type="checkbox" id="displayTotalOnly" name="displayTotalOnly"
                            class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 cursor-pointer" />
                        <label for="displayTotalOnly" class="text-sm text-gray-700 cursor-pointer">
                            Hide Unit & Subtotal Prices
                        </label>
                    </div>
                </div>

                <!-- Button -->
                <div class="mt-5">
                    <button onclick="openCheckoutModal()"
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

        <!-- Checkout Form -->
        <form id="checkoutForm" method="POST">
            @csrf

            <!-- Hidden fields for order data -->
            <input type="hidden" id="formCustomerId" name="customer_id" value="">
            <input type="hidden" id="formSubtotal" name="subtotal" value="0">
            <input type="hidden" id="formDiscount" name="discount" value="0">
            <input type="hidden" id="formTotal" name="total" value="0">
            <input type="hidden" id="formDisplayTotalOnly" name="displayTotalOnly" value="false">

            <!-- Items container for dynamic items -->
            <div id="formItemsContainer"></div>

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

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeCheckoutModal()"
                    class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition duration-150">
                    Cancel
                </button>
                <button type="button" onclick="validateAndSubmitCheckout()"
                    class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold transition duration-150 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M17.778 8.222c-4.296-4.296-11.26-4.296-15.556 0A1 1 0 01.808 6.808c5.076-5.077 13.308-5.077 18.384 0a1 1 0 01-1.414 1.414zM14.95 11.05a7 7 0 00-9.9 0 1 1 0 01-1.414-1.414 9 9 0 0112.728 0 1 1 0 01-1.414 1.414zM12.12 13.88a3 3 0 00-4.242 0 1 1 0 01-1.415-1.415 5 5 0 017.072 0 1 1 0 01-1.415 1.415zM9 16a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Complete Purchase
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
    const MIN_FETCH_INTERVAL = 300;

    /**
     * Fetch product from API endpoint by serial number
     */
    async function fetchProductFromAPI(serialNumber) {
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 5000);

            const response = await fetch(`/api/products/search-pos?serial=${encodeURIComponent(serialNumber)}`, {
                signal: controller.signal,
                headers: {
                    'Accept': 'application/json'
                }
            });

            clearTimeout(timeoutId);

            if (!response.ok) {
                console.error(`API Error: ${response.status} ${response.statusText}`);
                return null;
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                console.error('Invalid response type. Expected JSON, got:', contentType);
                return null;
            }

            const data = await response.json();

            if (!data || typeof data !== 'object') {
                console.error('Invalid response structure:', data);
                return null;
            }

            if (data.product && typeof data.product === 'object') {
                if (!data.product.id || !data.product.product_name) {
                    console.error('Product missing required fields:', data.product);
                    return null;
                }
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
     * Open checkout modal and prepare form data
     */
    function openCheckoutModal() {
        const checkoutModal = document.getElementById('checkoutModal');
        if (checkoutModal) {
            // Pre-fill amount with current total
            const totalAmount = document.getElementById('purchaseTotalDisplay').textContent;
            document.getElementById('amount').value = totalAmount;

            // Update hidden form fields
            document.getElementById('formSubtotal').value = document.getElementById('purchaseSubtotalDisplay').textContent;
            document.getElementById('formDiscount').value = document.getElementById('purchaseDiscountDisplay').textContent;
            document.getElementById('formTotal').value = totalAmount;
            document.getElementById('formDisplayTotalOnly').value = document.getElementById('displayTotalOnly').checked ? 'true' : 'false';

            // Prepare form items
            prepareFormItems();

            checkoutModal.classList.remove('hidden');
            checkoutModal.classList.add('flex');
        }
    }

    /**
     * Prepare form items for submission
     */
    function prepareFormItems() {
        const formItemsContainer = document.getElementById('formItemsContainer');
        formItemsContainer.innerHTML = '';

        const orderListItems = document.querySelectorAll('#purchaseOrderList li');

        orderListItems.forEach((li, index) => {
            const productId = li.getAttribute('data-product-id');
            const unitPrice = li.getAttribute('data-unit-price');
            const quantity = li.getAttribute('data-quantity');
            const totalPrice = li.getAttribute('data-total-price');
            const serialNumber = li.getAttribute('data-serial-number');

            if (productId && unitPrice && quantity && totalPrice && serialNumber) {
                const inputs = [
                    { name: `items[${index}][product_id]`, value: productId },
                    { name: `items[${index}][unit_price]`, value: unitPrice },
                    { name: `items[${index}][quantity]`, value: quantity },
                    { name: `items[${index}][total_price]`, value: totalPrice },
                    { name: `items[${index}][serial_number]`, value: serialNumber }
                ];

                inputs.forEach(input => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = input.name;
                    hiddenInput.value = input.value;
                    formItemsContainer.appendChild(hiddenInput);
                });
            }
        });
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
     * Handle barcode input with API lookup
     */
    document.addEventListener('DOMContentLoaded', function () {
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

                    // Check if serial number already exists in order
                    const existingOrderItems = typeof orderItems !== 'undefined' ? orderItems : [];
                    const isDuplicate = existingOrderItems.some(item => item.serialNumber === product.serial_number);

                    if (isDuplicate) {
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

                    // Add product to order
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
                        console.error('addItemToOrder function not found.');
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
                }, 300);
            });
        }
    });

    /**
     * Customer Name Autosuggestion
     */
    let customerSearchDebounceTimer = null;
    const customerNameInput = document.getElementById('customerName');
    const customerSuggestionsContainer = document.getElementById('customerSuggestions');

    if (customerNameInput) {
        customerNameInput.addEventListener('input', function (e) {
            clearTimeout(customerSearchDebounceTimer);
            const query = e.target.value.trim();

            if (query.length < 1) {
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
     */
    function selectCustomer(firstName, lastName, customerId) {
        document.getElementById('customerName').value = `${firstName} ${lastName}`;
        document.getElementById('formCustomerId').value = customerId;
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
     * Validate and submit checkout form with SweetAlert
     */
    function validateAndSubmitCheckout() {
        const customerName = document.getElementById('customerName').value;
        const customerId = document.getElementById('formCustomerId').value;
        const paymentMethod = document.getElementById('paymentMethod').value;
        const amount = document.getElementById('amount').value;

        // Validate form
        if (!customerId) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Customer',
                text: 'Please select a customer from the suggestions.',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        if (!paymentMethod) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Payment Method',
                text: 'Please select a payment method.',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        if (!amount || parseFloat(amount) <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Amount',
                text: 'Please enter a valid amount.',
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        // Check if there are items in the order
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

        // Show confirmation dialog
        Swal.fire({
            title: 'Confirm Purchase',
            html: `
                <div class="text-left">
                    <p class="font-semibold">Customer: ${customerName}</p>
                    <p>Payment Method: ${paymentMethod}</p>
                    <p>Amount: ₱${parseFloat(amount).toFixed(2)}</p>
                    <p class="text-sm text-gray-600 mt-2">This will:</p>
                    <ul class="text-sm text-gray-600 list-disc list-inside">
                        <li>Create customer purchase orders</li>
                        <li>Record payment method</li>
                        <li>Update inventory (mark products as sold)</li>
                        <li>Generate receipt</li>
                    </ul>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Process Purchase',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444'
        }).then((result) => {
            if (result.isConfirmed) {
                processCheckout();
            }
        });
    }

    /**
     * Process the checkout
     */
    function processCheckout() {
        const customerName = document.getElementById('customerName').value;
        const paymentMethod = document.getElementById('paymentMethod').value;
        const amount = document.getElementById('amount').value;

        // Prepare form data
        prepareFormItems();

        // Get form data
        const form = document.getElementById('checkoutForm');
        const formData = new FormData(form);

        // Show processing SweetAlert
        Swal.fire({
            title: 'Processing Purchase',
            html: `
                <div class="text-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto mb-4"></div>
                    <p class="font-semibold">Storing purchase data in database...</p>
                    <div class="mt-4 space-y-1 text-sm text-gray-600">
                        <p>✓ Validating data</p>
                        <p>⏳ Creating purchase records</p>
                        <p>⏳ Recording payment</p>
                        <p>⏳ Updating inventory</p>
                    </div>
                </div>
            `,
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Submit via AJAX
        fetch('{{ route("checkout.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success - show success message
                    showSuccessMessage(data);
                } else {
                    // Error - show error message
                    showErrorMessage(data);
                }
            })
            .catch(error => {
                console.error('Checkout error:', error);
                showNetworkError();
            });
    }

    /**
     * Show success message
     */
    function showSuccessMessage(data) {
        Swal.fire({
            icon: 'success',
            title: 'Purchase Completed!',
            html: `
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-800 mb-2">${data.message}</p>
                    <p class="text-sm text-gray-600 mb-4">All data has been successfully stored in the database.</p>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 mt-4 text-left">
                        <p class="text-sm text-green-800 font-medium">✓ Customer Purchase Orders Created</p>
                        <p class="text-sm text-green-800 font-medium">✓ Payment Method Recorded</p>
                        <p class="text-sm text-green-800 font-medium">✓ Inventory Updated (Products marked as sold)</p>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'View & Print Receipt',
            cancelButtonText: 'Back to POS',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                // User wants to view receipt - redirect to receipt page
                window.location.href = data.redirect_url;
            } else {
                // User wants to go back to POS - clear order and refresh
                clearOrderAndRefresh();
            }
        });
    }

    /**
     * Show error message
     */
    function showErrorMessage(data) {
        Swal.fire({
            icon: 'error',
            title: 'Database Storage Failed',
            html: `
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <p class="text-lg font-semibold text-gray-800 mb-2">Failed to Save Data</p>
                    <p class="text-sm text-gray-600 mb-4">${data.message}</p>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-4">
                        <p class="text-sm text-red-800">Please check the data and try again.</p>
                    </div>
                </div>
            `,
            confirmButtonText: 'Try Again',
            confirmButtonColor: '#ef4444'
        });
    }

    /**
     * Show network error
     */
    function showNetworkError() {
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Failed to connect to server. Please check your internet connection and try again.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#ef4444'
        });
    }

    /**
     * Clear order and refresh POS
     */
    function clearOrderAndRefresh() {
        // Clear order items
        if (typeof orderItems !== 'undefined') {
            orderItems = [];
            updateOrderDisplay();
        }

        // Close modal
        closeCheckoutModal();

        // Refresh the page to update product availability
        window.location.reload();
    }
</script>