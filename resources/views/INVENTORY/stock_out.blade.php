{{-- stock_out.blade.php --}}
@extends('SIDEBAR.layouts')
@section('title', 'Stock-Out')
@section('btn')
    <a href="{{ route('inventory.list') }}"
        class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </a>
@endsection
@section('name', 'STOCK-OUT MANAGEMENT')
@section('content')

    <div class="bg-white border rounded-lg p-6 shadow-sm">
        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Stock-Out Management</h2>
                <p class="text-gray-600 mt-1">Track and manage Sold Products</p>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Stock-Outs</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalStockOuts }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-xl shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Sold Products</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalSoldProducts }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-200 my-6"></div>

        {{-- Filter Section - Search, Sort and Print in single card --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6" id="filter-container">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <div class="lg:col-span-4">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" placeholder="Search by product, serial, brand, category..."
                            value="{{ $searchQuery }}" hx-get="{{ route('inventory.stock-out') }}"
                            hx-trigger="input changed delay:500ms" hx-target="#stockout-table"
                            hx-include="#filter-container" hx-swap="innerHTML"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" value="{{ $selectedDate ?? '' }}"
                        hx-get="{{ route('inventory.stock-out') }}" hx-trigger="change" hx-target="#stockout-table"
                        hx-include="#filter-container" hx-swap="innerHTML"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" hx-get="{{ route('inventory.stock-out') }}" hx-trigger="change"
                        hx-target="#stockout-table" hx-include="#filter-container" hx-swap="innerHTML"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white">
                        <option value="date_added_desc" {{ $currentSort == 'date_added_desc' ? 'selected' : '' }}>Latest Added
                        </option>
                        <option value="date_added_asc" {{ $currentSort == 'date_added_asc' ? 'selected' : '' }}>Oldest Added
                        </option>
                        <option value="brand_asc" {{ $currentSort == 'brand_asc' ? 'selected' : '' }}>Brand (A-Z)</option>
                        <option value="brand_desc" {{ $currentSort == 'brand_desc' ? 'selected' : '' }}>Brand (Z-A)</option>
                        <option value="category_asc" {{ $currentSort == 'category_asc' ? 'selected' : '' }}>Category (A-Z)
                        </option>
                        <option value="category_desc" {{ $currentSort == 'category_desc' ? 'selected' : '' }}>Category (Z-A)
                        </option>
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <button onclick="window.print()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white shadow hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 transition duration-150 text-sm font-medium text-gray-700 flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                </div>
            </div>
        </div>

        {{-- Stock-Out Table in Card Container --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div id="stockout-table">
                @include('INVENTORY.partials.stock_out_table', ['products' => $products])
            </div>
        </div>
    </div>
@endsection