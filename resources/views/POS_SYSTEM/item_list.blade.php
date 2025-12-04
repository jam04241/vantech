@extends('POS_SYSTEM.sidebar.app')

@section('title', 'POS')
@section('name', 'POS')
<style>
    .scrollbar-hide {
        overflow-y: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@section('content_items')
    <div class="flex items-center space-between gap-2 w-full sm:w-auto flex-1 flex-wrap" id="filter-container">

        {{-- Search Bar --}}
        <input type="text" id="productSearch" name="search" value="{{ request('search') }}"
            placeholder="Search products by name, brand, or category..." hx-get="{{ route('pos.itemlist') }}"
            hx-trigger="input changed delay:500ms, keyup[keyCode==13]" hx-target="#products-display"
            hx-include="#filter-container"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white flex-1 min-w-64" />

        {{-- Brand Filter --}}
        <select id="brandFilter" name="brand" hx-get="{{ route('pos.itemlist') }}" hx-trigger="change"
            hx-target="#products-display" hx-include="#filter-container"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" {{ request('brand') == '' ? 'selected' : '' }}>All Brands</option>
            @isset($brands)
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->brand_name }}
                    </option>
                @endforeach
            @endisset
        </select>

        {{-- Condition Filter --}}
        <select id="conditionFilter" name="condition" hx-get="{{ route('pos.itemlist') }}" hx-trigger="change"
            hx-target="#products-display" hx-include="#filter-container"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" {{ request('condition') == '' ? 'selected' : '' }}>All Conditions</option>
            <option value="Brand New" {{ request('condition') == 'Brand New' ? 'selected' : '' }}>Brand New</option>
            <option value="Second Hand" {{ request('condition') == 'Second Hand' ? 'selected' : '' }}>Second Hand</option>
        </select>

        <div class="gap-3">
            <button type="button" onclick="openAddCustomerModal()"
                class="px-4 py-2 bg-indigo-600 text-white border border-transparent rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                Add Customer
            </button>
        </div>
    </div>
    <div class="flex flex-col lg:flex-row gap-6 mt-6">

        <!-- LEFT SIDE: PRODUCTS DISPLAY (Updated with HTMX target) -->
        <div id="products-display">
            @include('POS_SYSTEM.display_productFrame')
        </div>

        <!-- RIGHT SIDE: PURCHASE ORDER FORM (Component) -->
        @include('POS_SYSTEM.purchaseFrame')
    </div>

    <!-- Add Customer Modal (Consistent with Customer_record.blade.php) -->
    <div id="addCustomerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 id="modalTitle" class="text-xl font-semibold text-gray-900">Add New Customer</h3>
                    <button type="button" onclick="closeAddCustomerModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="customerForm" class="space-y-4 mt-4">
                    @csrf
                    <input type="hidden" id="customerId" name="id">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name *</label>
                        <input type="text" id="firstName" name="first_name" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                        <input type="text" id="lastName" name="last_name" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contact Number *</label>
                        <input type="text" id="contactNo" name="contact_no" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gender *</label>
                        <select id="gender" name="gender" required
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Street</label>
                        <input type="text" id="street" name="street"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Barangay</label>
                        <input type="text" id="brgy" name="brgy"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">City/Province</label>
                        <input type="text" id="cityProvince" name="city_province"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" onclick="closeAddCustomerModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                            Save Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tab Switching Script -->
    <script>
        // Store order items
        let orderItems = [];



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
            // Calculate total without VAT
            const total = subtotal - discount;

            document.getElementById('purchaseDiscountDisplay').textContent = discount.toFixed(2);
            document.getElementById('purchaseTotalDisplay').textContent = total.toFixed(2);

            console.log('💰 PURCHASE TOTALS (Line 53-54):');
            console.log('   Subtotal: ₱' + subtotal.toFixed(2));
            console.log('   Total Price (after discount): ₱' + total.toFixed(2));
            console.log('   Discount: ₱' + discount.toFixed(2));
        }

        // Global addToCart function for product cards
        function addToCart(id, name, price, serial, brand, category, condition) {
            addProductToOrder(null, serial, name, price);
        }

        // Customer Modal Functions
        function openAddCustomerModal() {
            document.getElementById('addCustomerModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Add New Customer';
            document.getElementById('customerForm').reset();
            document.getElementById('customerId').value = '';
            document.getElementById('submitBtn').textContent = 'Save Customer';
        }

        function closeAddCustomerModal() {
            document.getElementById('addCustomerModal').classList.add('hidden');
        }

        // Customer Form Submission Handler
        document.getElementById('customerForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const customerId = document.getElementById('customerId').value;
            const isEditing = customerId !== '';

            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            // Create form data object
            const formData = {
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                contact_no: document.getElementById('contactNo').value,
                gender: document.getElementById('gender').value,
                street: document.getElementById('street').value,
                brgy: document.getElementById('brgy').value,
                city_province: document.getElementById('cityProvince').value,
                _token: document.querySelector('input[name="_token"]').value
            };

            const url = isEditing ? `/customers/${customerId}` : '/customers';
            const method = isEditing ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: isEditing ? 'Customer updated successfully!' : 'Customer added successfully!',
                    }).then(() => {
                        closeAddCustomerModal();
                        document.getElementById('customerForm').reset();
                    });
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        Object.values(data.errors).forEach(error => {
                            errorMessage += `• ${error[0]}\n`;
                        });
                        Swal.fire('Validation Error', errorMessage, 'error');
                    } else {
                        throw new Error(data.message || 'Failed to save customer');
                    }
                }
            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = isEditing ? 'Update Customer' : 'Save Customer';
            }
        });

        // Close modal when clicking outside
        document.getElementById('addCustomerModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeAddCustomerModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAddCustomerModal();
            }
        });

        // Auto-hide messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function () {
            // Success message
            const successMessage = document.getElementById('customerSuccessMessage');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
            }

            // Error message
            const errorMessage = document.getElementById('customerErrorMessage');
            if (errorMessage) {
                setTimeout(() => {
                    errorMessage.style.display = 'none';
                }, 5000);
            }

            // Validation errors
            const validationErrors = document.getElementById('validationErrors');
            if (validationErrors) {
                setTimeout(() => {
                    validationErrors.style.display = 'none';
                }, 5000);
            }
        });
    </script>
@endsection