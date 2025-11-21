<!-- 
    RECEIPT WITH TAB SWITCHER 
    Logic: Handles barcode scanning and order management
    Basis: Serial number for scanning, but groups items by product name in order list
    Key Feature: Multiple serials of same product increase quantity (prevents duplicate lines)
    Example: Scan Dell Monitor ABC123 (Qty: 1) + Dell Monitor XYZ789 (Qty: 2 - same line)
-->
<div class="container w-full lg:w-1/2">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden h-fit sticky top-6">
        <!-- Barcode Input -->
        <div class="p-4 bg-gradient-to-r from-indigo-50 to-blue-50">
            <label class="block text-xs font-semibold text-gray-700 mb-2">Scan Barcode / Serial No.</label>
            <input type="text" id="productSerialNo" placeholder="Scan product barcode here..."
                class="w-full px-4 py-3 border-2 border-indigo-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 transition duration-150 ease-in-out text-sm font-medium"
                autofocus />
            <input type="hidden" id="scannedSerialNumbers" />
            <p class="text-xs text-gray-500 mt-2">Auto-input enabled • Automatic calculation</p>
        </div>
        <!-- Tab Buttons -->
        <div class="flex border-b border-gray-200">
            <button onclick="switchTabWithConfirm('purchase')" id="purchaseTab"
                class="flex-1 py-3 px-4 text-center font-semibold transition-all duration-200 border-b-2 border-indigo-600 text-indigo-600 bg-white">
                Purchase
            </button>
            <button onclick="switchTabWithConfirm('quotation')" id="quotationTab"
                class="flex-1 py-3 px-4 text-center font-semibold transition-all duration-200 border-b-2 border-transparent text-gray-500 bg-gray-50 hover:bg-gray-100">
                Quotation
            </button>
        </div>

        <!-- Tab Content Container -->
        <div class="p-6">

            <!-- PURCHASE TAB CONTENT -->
            <div id="purchaseContent" class="tab-content">
                <h2 class="text-xl font-extrabold text-gray-900 mb-4 border-b pb-2"> Purchase</h2>

                <!-- Customer Info -->
                <div class="mb-4 text-sm">
                    <p class="text-gray-700">Order #: <span class="font-semibold text-gray-900">Serial Number</span>
                    </p>
                    <p class="text-gray-700">Date: <span
                            class="font-semibold text-gray-900">{{ now()->format('M d, Y h:i A') }}</span></p>
                </div>

                <!-- Order List -->
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Order Items</h3>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        <div
                            class="grid grid-cols-12 gap-1 px-3 py-2 bg-gray-100 text-xs font-semibold text-gray-700 border-b border-gray-200">
                            <div class="col-span-5">Product</div>
                            <div class="col-span-3 text-center">Price</div>
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
                <div class="border-t border-gray-300 pt-4 text-sm space-y-3">
                    <div class="flex justify-between items-center bg-blue-50 px-3 py-2 rounded">
                        <span class="text-gray-700 font-medium">Subtotal</span>
                        <span class="font-bold text-gray-900 text-lg">₱<span
                                id="purchaseSubtotalDisplay">0.00</span></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Discount</span>
                        <div class="flex gap-2 items-center">
                            <input type="number" id="purchaseDiscountInput" value="0"
                                class="w-20 px-2 py-1 border border-gray-300 rounded text-right focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                step="0.01" oninput="updatePurchaseTotal()" placeholder="0.00" />
                            <span class="text-red-500 font-medium w-24 text-right">-₱<span
                                    id="purchaseDiscountDisplay">0.00</span></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">VAT (12%)</span>
                        <span class="font-medium text-gray-900">₱<span id="purchaseVAT">0.00</span></span>
                    </div>
                    <div
                        class="flex justify-between font-extrabold text-gray-900 text-lg border-t-2 border-indigo-300 pt-3 mt-3 bg-gradient-to-r from-indigo-50 to-blue-50 px-3 py-2 rounded">
                        <span>Total</span>
                        <span>₱<span id="purchaseTotalDisplay">0.00</span></span>
                    </div>
                </div>

                <!-- Button -->
                <div class="mt-5">
                    <button id="checkout-btn"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 110 2h-3V6a1 1 0 011-1v3a1 1 0 011 1H10a1 1 0 01-1 1H7a1 1 0 00-1 1h-3V11a1 1 0 00 1-1V7a1 1 0 011-1h2V3a2 2 0 012 2z" />
                        </svg>
                        Proceed to Checkout
                    </button>
                </div>
            </div>

            <!-- QUOTATION TAB CONTENT -->
            <div id="quotationContent" class="tab-content hidden">
                <h2 class="text-xl font-extrabold text-gray-900 mb-4 border-b pb-2"> Quotation</h2>

                <!-- Customer Info (No Customer Name) -->
                <div class="mb-4 text-sm">
                    <p class="text-gray-700">Order #: <span class="font-semibold text-gray-900">Serial Number</span>
                    </p>
                    <p class="text-gray-700">Date: <span
                            class="font-semibold text-gray-900">{{ now()->format('M d, Y h:i A') }}</span></p>
                </div>

                <!-- Order List -->
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Order Items</h3>
                    <div class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden">
                        <div
                            class="grid grid-cols-12 gap-1 px-3 py-2 bg-gray-100 text-xs font-semibold text-gray-700 border-b border-gray-200">
                            <div class="col-span-5">Product</div>
                            <div class="col-span-3 text-center">Price</div>
                            <div class="col-span-3 text-right">Subtotal</div>
                            <div class="col-span-1 text-center">Remove</div>
                        </div>
                        <ul id="quotationOrderList"
                            class="divide-y divide-gray-200 max-h-56 overflow-y-auto scrollbar-hide">
                            <!-- Items will be added dynamically here -->
                        </ul>
                    </div>
                    <p id="emptyQuotationMsg" class="text-center text-gray-500 text-sm py-4">No items added yet.
                        Scan a barcode to start.</p>
                </div>

                <!-- Summary -->
                <div class="border-t border-gray-300 pt-4 text-sm space-y-3">
                    <div class="flex justify-between items-center bg-blue-50 px-3 py-2 rounded">
                        <span class="text-gray-700 font-medium">Subtotal</span>
                        <span class="font-bold text-gray-900 text-lg">₱<span
                                id="quotationSubtotalDisplay">0.00</span></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">Discount</span>
                        <div class="flex gap-2 items-center">
                            <input type="number" id="quotationDiscountInput" value="0"
                                class="w-20 px-2 py-1 border border-gray-300 rounded text-right focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                step="0.01" oninput="updateQuotationTotal()" placeholder="0.00" />
                            <span class="text-red-500 font-medium w-24 text-right">-₱<span
                                    id="quotationDiscountDisplay">0.00</span></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700">VAT (12%)</span>
                        <span class="font-medium text-gray-900">₱<span id="quotationVAT">0.00</span></span>
                    </div>
                    <div
                        class="flex justify-between font-extrabold text-gray-900 text-lg border-t-2 border-green-300 pt-3 mt-3 bg-gradient-to-r from-green-50 to-emerald-50 px-3 py-2 rounded">
                        <span>Total</span>
                        <span>₱<span id="quotationTotalDisplay">0.00</span></span>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-5 flex flex-col gap-2">
                    <button id="print-quotation-btn"
                        class="w-full bg-green-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-green-700 transition duration-150 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"
                                clip-rule="evenodd" />
                        </svg>
                        Proceed to Print Quotation
                    </button>
                    <button id="add-pc-build-btn"
                        class="w-full bg-indigo-600 text-white py-3 rounded-lg text-base font-semibold shadow hover:bg-indigo-700 transition duration-150 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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

<!-- POS Product Lookup Script (Serial Number Search) -->
<script>
    /**
     * Fetch product from API endpoint by serial number
     * Used when purchaseFrame is used independently
     * Basis: Search by serial_number, then group by name, brand, category, condition, price
     */
    async function fetchProductFromAPI(serialNumber) {
        try {
            const response = await fetch(`/api/products/search-pos?serial=${encodeURIComponent(serialNumber)}`);
            const data = await response.json();

            if (data.product) {
                return data.product;
            } else {
                console.warn('Product not found:', data.message);
                return null;
            }
        } catch (error) {
            console.error('Error fetching product:', error);
            return null;
        }
    }

    /**
     * Handle barcode input with API lookup
     * This script runs when purchaseFrame is used as a standalone component
     * Searches by serial number and groups by product attributes
     */
    document.addEventListener('DOMContentLoaded', function () {
        const productSerialInput = document.getElementById('productSerialNo');

        if (productSerialInput) {
            productSerialInput.addEventListener('input', async function (e) {
                const serialNumber = this.value.trim();

                if (!serialNumber) return;

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

                // Check if product group already in order (by product ID)
                const scannedProductIds = document.getElementById('scannedSerialNumbers').value.split(',').filter(s => s);
                if (scannedProductIds.includes(product.id.toString())) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Product Already Added',
                        html: `<p>Product <strong>${product.product_name}</strong> is already in the order</p>`,
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
                    addItemToOrder(product);
                } else {
                    console.warn('addItemToOrder function not found. Make sure item_list.blade.php is loaded.');
                }

                // Clear input
                this.value = '';
                this.focus();
            });
        }
    });
</script>