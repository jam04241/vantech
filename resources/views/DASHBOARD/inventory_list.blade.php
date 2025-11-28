@extends('SIDEBAR.layouts')
@section('title', 'Inventory List')
@section('name', 'Inventory List')
@section('content')
    @php
        $activeSort = $currentSort ?? request('sort', 'name_asc');
    @endphp
    <div class="flex items-center gap-2 w-full sm:w-auto flex-1" id="filter-container">
        <input type="text" name="search" placeholder="Search inventory..." value="{{ request('search') }}"
            hx-get="{{ route('inventory.list') }}" hx-trigger="input changed delay:300ms, search"
            hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
            class="w-1/3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">

        <select name="category" hx-get="{{ route('inventory.list') }}" hx-trigger="change"
            hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" {{ request('category') == '' ? 'selected' : '' }}>All Categories</option>
            @isset($categories)
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            @endisset
        </select>

        <select name="brand" hx-get="{{ route('inventory.list') }}" hx-trigger="change" hx-target="#product-table-container"
            hx-include="#filter-container" hx-swap="innerHTML"
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

        {{-- Condition filter --}}
        <select name="condition" hx-get="{{ route('inventory.list') }}" hx-trigger="change"
            hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" {{ request('condition') == '' ? 'selected' : '' }}>All Conditions</option>
            <option value="Brand New" {{ request('condition') == 'Brand New' ? 'selected' : '' }}>Brand New</option>
            <option value="Second Hand" {{ request('condition') == 'Second Hand' ? 'selected' : '' }}>Second Hand</option>
        </select>

        {{-- Status filter --}}
        <select name="status" hx-get="{{ route('inventory.list') }}" hx-trigger="change"
            hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active Products</option>
            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived Products</option>
            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Products</option>
        </select>

        <select name="sort" hx-get="{{ route('inventory.list') }}" hx-trigger="change" hx-target="#product-table-container"
            hx-include="#filter-container" hx-swap="innerHTML"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="name_asc" {{ $activeSort === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
            <option value="name_desc" {{ $activeSort === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
            <option value="qty_desc" {{ $activeSort === 'qty_desc' ? 'selected' : '' }}>Quantity High-Low</option>
            <option value="qty_asc" {{ $activeSort === 'qty_asc' ? 'selected' : '' }}>Quantity Low-High</option>
            <option value="price_desc" {{ $activeSort === 'price_desc' ? 'selected' : '' }}>Price High-Low</option>
            <option value="price_asc" {{ $activeSort === 'price_asc' ? 'selected' : '' }}>Price Low-High</option>
            <option value="condition_new" {{ $activeSort === 'condition_new' ? 'selected' : '' }}>Brand New First</option>
            <option value="condition_used" {{ $activeSort === 'condition_used' ? 'selected' : '' }}>Second Hand First</option>
        </select>
    </div>

    <div class="py-6 rounded-xl" id="product-table-container">
        @include('partials.productTable_InventList', ['products' => $products, 'currentSort' => $currentSort])
    </div>

    {{-- Price Edit Modal --}}
    <div id="priceEditModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4 transition">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
            <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" data-price-modal-close>
                ✕
            </button>
            <h3 class="text-xl font-semibold text-gray-800 mb-4" id="priceEditModalTitle">Edit Price</h3>
            <p class="text-sm text-gray-500 mb-4" id="priceEditMeta"></p>
            <div id="priceEditWarning" class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                <p class="text-sm text-yellow-800" id="priceEditWarningText"></p>
            </div>
            <form id="priceEditForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (₱)</label>
                    <input type="number" name="price" step="0.01" min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        required>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                        data-price-modal-close>Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200">
                        Update Price
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let priceModalSetup = {
            backdropBound: false,
        };

        const setupPriceModal = () => {
            const modal = document.getElementById('priceEditModal');
            const form = document.getElementById('priceEditForm');
            const priceInput = form?.querySelector('[name="price"]');
            const meta = document.getElementById('priceEditMeta');
            const title = document.getElementById('priceEditModalTitle');
            const warning = document.getElementById('priceEditWarning');
            const warningText = document.getElementById('priceEditWarningText');

            const toggle = (show) => {
                if (!modal) return;
                if (show) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                } else {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    warning.classList.add('hidden');
                }
            };

            document.querySelectorAll('.edit-price-btn').forEach((btn) => {
                if (btn.dataset.bound === 'true') {
                    return;
                }
                btn.dataset.bound = 'true';
                btn.addEventListener('click', () => {
                    const payload = JSON.parse(btn.dataset.product || '{}');
                    form.action = btn.dataset.action;
                    priceInput.value = payload.price ?? 0;

                    // Set modal content based on product condition
                    if (payload.product_condition === 'Brand New') {
                        title.textContent = 'Edit Price - Brand New Product Group';
                        meta.textContent = `${payload.product_name} • ${payload.brand_name} • ${payload.category_name}`;
                        warningText.innerHTML = `This will update the price for <strong>ALL ${payload.quantity} Brand New "${payload.product_name}" products</strong>.<br><br>All products with the same name, brand, and category will be updated to the new price.`;
                        warning.classList.remove('hidden');
                    } else {
                        title.textContent = 'Edit Price - Second Hand Product Group';
                        meta.textContent = `${payload.product_name} • ${payload.brand_name} • ${payload.category_name}`;
                        if (payload.quantity > 1) {
                            warningText.innerHTML = `This will update the price for <strong>ALL ${payload.quantity} Second Hand "${payload.product_name}" products</strong> that currently have the same price (₱${parseFloat(payload.price).toLocaleString('en-PH', { minimumFractionDigits: 2 })}).<br><br>Only products with the same current price will be updated.`;
                        } else {
                            warningText.innerHTML = `This will update the price for this specific Second Hand "${payload.product_name}" product.`;
                        }
                        warning.classList.remove('hidden');
                    }

                    toggle(true);
                });
            });

            document.querySelectorAll('[data-price-modal-close]').forEach((btn) => {
                if (btn.dataset.bound === 'true') {
                    return;
                }
                btn.dataset.bound = 'true';
                btn.addEventListener('click', () => toggle(false));
            });

            if (!priceModalSetup.backdropBound) {
                window.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        toggle(false);
                    }
                });
                priceModalSetup.backdropBound = true;
            }

            // Add SweetAlert confirmation for price update
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const form = this;
                const price = priceInput.value;
                const payload = JSON.parse(document.querySelector('.edit-price-btn[data-bound="true"]')?.dataset.product || '{}');
                const isBrandNew = payload.product_condition === 'Brand New';

                let confirmMessage, confirmTitle, confirmIcon;

                if (isBrandNew) {
                    confirmTitle = 'Update Price for All Brand New Products';
                    confirmMessage = `You are about to change the price for <strong>ALL ${payload.quantity} Brand New "${payload.product_name}" products</strong> to <strong>₱${parseFloat(price).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</strong>.<br><br>This will affect ${payload.quantity} products. Are you sure you want to continue?`;
                    confirmIcon = 'warning';
                } else {
                    if (payload.quantity > 1) {
                        confirmTitle = 'Update Price for Second Hand Product Group';
                        confirmMessage = `You are about to change the price for <strong>${payload.quantity} Second Hand "${payload.product_name}" products</strong> from ₱${parseFloat(payload.price).toLocaleString('en-PH', { minimumFractionDigits: 2 })} to <strong>₱${parseFloat(price).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</strong>.<br><br>This will affect ${payload.quantity} products that currently have the same price.`;
                        confirmIcon = 'warning';
                    } else {
                        confirmTitle = 'Update Price for Second Hand Product';
                        confirmMessage = `You are about to change the price for this Second Hand "${payload.product_name}" from ₱${parseFloat(payload.price).toLocaleString('en-PH', { minimumFractionDigits: 2 })} to <strong>₱${parseFloat(price).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</strong>.`;
                        confirmIcon = 'question';
                    }
                }

                Swal.fire({
                    title: confirmTitle,
                    html: confirmMessage,
                    icon: confirmIcon,
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Update Price!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state on the modal submit button
                        const submitBtn = form.querySelector('button[type="submit"]');
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = `
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Updating...
                                `;
                        submitBtn.disabled = true;

                        form.submit();
                    }
                });
            });
        };

        // SweetAlert for archive/unarchive forms
        const setupArchiveForms = () => {
            // Archive buttons
            document.querySelectorAll('.archive-btn').forEach((button) => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const form = this.closest('form');
                    const productName = this.getAttribute('data-product-name');

                    Swal.fire({
                        title: 'Archive Product',
                        html: `Are you sure you want to archive <strong>"${productName}"</strong>?<br><br>
                                          <div class="text-left text-sm text-gray-600 mt-2">
                                              <span class="font-semibold">This will:</span>
                                              <ul class="list-disc list-inside mt-1 space-y-1">
                                                  <li>Make the product unavailable for POS</li>
                                                  <li>Prevent new stock from being added</li>
                                                  <li>Move the product to archived products</li>
                                              </ul>
                                          </div>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#eab308',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, Archive it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'px-4 py-2 rounded-lg',
                            cancelButton: 'px-4 py-2 rounded-lg'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            const originalText = button.innerHTML;
                            button.innerHTML = `
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Archiving...
                                        `;
                            button.disabled = true;

                            form.submit();
                        }
                    });
                });
            });

            // Unarchive buttons
            document.querySelectorAll('.unarchive-btn').forEach((button) => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const form = this.closest('form');
                    const productName = this.getAttribute('data-product-name');

                    Swal.fire({
                        title: 'Unarchive Product',
                        html: `Are you sure you want to unarchive <strong>"${productName}"</strong>?<br><br>
                                          <div class="text-left text-sm text-gray-600 mt-2">
                                              <span class="font-semibold">This will:</span>
                                              <ul class="list-disc list-inside mt-1 space-y-1">
                                                  <li>Make the product available for POS again</li>
                                                  <li>Allow new stock to be added</li>
                                                  <li>Move the product back to active products</li>
                                              </ul>
                                          </div>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#22c55e',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, Unarchive it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            confirmButton: 'px-4 py-2 rounded-lg',
                            cancelButton: 'px-4 py-2 rounded-lg'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            const originalText = button.innerHTML;
                            button.innerHTML = `
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Unarchiving...
                                        `;
                            button.disabled = true;

                            form.submit();
                        }
                    });
                });
            });
        };

        document.addEventListener('DOMContentLoaded', () => {
            setupPriceModal();
            setupArchiveForms();
        });

        document.body.addEventListener('htmx:afterSwap', (event) => {
            if (event.target.id === 'product-table-container') {
                setupPriceModal();
                setupArchiveForms();
            }
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#4F46E5',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#E11D48',
                customClass: {
                    popup: 'rounded-2xl'
                }
            });
        </script>
    @endif
@endsection