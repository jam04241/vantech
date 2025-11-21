@extends('SIDEBAR.layouts')
@section('title', 'Inventory')
@section('name', 'Inventory')
@section('content')
    @php
        $activeSort = $currentSort ?? request('sort', 'name_asc');
    @endphp
    {{-- <div class="flex items-center gap-3 mb-4">
        <a href="#" class="px-4 py-2 rounded-lg bg-gray-600 text-white font-medium shadow ">
            Brand History
        </a>
        <a href="#" class="px-4 py-2 rounded-lg bg-gray-600 text-white font-medium shadow">
            Categories History
        </a>
    </div> --}}

    <div class="py-6 rounded-xl">
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto flex-1" id="filter-container">
                {{-- SEARCH BAR --}}
                <input type="text" name="search" placeholder="Search inventory..." value="{{ request('search') }}"
                    hx-get="{{ route('inventory') }}" hx-trigger="input changed delay:300ms, search"
                    hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
                    class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">

                <select name="category" hx-get="{{ route('inventory') }}" hx-trigger="change"
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

                <select name="brand" hx-get="{{ route('inventory') }}" hx-trigger="change"
                    hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
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

                {{-- ADDED: Condition filter --}}
                <select name="condition" hx-get="{{ route('inventory') }}" hx-trigger="change"
                    hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
                    <option value="" {{ request('condition') == '' ? 'selected' : '' }}>All Conditions</option>
                    <option value="Brand New" {{ request('condition') == 'Brand New' ? 'selected' : '' }}>Brand New</option>
                    <option value="Second Hand" {{ request('condition') == 'Second Hand' ? 'selected' : '' }}>Second Hand
                    </option>
                </select>

                {{-- ADDED: Supplier filter --}}
                <select name="supplier" hx-get="{{ route('inventory') }}" hx-trigger="change"
                    hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
                    <option value="" {{ request('supplier') == '' ? 'selected' : '' }}>All Suppliers</option>
                    @isset($suppliers)
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->supplier_name }}
                            </option>
                        @endforeach
                    @endisset
                </select>

                <select name="sort" hx-get="{{ route('inventory') }}" hx-trigger="change"
                    hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
                    <option value="name_asc" {{ $activeSort === 'name_asc' ? 'selected' : '' }}>A-Z</option>
                    <option value="name_desc" {{ $activeSort === 'name_desc' ? 'selected' : '' }}>Z-A</option>
                    {{-- ADDED: Condition sorting --}}
                    <option value="condition_new" {{ $activeSort === 'condition_new' ? 'selected' : '' }}>Brand New First
                    </option>
                    <option value="condition_used" {{ $activeSort === 'condition_used' ? 'selected' : '' }}>Second Hand First
                    </option>
                </select>
            </div>
            <div class="flex flex-wrap gap-2 justify-end">
                <button type="button" id="openBrandEditor"
                    class="px-4 py-2 border border-gray-300 rounded-lg bg-white shadow hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out whitespace-nowrap">
                    Edit Brand
                </button>
                <button type="button" id="openCategoryEditor"
                    class="px-4 py-2 border border-gray-300 rounded-lg bg-white shadow hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out whitespace-nowrap">
                    Edit Category
                </button>
                <a id="addProductBtn" href="{{ route('product.add') }}"
                    class="bg-[#46647F] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out whitespace-nowrap">
                    Add Product
                </a>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div id="product-table-container">
        @include('partials.productTable_Inventory')
    </div>

    {{-- ================= EDIT MODALS ================= --}}
    {{-- ADDED: Price Edit Modal --}}
    <div id="priceEditModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4 transition">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
            <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" data-price-modal-close>
                ✕
            </button>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Edit Price</h3>
            <p class="text-sm text-gray-500 mb-4" id="priceEditMeta"></p>
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
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="productEditModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4 transition">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl p-8 relative">
            <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" data-modal-close>
                ✕
            </button>
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">Edit Product</h3>
            <form id="productEditForm" method="POST">
                @csrf
                @method('PUT')
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Product Name</label>
                        <input type="text" name="product_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Serial Number (Read Only)</label>

                        <input type="text" name="serial_number"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select name="brand_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            required>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Warranty</label>
                            <input type="text" name="warranty_period"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                        </div>

                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price (₱)</label>
                        <input type="number" name="price" step="0.01" min="0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                        <select name="supplier_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            required>
                            @foreach ($suppliers ?? collect() as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->company_name ?? $supplier->supplier_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="flex justify-end gap-3">
                    <button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                        data-modal-close>Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">Save
                        Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div id="brandEditModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4 transition">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
            <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" data-modal-close>
                ✕
            </button>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Edit Brand</h3>
            <form id="brandEditForm" method="POST" data-action-base="{{ url('/brands') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Brand</label>
                        <select id="brandEditSelector"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand Name</label>
                        <input type="text" name="brand_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            required>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                        data-modal-close>Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">Update
                        Brand</button>
                </div>
            </form>
        </div>
    </div>

    <div id="categoryEditModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4 transition">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
            <button type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" data-modal-close>
                ✕
            </button>
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Edit Category</h3>
            <form id="categoryEditForm" method="POST" data-action-base="{{ url('/categories') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Category</label>
                        <select id="categoryEditSelector"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                        <input type="text" name="category_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            required>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300"
                        data-modal-close>Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">Update
                        Category</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleModal = (modal, show) => {
                if (!modal) return;
                if (show) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                } else {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            };

            const productModal = document.getElementById('productEditModal');
            const productForm = document.getElementById('productEditForm');
            // ============= PRODUCT FORM FIELDS (PRICE NOW INCLUDED) =============
            const productFields = {
                product_name: productForm.querySelector('[name="product_name"]'),
                serial_number: productForm.querySelector('[name="serial_number"]'),
                brand_id: productForm.querySelector('[name="brand_id"]'),
                category_id: productForm.querySelector('[name="category_id"]'),
                supplier_id: productForm.querySelector('[name="supplier_id"]'),
                warranty_period: productForm.querySelector('[name="warranty_period"]'),
                price: productForm.querySelector('[name="price"]'),
            };
            // ============= END PRODUCT FORM FIELDS =============

            const bindProductButtons = () => {
                document.querySelectorAll('[data-product-modal]').forEach(button => {
                    if (button.dataset.bound === 'true') {
                        return;
                    }
                    button.dataset.bound = 'true';
                    button.addEventListener('click', () => {
                        try {
                            const payload = JSON.parse(button.dataset.product);
                            productForm.action = button.dataset.action;
                            productFields.product_name.value = payload.product_name || '';
                            productFields.serial_number.value = payload.serial_number || '';
                            productFields.brand_id.value = payload.brand_id || '';
                            productFields.category_id.value = payload.category_id || '';
                            productFields.supplier_id.value = payload.supplier_id || '';
                            productFields.warranty_period.value = payload.warranty_period || '';
                            productFields.price.value = payload.price || 0;
                            toggleModal(productModal, true);
                        } catch (error) {
                            console.error('Unable to parse product payload', error);
                        }
                    });
                });
            };

            bindProductButtons();

            document.body.addEventListener('htmx:afterSwap', (event) => {
                if (event.target.id === 'product-table-container') {
                    bindProductButtons();
                }
            });

            document.getElementById('openBrandEditor')?.addEventListener('click', () => {
                toggleModal(document.getElementById('brandEditModal'), true);
                syncBrandForm();
            });

            document.getElementById('openCategoryEditor')?.addEventListener('click', () => {
                toggleModal(document.getElementById('categoryEditModal'), true);
                syncCategoryForm();
            });

            document.querySelectorAll('[data-modal-close]').forEach(button => {
                button.addEventListener('click', () => {
                    toggleModal(button.closest('.fixed'), false);
                });
            });

            window.addEventListener('click', (event) => {
                document.querySelectorAll('.fixed[id$="Modal"]').forEach(modal => {
                    if (event.target === modal) {
                        toggleModal(modal, false);
                    }
                });
            });

            const syncBrandForm = () => {
                const form = document.getElementById('brandEditForm');
                const selector = document.getElementById('brandEditSelector');
                if (!form || !selector) return;
                const selected = selector.selectedOptions[0];
                if (!selected) return;
                form.action = `${form.dataset.actionBase}/${selected.value}`;
                form.querySelector('[name="brand_name"]').value = selected.textContent.trim();
            };

            const syncCategoryForm = () => {
                const form = document.getElementById('categoryEditForm');
                const selector = document.getElementById('categoryEditSelector');
                if (!form || !selector) return;
                const selected = selector.selectedOptions[0];
                if (!selected) return;
                form.action = `${form.dataset.actionBase}/${selected.value}`;
                form.querySelector('[name="category_name"]').value = selected.textContent.trim();
            };

            document.getElementById('brandEditSelector')?.addEventListener('change', syncBrandForm);
            document.getElementById('categoryEditSelector')?.addEventListener('change', syncCategoryForm);

            // ADDED: Price modal setup
            let priceModalSetup = {
                backdropBound: false,
            };

            const setupPriceModal = () => {
                const modal = document.getElementById('priceEditModal');
                const form = document.getElementById('priceEditForm');
                const priceInput = form?.querySelector('[name="price"]');
                const meta = document.getElementById('priceEditMeta');

                const toggle = (show) => {
                    if (!modal) return;
                    if (show) {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    } else {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
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
                        meta.textContent = `${payload.product_name ?? ''} • ${payload.brand_name ?? ''} • ${payload.category_name ?? ''}`;
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
            };

            setupPriceModal();

            document.body.addEventListener('htmx:afterSwap', (event) => {
                if (event.target.id === 'product-table-container') {
                    setupPriceModal();
                }
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                confirmButtonColor: '#4F46E5'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#E11D48'
            });
        </script>
    @endif

@endsection