@extends('SIDEBAR.layouts')
@section('title', 'Inventory')
@section('name', 'Inventory')
@section('content')


    <!-- Filters Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('inventory') }}"
            class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
            <!-- Search and Filters Container -->
            <div class="flex-1 w-full flex flex-col sm:flex-row gap-3">
                <!-- Search Input -->
                <div class="w-full sm:w-1/2">
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

                <!-- Category and Brand Filters -->
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-1/2">
                    <!-- Category Filter -->
                    <select name="category"
                        class="flex-2 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="">All Categories</option>
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
                        class="flex-2 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="">All Brands</option>
                        @isset($brands)
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->brand_name }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 w-full sm:w-auto">
                <a href="{{ route('product.add') }}"
                    class="bg-[#46647F] text-white px-4 py-2 rounded-lg hover:bg-[#3B4A5A] transition duration-150 text-sm font-medium flex items-center gap-2 whitespace-nowrap justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Product
                </a>
            </div>
        </form>
    </div>

        <!-- Product Table -->
            @include('partials.productTable_Inventory')


@endsection