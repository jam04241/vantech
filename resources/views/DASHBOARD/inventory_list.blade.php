@extends('SIDEBAR.layouts')
@section('title', 'Inventory')
@section('name', 'Inventory')
@section('content')
        <!-- Header Section -->
        <div class="mb-6">

    <!-- Stats and Actions Container -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Quick Stats -->
        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="bg-white rounded-lg p-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Products</p>
                        <p class="text-xl font-light text-gray-900 mt-1">{{ $totalProducts ?? '0' }}</p>
                    </div>
                    <div class="text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Low Stock</p>
                        <p class="text-xl font-light text-gray-900 mt-1">{{ $lowStockCount ?? '0' }}</p>
                    </div>
                    <div class="text-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Out of Stock</p>
                        <p class="text-xl font-light text-gray-900 mt-1">{{ $outOfStockCount ?? '0' }}</p>
                    </div>
                    <div class="text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="flex justify-center lg:justify-end">
            <a href="{{ route('inventory.stockout') }}"
                class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition duration-150 text-sm font-normal flex items-center gap-2 justify-center">
                View Stock-Out
            </a>
        </div>
    </div>
            <!-- Filters Form -->
            <form method="GET" action="{{ route('inventory.list') }}" class="bg-white rounded-lg border border-gray-200 p-4 mb-6 mt-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Search -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <select name="category"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="" {{ request('category') == '' ? 'selected' : '' }}>All Categories</option>
                        @isset($categories)
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        @endisset
                    </select>

                    <!-- Brand Filter -->
                    <select name="brand"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="" {{ request('brand') == '' ? 'selected' : '' }}>All Brands</option>
                        @isset($brands)
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->brand_name }}
                                </option>
                            @endforeach
                        @endisset
                    </select>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <button type="button"
                            class="px-3 py-2 border border-gray-300 rounded-lg bg-white hover:bg-gray-50 transition duration-150 text-sm flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print
                        </button>
                        <button type="button"
                            class="bg-green-600 px-3 py-2 rounded-lg hover:bg-green-700 transition duration-150 text-sm flex items-center gap-1 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>
            </form>
            <div class="py-4">
                @include('partials.productTable_InventList')
            </div>
@endsection