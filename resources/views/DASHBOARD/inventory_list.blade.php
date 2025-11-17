@extends('SIDEBAR.layouts')
@section('title', 'Inventory List')
@section('name', 'Inventory List')
@section('content')
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
@endsection