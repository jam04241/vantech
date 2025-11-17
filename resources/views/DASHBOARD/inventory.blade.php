@extends('SIDEBAR.layouts')
@section('title', 'Inventory')
@section('name', 'Inventory')
@section('content')

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
            </div>
            <div>
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

@endsection