@extends('SIDEBAR.layouts')

@section('title', 'Manage Suppliers')

@section('name', 'SUPPLIERS')

@section('content')
    <div class="bg-white border rounded-lg p-6 shadow-sm">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            {{-- Search Form --}}
            <form method="GET" action="{{ route('suppliers') }}" class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search suppliers by name, company, phone, or address..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                        aria-label="Search suppliers">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if(request('search'))
                        <a href="{{ route('suppliers') }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition duration-200"
                            title="Clear search">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>

            <button id="openSupplierModal"
                class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg font-medium hover:shadow-xl transform hover:-translate-y-0.5"
                aria-label="Add a new supplier">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Supplier
            </button>
        </div>

        <div class="border-t border-gray-200 my-6"></div>

        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Supplier Management</h2>
                <p class="text-gray-600 mt-1">Manage your suppliers and their information</p>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div
                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Suppliers</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalSuppliers }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Active</p>
                        <p class="text-2xl font-bold text-gray-900" id="activeCount">{{ $activeCount }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border border-red-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-red-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Inactive</p>
                        <p class="text-2xl font-bold text-gray-900" id="inactiveCount">{{ $inactiveCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Suppliers Table --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100 text-gray-700 text-base">
                        <tr>
                            <th class="p-4 font-semibold text-center">#</th>
                            <th class="p-4 font-semibold text-center">Supplier Info</th>
                            <th class="p-4 font-semibold text-center">Contact</th>
                            <th class="p-4 font-semibold text-center">Address</th>
                            <th class="p-4 font-semibold text-center">Status</th>
                            <th class="p-4 font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 text-center">
                        @forelse($suppliers as $supplier)
                                        <tr class="hover:bg-gray-50 transition duration-150 group" id="supplier-{{ $supplier->id }}">
                                            {{-- Number --}}
                                            <td class="p-4 text-gray-900 font-medium text-lg">
                                                {{ ($suppliers->currentPage() - 1) * $suppliers->perPage() + $loop->iteration }}
                                            </td>

                                            {{-- Supplier Info --}}
                                            <td class="p-4">
                                                <div class="flex items-center justify-start">
                                                    <div
                                                        class="h-14 w-14 bg-indigo-500 text-white text-lg font-bold rounded-lg flex items-center justify-center">
                                                        {{ substr($supplier->supplier_name, 0, 1) }}
                                                    </div>
                                                    <div class="ml-5 text-left">
                                                        <div
                                                            class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition duration-200">
                                                            {{ $supplier->supplier_name }}
                                                        </div>
                                                        <div class="text-md text-gray-500">{{ $supplier->company_name }}</div>
                                                    </div>
                                                </div>
                                            </td>

                                            {{-- Contact --}}
                                            <td class="p-4">
                                                <div class="flex items-center justify-start gap-3 text-md text-gray-900">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    {{ $supplier->contact_phone }}
                                                </div>
                                            </td>

                                            {{-- Address --}}
                                            <td class="p-4">
                                                @if($supplier->address)
                                                    <div class="flex justify-start gap-3 max-w-md mx-auto">
                                                        <svg class="w-5 h-5 text-gray-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        <span class="text-md text-gray-900 break-words" title="{{ $supplier->address }}">
                                                            {{ Str::limit($supplier->address, 60) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-md text-gray-400 italic">No address provided</span>
                                                @endif
                                            </td>

                                            {{-- Status --}}
                                            <td class="p-4">
                                                <span id="status-badge-{{ $supplier->id }}" class="inline-flex items-center px-4 py-2 rounded-full text-md font-medium 
                                                                                                                                                {{ $supplier->status === 'active'
                            ? 'bg-green-100 text-green-800 border border-green-200'
                            : 'bg-red-100 text-red-800 border border-red-200' }}">
                                                    <span
                                                        class="w-2 h-2 rounded-full mr-2 
                                                                                                                                                    {{ $supplier->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                                    {{ ucfirst($supplier->status) }}
                                                </span>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="p-4">
                                                <div class="flex justify-center items-center space-x-3">
                                                    {{-- Toggle Status Button --}}
                                                    <button id="toggle-btn-{{ $supplier->id }}"
                                                        onclick="toggleStatus('{{ $supplier->id }}')" class="p-3 rounded-lg transition duration-200 
                                                                                                                                                {{ $supplier->status === 'active'
                            ? 'bg-orange-50 text-orange-600 hover:bg-orange-100'
                            : 'bg-green-50 text-green-600 hover:bg-green-100' }}"
                                                        title="{{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                        @if($supplier->status === 'active')
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        @else
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                            </svg>
                                                        @endif
                                                    </button>

                                                    {{-- Edit Button - Conditionally Enabled --}}
                                                    @if($supplier->status === 'active')
                                                        <button onclick="editSupplier('{{ $supplier->id }}')"
                                                            class="p-3 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition duration-200 edit-btn"
                                                            title="Edit Supplier" data-supplier-id="{{ $supplier->id }}">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <button
                                                            class="p-3 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed transition duration-200 edit-btn"
                                                            title="Cannot edit inactive supplier" data-supplier-id="{{ $supplier->id }}"
                                                            disabled>
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <svg class="w-20 h-20 mb-6 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p class="text-xl font-semibold text-gray-600">
                                            @if(request('search'))
                                                No suppliers found for "{{ request('search') }}"
                                            @else
                                                No suppliers found
                                            @endif
                                        </p>
                                        <p class="text-md text-gray-500 mt-2">
                                            @if(request('search'))
                                                Try adjusting your search terms
                                            @else
                                                Get started by adding your first supplier
                                            @endif
                                        </p>

                                        @if(!request('search'))
                                            <button id="openSupplierModalEmpty"
                                                class="mt-6 inline-flex items-center gap-3 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 font-medium text-lg">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Add Your First Supplier
                                            </button>
                                        @else
                                            <a href="{{ route('suppliers') }}"
                                                class="mt-6 inline-flex items-center gap-3 px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition duration-200 font-medium text-lg">
                                                View All Suppliers
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($suppliers->hasPages())
                <div class="border-t border-gray-200 px-6 py-4 bg-gray-50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        {{-- Showing results info --}}
                        <div class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ $suppliers->firstItem() }}</span>
                            to
                            <span class="font-medium">{{ $suppliers->lastItem() }}</span>
                            of
                            <span class="font-medium">{{ $suppliers->total() }}</span>
                            results
                        </div>

                        {{-- Pagination Links --}}
                        <nav class="flex items-center space-x-2">
                            {{-- Previous Page Link --}}
                            @if ($suppliers->onFirstPage())
                                <span class="px-3 py-2 text-gray-400 border border-gray-300 rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $suppliers->previousPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}"
                                    class="px-3 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($suppliers->getUrlRange(1, $suppliers->lastPage()) as $page => $url)
                                @if ($page == $suppliers->currentPage())
                                    <span class="px-4 py-2 bg-indigo-600 text-white border border-indigo-600 rounded-lg font-medium">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}{{ request('search') ? '&search=' . request('search') : '' }}"
                                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition duration-200">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($suppliers->hasMorePages())
                                <a href="{{ $suppliers->nextPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}"
                                    class="px-3 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span class="px-3 py-2 text-gray-400 border border-gray-300 rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            @endif
        </div>

        {{-- Add Supplier Modal --}}
        <div id="supplierModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
            <div
                class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-auto transform scale-95 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Add New Supplier</h3>
                        <button id="closeSupplierModal" class="text-gray-400 hover:text-gray-600 transition duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="supplierForm" method="POST" action="{{ route('suppliers.store') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="supplier_name" class="block text-sm font-medium text-gray-700 mb-2">Supplier
                                    Name *</label>
                                <input type="text" id="supplier_name" name="supplier_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter supplier name">
                            </div>

                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Company Name
                                    *</label>
                                <input type="text" id="company_name" name="company_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter company name">
                            </div>

                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact
                                    Phone *</label>
                                <input type="text" id="contact_phone" name="contact_phone" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter phone number">
                            </div>

                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea id="address" name="address" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter supplier address"></textarea>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" id="cancelSupplierModal"
                                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 font-medium">
                                Add Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Supplier Modal --}}
        <div id="editSupplierModal"
            class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
            <div
                class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-auto transform scale-95 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Edit Supplier</h3>
                        <button onclick="closeEditModal()"
                            class="text-gray-400 hover:text-gray-600 transition duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="editSupplierForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_supplier_id" name="supplier_id">

                        <div class="space-y-4">
                            <div>
                                <label for="edit_supplier_name"
                                    class="block text-sm font-medium text-gray-700 mb-2">Supplier Name *</label>
                                <input type="text" id="edit_supplier_name" name="supplier_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter supplier name">
                            </div>

                            <div>
                                <label for="edit_company_name" class="block text-sm font-medium text-gray-700 mb-2">Company
                                    Name *</label>
                                <input type="text" id="edit_company_name" name="company_name" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter company name">
                            </div>

                            <div>
                                <label for="edit_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact
                                    Phone *</label>
                                <input type="text" id="edit_contact_phone" name="contact_phone" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter phone number">
                            </div>

                            <div>
                                <label for="edit_address"
                                    class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea id="edit_address" name="address" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter supplier address"></textarea>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" onclick="closeEditModal()"
                                class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 font-medium">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 font-medium">
                                Update Supplier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .status-badge {
            transition: all 0.3s ease;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .edit-btn:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        .edit-btn:disabled:hover {
            background-color: #f3f4f6 !important;
            color: #9ca3af !important;
        }

        .toggle-loading {
            position: relative;
            pointer-events: none;
        }

        .toggle-loading::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .toggle-loading svg {
            opacity: 0;
        }

        .smooth-transition {
            transition: all 0.3s ease-in-out;
        }
    </style>

    <script>
        // Modal elements
        const supplierModal = document.getElementById('supplierModal');
        const editSupplierModal = document.getElementById('editSupplierModal');
        const openSupplierModal = document.getElementById('openSupplierModal');
        const openSupplierModalEmpty = document.getElementById('openSupplierModalEmpty');
        const closeSupplierModal = document.getElementById('closeSupplierModal');
        const cancelSupplierModal = document.getElementById('cancelSupplierModal');

        // Add Supplier Modal Functions
        function openAddModal() {
            supplierModal.classList.remove('hidden');
            supplierModal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            setTimeout(() => {
                supplierModal.querySelector('.max-w-md').classList.remove('scale-95');
                supplierModal.querySelector('.max-w-md').classList.add('scale-100');
            }, 10);
        }

        function closeAddModal() {
            supplierModal.querySelector('.max-w-md').classList.remove('scale-100');
            supplierModal.querySelector('.max-w-md').classList.add('scale-95');
            setTimeout(() => {
                supplierModal.classList.add('hidden');
                supplierModal.classList.remove('flex');
                document.body.style.overflow = 'auto';
                document.getElementById('supplierForm').reset();
            }, 200);
        }

        // Edit Supplier Modal Functions
        async function editSupplier(supplierId) {
            try {
                // Show loading state
                const submitButton = document.querySelector('#editSupplierForm button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';
                submitButton.disabled = true;

                // Fetch supplier data
                const response = await fetch(`/suppliers/${supplierId}`);

                if (!response.ok) {
                    throw new Error('Failed to fetch supplier data');
                }

                const supplier = await response.json();

                // Check if supplier is active before allowing edit
                if (supplier.status !== 'active') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Cannot Edit',
                        text: 'Inactive suppliers cannot be edited. Please activate the supplier first.',
                        confirmButtonColor: '#EF4444'
                    });
                    closeEditModal();
                    return;
                }

                // Populate form fields
                document.getElementById('edit_supplier_id').value = supplier.id;
                document.getElementById('edit_supplier_name').value = supplier.supplier_name || '';
                document.getElementById('edit_company_name').value = supplier.company_name || '';
                document.getElementById('edit_contact_phone').value = supplier.contact_phone || '';
                document.getElementById('edit_address').value = supplier.address || '';

                // Set form action
                document.getElementById('editSupplierForm').action = `/suppliers/${supplier.id}`;

                // Show modal
                editSupplierModal.classList.remove('hidden');
                editSupplierModal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                // Trigger animation
                setTimeout(() => {
                    editSupplierModal.querySelector('.max-w-md').classList.remove('scale-95');
                    editSupplierModal.querySelector('.max-w-md').classList.add('scale-100');
                }, 10);

            } catch (error) {
                console.error('Error fetching supplier data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to load supplier data',
                    confirmButtonColor: '#EF4444'
                });
            } finally {
                // Reset button state
                const submitButton = document.querySelector('#editSupplierForm button[type="submit"]');
                submitButton.innerHTML = 'Update Supplier';
                submitButton.disabled = false;
            }
        }

        function closeEditModal() {
            const modalContent = editSupplierModal.querySelector('.max-w-md');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                editSupplierModal.classList.add('hidden');
                editSupplierModal.classList.remove('flex');
                document.body.style.overflow = 'auto';
                document.getElementById('editSupplierForm').reset();
            }, 200);
        }

        // Event Listeners for Add Modal
        if (openSupplierModal) {
            openSupplierModal.addEventListener('click', openAddModal);
        }

        if (openSupplierModalEmpty) {
            openSupplierModalEmpty.addEventListener('click', openAddModal);
        }

        if (closeSupplierModal) {
            closeSupplierModal.addEventListener('click', closeAddModal);
        }

        if (cancelSupplierModal) {
            cancelSupplierModal.addEventListener('click', closeAddModal);
        }

        // Click outside to close modals
        supplierModal.addEventListener('click', (e) => {
            if (e.target === supplierModal) {
                closeAddModal();
            }
        });

        editSupplierModal.addEventListener('click', (e) => {
            if (e.target === editSupplierModal) {
                closeEditModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!supplierModal.classList.contains('hidden')) {
                    closeAddModal();
                }
                if (!editSupplierModal.classList.contains('hidden')) {
                    closeEditModal();
                }
            }
        });

        // Toggle supplier status with smooth loading
        async function toggleStatus(supplierId) {
            const toggleButton = document.getElementById(`toggle-btn-${supplierId}`);
            const statusBadge = document.getElementById(`status-badge-${supplierId}`);

            // Store current state for rollback in case of error
            const currentStatus = statusBadge.textContent.trim().toLowerCase();
            const currentStatusClass = statusBadge.className;
            const currentButtonClass = toggleButton.className;
            const currentButtonTitle = toggleButton.title;
            const currentButtonHTML = toggleButton.innerHTML;

            try {
                // Add loading state to button
                toggleButton.classList.add('toggle-loading');
                toggleButton.disabled = true;

                const response = await fetch(`/suppliers/${supplierId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update status badge
                    if (data.status === 'active') {
                        statusBadge.className = 'inline-flex items-center px-4 py-2 rounded-full text-md font-medium bg-green-100 text-green-800 border border-green-200 smooth-transition';
                        statusBadge.innerHTML = '<span class="w-2 h-2 rounded-full mr-2 bg-green-500"></span>Active';

                        // Update toggle button
                        toggleButton.className = 'p-3 rounded-lg transition duration-200 bg-orange-50 text-orange-600 hover:bg-orange-100 smooth-transition';
                        toggleButton.title = 'Deactivate';
                        toggleButton.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';

                        // Enable edit button
                        const editButton = document.querySelector(`button[data-supplier-id="${supplierId}"]`);
                        if (editButton) {
                            editButton.className = 'p-3 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition duration-200 edit-btn smooth-transition';
                            editButton.title = 'Edit Supplier';
                            editButton.disabled = false;
                            editButton.onclick = function () { editSupplier(supplierId); };
                        }

                    } else {
                        statusBadge.className = 'inline-flex items-center px-4 py-2 rounded-full text-md font-medium bg-red-100 text-red-800 border border-red-200 smooth-transition';
                        statusBadge.innerHTML = '<span class="w-2 h-2 rounded-full mr-2 bg-red-500"></span>Inactive';

                        // Update toggle button
                        toggleButton.className = 'p-3 rounded-lg transition duration-200 bg-green-50 text-green-600 hover:bg-green-100 smooth-transition';
                        toggleButton.title = 'Activate';
                        toggleButton.innerHTML = '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>';

                        // Disable edit button
                        const editButton = document.querySelector(`button[data-supplier-id="${supplierId}"]`);
                        if (editButton) {
                            editButton.className = 'p-3 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed transition duration-200 edit-btn smooth-transition';
                            editButton.title = 'Cannot edit inactive supplier';
                            editButton.disabled = true;
                            editButton.onclick = null;
                        }
                    }

                    // Update statistics counts
                    updateStatisticsCounts(data.status);

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonColor: '#4F46E5',
                        timer: 2000,
                        showConfirmButton: false
                    });

                } else {
                    throw new Error(data.message || 'Failed to update status');
                }

            } catch (error) {
                console.error('Error toggling status:', error);

                // Revert to previous state on error
                statusBadge.className = currentStatusClass;
                statusBadge.innerHTML = currentStatus === 'active'
                    ? '<span class="w-2 h-2 rounded-full mr-2 bg-green-500"></span>Active'
                    : '<span class="w-2 h-2 rounded-full mr-2 bg-red-500"></span>Inactive';

                toggleButton.className = currentButtonClass;
                toggleButton.title = currentButtonTitle;
                toggleButton.innerHTML = currentButtonHTML;

                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update supplier status. Please try again.',
                    confirmButtonColor: '#EF4444'
                });
            } finally {
                // Remove loading state
                toggleButton.classList.remove('toggle-loading');
                toggleButton.disabled = false;
            }
        }

        // Update statistics counts without page reload
        function updateStatisticsCounts(newStatus) {
            const activeCountElement = document.getElementById('activeCount');
            const inactiveCountElement = document.getElementById('inactiveCount');

            let activeCount = parseInt(activeCountElement.textContent);
            let inactiveCount = parseInt(inactiveCountElement.textContent);

            if (newStatus === 'active') {
                // Moving from inactive to active
                activeCountElement.textContent = activeCount + 1;
                inactiveCountElement.textContent = inactiveCount - 1;
            } else {
                // Moving from active to inactive
                activeCountElement.textContent = activeCount - 1;
                inactiveCountElement.textContent = inactiveCount + 1;
            }
        }

        // Handle edit form submission
        document.getElementById('editSupplierForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            try {
                submitButton.innerHTML = '<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Updating...';
                submitButton.disabled = true;

                // Convert FormData to JSON object
                const jsonData = {};
                formData.forEach((value, key) => {
                    if (key !== '_token' && key !== '_method') {
                        jsonData[key] = value;
                    }
                });

                const response = await fetch(this.action, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(jsonData)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonColor: '#4F46E5',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        closeEditModal();
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Failed to update supplier');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message,
                    confirmButtonColor: '#EF4444'
                });
            } finally {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });

        // Auto-submit search form when typing (optional - debounced)
        let searchTimeout;
        document.querySelector('input[name="search"]')?.addEventListener('input', function (e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (e.target.value.length === 0 || e.target.value.length >= 3) {
                    e.target.form.submit();
                }
            }, 500);
        });

        // Display success/error messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#4F46E5',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#EF4444'
            });
        @endif

        // Form submission handling for add form
        document.getElementById('supplierForm')?.addEventListener('submit', function (e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...';
            submitButton.disabled = true;
        });
    </script>
@endsection