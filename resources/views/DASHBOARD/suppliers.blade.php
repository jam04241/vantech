@extends('SIDEBAR.layouts')

@section('title', 'Manage Suppliers')

@section('name', 'SUPPLIERS')

@section('content')
    <div class="bg-white border rounded-lg p-6 shadow-sm">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" id="supplierSearch" placeholder="Search suppliers by name, company, or phone..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                        aria-label="Search suppliers">
                </div>
            </div>

            <button id="openSupplierModal"
                class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg font-medium hover:shadow-xl transform hover:-translate-y-0.5"
                aria-label="Add a new supplier">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Supplier
            </button>
        </div>

        <div class="border-t border-gray-200 my-6"></div>

        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Supplier Management</h2>
                <p class="text-gray-600 mt-1">Manage your suppliers and their information</p>
            </div>

            {{-- Action Buttons --}}
            <div class="flex gap-2 mt-4 sm:mt-0">
                <button
                    class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Print
                </button>
                <button
                    class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                    </svg>
                    Filters
                </button>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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
                        <p class="text-2xl font-bold text-gray-900">{{ $suppliers->count() }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ $suppliers->where('status', 'active')->count() }}</p>
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
                        <p class="text-2xl font-bold text-gray-900">{{ $suppliers->where('status', 'inactive')->count() }}
                        </p>
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
                                    {{ $loop->iteration }}
                                </td>

                                {{-- Supplier Info --}}
                                <td class="p-4">
                                    <div class="flex items-center justify-center">
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
                                    <div class="flex flex-col items-center space-y-2">
                                        <div class="flex items-center gap-3 text-md text-gray-900">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $supplier->contact_phone }}
                                        </div>

                                        @if($supplier->contact_email)
                                            <div class="flex items-center gap-3 text-md text-gray-600">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $supplier->contact_email }}
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Address --}}
                                <td class="p-4">
                                    @if($supplier->address)
                                        <div class="flex justify-center gap-3 max-w-md mx-auto">
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
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-md font-medium 
                                        {{ $supplier->status === 'active'
                    ? 'bg-green-100 text-green-800 border border-green-200'
                    : 'bg-red-100 text-red-800 border border-red-200' }}">
                                        <span class="w-2 h-2 rounded-full mr-2 
                                            {{ $supplier->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="p-4">
                                    <div class="flex justify-center items-center space-x-3">
                                        {{-- Toggle --}}
                                        <button onclick="toggleStatus('{{ $supplier->id }}')" class="p-3 rounded-lg transition duration-200 
                                            {{ $supplier->status === 'active'
                    ? 'bg-orange-50 text-orange-600 hover:bg-orange-100'
                    : 'bg-green-50 text-green-600 hover:bg-green-100' }}"
                                            title="{{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($supplier->status === 'active')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                @endif
                                            </svg>
                                        </button>

                                        {{-- Edit --}}
                                        <button onclick="editSupplier('{{ $supplier->id }}')"
                                            class="p-3 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition duration-200"
                                            title="Edit Supplier">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- Delete --}}
                                        <button onclick="deleteSupplier('{{ $supplier->id }}')"
                                            class="p-3 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition duration-200"
                                            title="Delete Supplier">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-20 h-20 mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-xl font-semibold text-gray-600">No suppliers found</p>
                                <p class="text-md text-gray-500 mt-2">Get started by adding your first supplier</p>

                                <button id="openSupplierModalEmpty"
                                    class="mt-6 inline-flex items-center gap-3 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-200 font-medium text-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Your First Supplier
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>

            </table>
        </div>
    </div>

        {{-- Add Supplier Modal --}}
        <div id="supplierModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
            <div
                class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-auto transform transition-all duration-300 scale-95">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Add New Supplier</h2>
                    <button id="closeSupplierModal"
                        class="text-gray-400 hover:text-gray-600 transition duration-200 p-1 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="supplierForm" action="{{ route('suppliers.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label for="supplier_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Supplier Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="supplier_name" name="supplier_name" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                            placeholder="Enter supplier name">
                    </div>

                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="company_name" name="company_name" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                            placeholder="Enter company name">
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="contact_phone" name="contact_phone" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400"
                            placeholder="Enter contact phone">
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Address
                        </label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 placeholder-gray-400 resize-none"
                            placeholder="Enter full address"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelSupplierModal"
                            class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 font-medium shadow-sm hover:shadow-md">
                            Save Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .tooltip {
            position: relative;
        }

        .tooltip::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1f2937;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s;
            z-index: 1000;
        }

        .tooltip:hover::before {
            opacity: 1;
            visibility: visible;
            bottom: calc(100% + 8px);
        }

        .status-badge {
            transition: all 0.3s ease;
        }

        /* Smooth animations */
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
    </style>

    <script>
        // Modal handling
        const supplierModal = document.getElementById('supplierModal');
        const openSupplierModal = document.getElementById('openSupplierModal');
        const openSupplierModalEmpty = document.getElementById('openSupplierModalEmpty');
        const closeSupplierModal = document.getElementById('closeSupplierModal');
        const cancelSupplierModal = document.getElementById('cancelSupplierModal');

        function openModal() {
            supplierModal.classList.remove('hidden');
            supplierModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            // Trigger animation
            setTimeout(() => {
                supplierModal.querySelector('.max-w-md').classList.remove('scale-95');
                supplierModal.querySelector('.max-w-md').classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            supplierModal.querySelector('.max-w-md').classList.remove('scale-100');
            supplierModal.querySelector('.max-w-md').classList.add('scale-95');
            setTimeout(() => {
                supplierModal.classList.add('hidden');
                supplierModal.classList.remove('flex');
                document.body.style.overflow = 'auto';
                document.getElementById('supplierForm').reset();
            }, 200);
        }

        if (openSupplierModal) {
            openSupplierModal.addEventListener('click', openModal);
        }

        if (openSupplierModalEmpty) {
            openSupplierModalEmpty.addEventListener('click', openModal);
        }

        if (closeSupplierModal) {
            closeSupplierModal.addEventListener('click', closeModal);
        }

        if (cancelSupplierModal) {
            cancelSupplierModal.addEventListener('click', closeModal);
        }

        // Click outside to close
        supplierModal.addEventListener('click', (e) => {
            if (e.target === supplierModal) {
                closeModal();
            }
        });

        // Toggle supplier status with AJAX
        async function toggleStatus(id) {
            try {
                const response = await fetch(`/suppliers/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update the status badge
                    const statusBadge = document.querySelector(`#supplier-${id} .status-badge`);
                    if (statusBadge) {
                        if (data.status === 'active') {
                            statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium status-badge bg-green-100 text-green-800 border border-green-200';
                            statusBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-green-500"></span>Active';
                        } else {
                            statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium status-badge bg-red-100 text-red-800 border border-red-200';
                            statusBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full mr-1.5 bg-red-500"></span>Inactive';
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonColor: '#4F46E5',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Reload page to update statistics after a short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to update supplier status',
                    confirmButtonColor: '#EF4444'
                });
            }
        }

        // Edit supplier - redirect to edit page
        function editSupplier(id) {
            window.location.href = `/suppliers/${id}/edit`;
        }

        // Delete supplier with confirmation
        function deleteSupplier(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This supplier will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                background: '#fff',
                iconColor: '#EF4444'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/suppliers/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });

                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Supplier has been deleted successfully.',
                                confirmButtonColor: '#4F46E5',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error('Delete failed');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete supplier. Please try again.',
                            confirmButtonColor: '#EF4444'
                        });
                    }
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('supplierSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function (e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

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

        // Form submission handling
        document.getElementById('supplierForm')?.addEventListener('submit', function (e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<svg class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...';
            submitButton.disabled = true;
        });
    </script>
@endsection