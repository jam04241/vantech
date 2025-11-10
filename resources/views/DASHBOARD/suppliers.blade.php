@extends('SIDEBAR.layouts')

@section('title', 'Manage Suppliers')

@section('name', 'SUPPLIERS')

@section('content')
    <div class="bg-white border p-6">
            {{-- Header with Add Button --}}
        {{-- Header with Search and Add Button --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <div class="flex-1">
        <h2 class="text-2xl font-bold text-gray-800">Supplier Management</h2>
        <p class="text-gray-600 mt-1">Manage your suppliers and their information</p>
    </div>
    
    {{-- Search Bar --}}
    <div class="flex-1 max-w-md">
        <div class="relative">
            <input type="text" id="supplierSearch" placeholder="Search suppliers..." 
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                aria-label="Search suppliers">
            <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>
    
    <button id="openSupplierModal"
        class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg font-medium"
        aria-label="Add a new supplier">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        Add New Supplier
    </button>
</div>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-7">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Suppliers</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $suppliers->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Active</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $suppliers->where('status', 'active')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-lg p-4 border border-red-100">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Inactive</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $suppliers->where('status', 'inactive')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Suppliers Table --}}
        <div class="bg-white border overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-base"> <!-- Increased base font -->
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">#</th>
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Supplier Info</th>
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Contact
                            </th>
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Address
                            </th>
                            <th class="px-6 py-5 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Status
                            </th>
                            <th class="px-6 py-5 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">

                        @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50 transition duration-150" id="supplier-{{ $supplier->id }}">
                        <!-- Number -->
                        <td class="px-6 py-5 text-gray-900 font-semibold text-base">
                            {{ $loop->iteration }}
                        </td>

                        <!-- Supplier Info -->
                        <td class="px-6 py-5">
                               <div class="flex items-center">
                                <div class="h-12 w-12 bg-indigo-600 text-white text-lg font-bold rounded-lg flex items-center justify-center">
                                    {{ substr($supplier->supplier_name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-base font-semibold text-gray-900">{{ $supplier->supplier_name }}</div>
                                    <div class="text-sm text-gray-600">{{ $supplier->company_name }}</div>
                                </div>
                            </div>
                    
                        <!-- Contact -->
                           <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-2 text-gray-900 text-base">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $supplier->contact_phone }}
                    
                            @if($supplier->contact_email)
                                <div class="text-sm text-gray-600">{{ $supplier->contact_email }}</div>
                               @endif
                    
                        <!-- Address -->
                        <td class="px-6 py-5 whitespace-nowrap text-gray-900 text-base">
                            @if($supplier->address)
                                <span class="block max-w-xs truncate" title="{{ $supplier->address }}">
                                    {{ Str::limit($supplier->address, 45) }}
                                </span>
                            @else
                                <span class="text-gray-400 italic text-base">No address provided</span>
                                @endif
                    
                        <!-- Status -->
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span
                                class="px-4 py-1.5 rounded-full text-base font-semibold 
                                    {{ $supplier->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($supplier->status) }}
                            </span>
                        </td>
                        <!-- Actions -->
                            <td class="px-6 py-4 text-center">
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleDropdown('{{ $supplier->id }}')"
                                        class="inline-flex justify-center w-full px-2 py-1 text-gray-500 hover:text-gray-700 rounded-md transition focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        aria-haspopup="true" aria-expanded="false">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M6 10a2 2 0 114 0 2 2 0 01-4 0zm4-6a2 2 0 11-4 0 2 2 0 014 0zm0 12a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                    <div id="dropdown-{{ $supplier->id }}"
                                        class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50"
                                        role="menu">
                                        <button onclick="toggleStatus('{{ $supplier->id }}')"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-800 transition"
                                            role="menuitem">
                                            {{ $supplier->status === 'active' ? 'Deactivate' : 'Activate' }}
                                        </button>
                                        <button onclick="editSupplier('{{ $supplier->id }}')"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-800 transition"
                                            role="menuitem">
                                            Edit
                                        </button>
                                        <button onclick="deleteSupplier('{{ $supplier->id }}')"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-800 transition"
                                            role="menuitem">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </td>
                            </tr>


                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-600 text-lg font-semibold">
                                    No suppliers found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>


        {{-- Add Supplier Modal --}}
        <div id="supplierModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Add New Supplier</h2>
                    <button id="closeSupplierModal" class="text-gray-400 hover:text-gray-600 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="Enter supplier name">
                    </div>

                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="company_name" name="company_name" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="Enter company name">
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="contact_phone" name="contact_phone" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="Enter contact phone">
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Address
                        </label>
                        <textarea id="address" name="address" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="Enter full address"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelSupplierModal"
                            class="px-6 py-3 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 transition duration-200 font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 font-medium">
                            Save Supplier
                        </button>
                    </div>
                </form>
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
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 12px;
                white-space: nowrap;
                opacity: 0;
                visibility: hidden;
                transition: all 0.2s;
            }

            .tooltip:hover::before {
                opacity: 1;
                visibility: visible;
                bottom: calc(100% + 5px);
            }

            .status-badge {
                transition: all 0.3s ease;
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
            }

            function closeModal() {
                supplierModal.classList.add('hidden');
                supplierModal.classList.remove('flex');
                document.body.style.overflow = 'auto';
                document.getElementById('supplierForm').reset();
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
                                statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium status-badge bg-green-100 text-green-800';
                                statusBadge.textContent = 'Active';
                            } else {
                                statusBadge.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium status-badge bg-red-100 text-red-800';
                                statusBadge.textContent = 'Inactive';
                            }
                        }

                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            confirmButtonColor: '#4F46E5',
                            timer: 2000
                        });

                        // Reload page to update statistics
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
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
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
                                    text: 'Supplier has been deleted.',
                                    confirmButtonColor: '#4F46E5',
                                    timer: 2000
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
                                text: 'Failed to delete supplier',
                                confirmButtonColor: '#EF4444'
                            });
                        }
                    }
                });
            }

       function toggleDropdown(id) {
            const dropdown = document.getElementById(`dropdown-${id}`);
            // Close all other dropdowns
            document.querySelectorAll('[id^="dropdown-"]').forEach(menu => {
                if (menu !== dropdown) menu.classList.add('hidden');
            });
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (event) {
            document.querySelectorAll('[id^="dropdown-"]').forEach(menu => {
                const button = menu.previousElementSibling;
                if (!menu.contains(event.target) && !button.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

            // Display success/error messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#4F46E5',
                    timer: 3000
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
            document.getElementById('supplierForm')?.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.innerHTML = 'Saving...';
                submitButton.disabled = true;
            });
        </script>
@endsection