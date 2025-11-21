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

        // Filter products by category, brand, and search query
        function filterProducts() {
            const categoryFilter = document.getElementById('categoryFilter').value;
            const brandFilter = document.getElementById('brandFilter').value;
            const searchQuery = document.getElementById('productSearch').value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                const cardBrand = card.getAttribute('data-brand');
                const productName = card.querySelector('h3').textContent.toLowerCase();
                const brandName = card.querySelector('p:nth-of-type(1)').textContent.toLowerCase();
                const typeName = card.querySelector('p:nth-of-type(2)').textContent.toLowerCase();

                const categoryMatch = categoryFilter === '' || categoryFilter === 'all' || cardCategory === categoryFilter;
                const brandMatch = brandFilter === '' || brandFilter === 'all' || cardBrand === brandFilter;
                const searchMatch = searchQuery === '' || productName.includes(searchQuery) || brandName.includes(searchQuery) || typeName.includes(searchQuery);

                if (categoryMatch && brandMatch && searchMatch) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
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
            const quotationList = document.getElementById('quotationOrderList');
            const emptyPurchaseMsg = document.getElementById('emptyOrderMsg');
            const emptyQuotationMsg = document.getElementById('emptyQuotationMsg');

            let html = '';
            let subtotal = 0;

            orderItems.forEach((item, index) => {
                const itemSubtotal = item.price * item.qty; // Price × Quantity
                subtotal += itemSubtotal;

                html += `
                                                                                                                                                <li class="py-3 px-3 hover:bg-gray-100 transition">
                                                                                                                                                    <div class="grid grid-cols-12 gap-1 items-center text-xs">
                                                                                                                                                        <div class="col-span-5">
                                                                                                                                                            <p class="font-medium text-gray-900 truncate">${item.name}</p>
                                                                                                                                                            <p class="text-gray-500 text-xs">SN: ${item.serialNumber}</p>
                                                                                                                                                        </div>
                                                                                                                                                        <div class="col-span-3 text-center">
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
            quotationList.innerHTML = html;

            // Show/hide empty messages
            emptyPurchaseMsg.style.display = orderItems.length === 0 ? 'block' : 'none';
            emptyQuotationMsg.style.display = orderItems.length === 0 ? 'block' : 'none';

            // Update subtotal
            document.getElementById('purchaseSubtotalDisplay').textContent = subtotal.toFixed(2);
            document.getElementById('quotationSubtotalDisplay').textContent = subtotal.toFixed(2);

            // Recalculate totals
            updatePurchaseTotal();
            updateQuotationTotal();
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
            const vat = (subtotal - discount) * 0.12;
            const total = subtotal - discount + vat;

            document.getElementById('purchaseDiscountDisplay').textContent = discount.toFixed(2);
            document.getElementById('purchaseVAT').textContent = vat.toFixed(2);
            document.getElementById('purchaseTotalDisplay').textContent = total.toFixed(2);
        }

        // Update Quotation Total
        function updateQuotationTotal() {
            const subtotal = parseFloat(document.getElementById('quotationSubtotalDisplay').textContent) || 0;
            const discount = parseFloat(document.getElementById('quotationDiscountInput').value) || 0;
            const vat = (subtotal - discount) * 0.12;
            const total = subtotal - discount + vat;

            document.getElementById('quotationDiscountDisplay').textContent = discount.toFixed(2);
            document.getElementById('quotationVAT').textContent = vat.toFixed(2);
            document.getElementById('quotationTotalDisplay').textContent = total.toFixed(2);
        }

        // Event listeners for filters and search
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('brandFilter').addEventListener('change', filterProducts);
        document.getElementById('productSearch').addEventListener('input', filterProducts);

        // Switch tab with confirmation if items exist
        function switchTabWithConfirm(tabName) {
            const scannedSerials = document.getElementById('scannedSerialNumbers').value.split(',').filter(s => s);

            // If no items scanned, just switch
            if (scannedSerials.length === 0) {
                switchTab(tabName);
                return;
            }

            // Show confirmation alert
            Swal.fire({
                icon: 'question',
                title: 'Switch Tab?',
                html: `<p>You have <strong>${scannedSerials.length}</strong> scanned product(s). Are you sure you want to switch tabs?</p>
                                                                                                                                           <p style="font-size: 0.9em; color: #666; margin-top: 10px;">Your current transaction will be removed.</p>`,
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Switch',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clear order items and scanned serials
                    orderItems = [];
                    document.getElementById('scannedSerialNumbers').value = '';
                    switchTab(tabName);
                    updateOrderDisplay();
                }
            });
        }

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
            const productSerialNo = document.getElementById('productSerialNo').value.trim();

            // Validate product serial number
            if (!productSerialNo && actionType === 'checkout') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Product Serial No. Required',
                    text: 'Please enter a product serial number before proceeding.',
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