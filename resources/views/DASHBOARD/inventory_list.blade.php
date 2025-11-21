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

        {{-- ADDED: Condition filter --}}
        <select name="condition" hx-get="{{ route('inventory.list') }}" hx-trigger="change"
            hx-target="#product-table-container" hx-include="#filter-container" hx-swap="innerHTML"
            class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
            <option value="" {{ request('condition') == '' ? 'selected' : '' }}>All Conditions</option>
            <option value="Brand New" {{ request('condition') == 'Brand New' ? 'selected' : '' }}>Brand New</option>
            <option value="Second Hand" {{ request('condition') == 'Second Hand' ? 'selected' : '' }}>Second Hand</option>
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
            {{-- ADDED: Condition sorting --}}
            <option value="condition_new" {{ $activeSort === 'condition_new' ? 'selected' : '' }}>Brand New First</option>
            <option value="condition_used" {{ $activeSort === 'condition_used' ? 'selected' : '' }}>Second Hand First</option>
        </select>
        <button class="px-4 py-2 border rounded-lg bg-white flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v4H6v-4z" />
            </svg>
            Print
        </button>
        <button class="px-4 py-2 border rounded-lg bg-white flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v4H6v-4z" />
            </svg>
            Export
        </button>
    </div>
    <div class="py-6 rounded-xl" id="product-table-container">
        @include('partials.productTable_InventList')
    </div>

    {{-- Price Edit Modal --}}
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

    <script>
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

        document.addEventListener('DOMContentLoaded', setupPriceModal);
        document.body.addEventListener('htmx:afterSwap', (event) => {
            if (event.target.id === 'product-table-container') {
                setupPriceModal();
            }
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