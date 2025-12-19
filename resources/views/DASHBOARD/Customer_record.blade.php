@extends('SIDEBAR.layouts')

@section('title', 'Customer Management')
@section('name', 'Customer Management')

@section('content')

    <div class="bg-white border rounded-lg p-6 shadow-sm">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            {{-- Search Bar --}}
            <div class="relative w-full sm:flex-1 sm:max-w-md">
                <input type="text" id="searchInput" placeholder="Search customers by name, contact, or address..."
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                    aria-label="Search customers">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <a href="#" id="clearSearch"
                    class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" />
                    </svg>
                </a>
            </div>

            <button id="openCustomerModal"
                class="inline-flex items-center gap-2 px-5 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg font-medium hover:shadow-xl transform hover:-translate-y-0.5"
                aria-label="Add a new customer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add New Customer
            </button>
        </div>

        <div class="border-t border-gray-200 my-6"></div>

        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Customer Management</h2>
                <p class="text-gray-600 mt-1">Manage your customers and their information</p>
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
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Customers</p>
                        <p class="text-2xl font-bold text-gray-900" id="totalCustomersCount">0</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Male</p>
                        <p class="text-2xl font-bold text-gray-900" id="maleCount">0</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl p-5 border border-pink-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-pink-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Female</p>
                        <p class="text-2xl font-bold text-gray-900" id="femaleCount">0</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Customers Table --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100 text-gray-700 text-base">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">#</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">First Name</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Last Name</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Contact</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Gender</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Street</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Barangay</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">City/Province</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($customers as $index => $customer)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-left">{{ $customer->first_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 text-left">{{ $customer->last_name }}</td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-500 {{ $customer->contact_no ? 'text-left' : 'text-center' }}">
                                    {{ $customer->contact_no ?: '-' }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-500 {{ $customer->gender ? 'text-left' : 'text-center' }}">
                                    {{ $customer->gender ?: '-' }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-500 {{ $customer->street ? 'text-left' : 'text-center' }}">
                                    {{ $customer->street ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 {{ $customer->brgy ? 'text-left' : 'text-center' }}">
                                    {{ $customer->brgy ?: '-' }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-500 {{ $customer->city_province ? 'text-left' : 'text-center' }}">
                                    {{ $customer->city_province ?: '-' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{-- @if(Auth::user() && Auth::user()->role === 'admin') --}}
                                    <button onclick="viewPurchaseTransactions({{ $customer->id }})"
                                        class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition duration-200">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Transactions
                                    </button>
                                    {{-- @elseif(Auth::user() && Auth::user()->role === 'staff')
                                    <button onclick="showAdminVerificationModal('{{ route('customer.records') }}')"
                                        class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition duration-200 relative group">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Transactions
                                        <span
                                            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs bg-red-500 text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Admin
                                            Only</span>
                                    </button> --}}
                                    {{-- @endif --}}
                                    <button onclick="editCustomer({{ $customer->id }})"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition duration-200">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                    <p class="text-sm">No customers found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

                {{-- Purchase Transactions Modal --}}
        <div id="transactionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4 overflow-y-auto">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-7xl mx-auto transform scale-95 transition-all duration-300 my-8">
                <div class="p-6">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Purchase Transactions</h3>
                        <button id="closeTransactionModal" class="text-gray-400 hover:text-gray-600 transition duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Main Content Grid --}}
                    <div class="grid grid-cols-12 gap-6">
                        {{-- Left Column - Customer & Payment Info --}}
                        <div class="col-span-4 space-y-6">
                            {{-- Customer Information --}}
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-5 border border-blue-200">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Customer Information</h4>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Full Name</p>
                                        <p id="transactionFullName" class="text-base font-semibold text-gray-900 truncate">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Contact No.</p>
                                        <p id="transactionContactNo" class="text-base font-semibold text-gray-900">-</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Full Address</p>
                                        <p id="transactionFullAddress" class="text-base font-semibold text-gray-900 line-clamp-2">-</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Information --}}
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-5 border border-blue-200">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Payment Information</h4>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Payment Method</p>
                                        <p id="transactionPaymentMethod" class="text-base font-semibold text-gray-900">-</p>
                                    </div>
                                    <div id="accountNameContainer" class="hidden">
                                        <p class="text-sm font-medium text-gray-600">Account Name</p>
                                        <p id="transactionAccountName" class="text-base font-semibold text-gray-900 truncate">-</p>
                                    </div>
                                    <div id="bankNameContainer" class="hidden">
                                        <p class="text-sm font-medium text-gray-600">Bank</p>
                                        <p id="transactionBankName" class="text-base font-semibold text-gray-900 truncate">-</p>
                                    </div>
                                    <div id="referenceNoContainer" class="hidden">
                                        <p class="text-sm font-medium text-gray-600">Reference No.</p>
                                        <p id="transactionReferenceNo" class="text-base font-semibold text-gray-900 truncate">-</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Receipt Selection --}}
                            <div class="bg-white rounded-lg p-5 border border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Select Receipt</h4>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Recent Purchase Order Receipt No.</label>
                                <select id="receiptSelect" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="">-- Select a receipt --</option>
                                </select>
                            </div>
                        </div>

                        {{-- Right Column - Transaction Details --}}
                        <div class="col-span-8">
                            <div id="transactionDetailsContainer" class="hidden h-full flex flex-col">
                                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 h-full flex flex-col">
                                    {{-- Transaction Header --}}
                                    <div class="grid grid-cols-2 gap-4 mb-6 pb-4 border-b border-gray-300">
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Date & Time</p>
                                            <p id="transactionDateTime" class="text-lg font-semibold text-gray-900">-</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-600">Receipt No.</p>
                                            <p id="transactionReceiptNo" class="text-lg font-semibold text-gray-900">-</p>
                                        </div>
                                    </div>

                                    {{-- Products Table with Fixed Height --}}
                                    <div class="flex-1 overflow-hidden mb-5">
                                        <div class="h-full flex flex-col">
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-sm" style="table-layout: fixed;">
                                                    <thead class="bg-gray-200 text-gray-700">
                                                        <tr>
                                                            <th class="px-4 py-3 text-left font-semibold w-2/5">Product Name</th>
                                                            <th class="px-4 py-3 text-left font-semibold w-1/5">Serial No.</th>
                                                            <th class="px-4 py-3 text-left font-semibold w-1/5">Warranty</th>
                                                            <th class="px-4 py-3 text-left font-semibold w-1/5 whitespace-nowrap">Total Price</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="overflow-y-auto flex-1 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]" style="max-height: 445px;"> <!-- Fixed height for 6 items -->
                                                <table class="w-full text-sm" style="table-layout: fixed;">
                                                    <tbody id="productsTableBody" class="divide-y divide-gray-200">
                                                        {{-- Products will be dynamically inserted here --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                            {{-- Item Count Indicator --}}
                                            <div class="mt-2 text-xs text-gray-500 text-right">
                                                <span id="itemCount">0</span> item(s) total
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Summary Section --}}
                                    <div class="border-t border-gray-300 pt-5">
                                        <div class="grid grid-cols-3 gap-4">
                                            <div class="col-span-2">
                                                <div class="space-y-2">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-700 font-medium">Subtotal:</span>
                                                        <span id="subtotalAmount" class="text-gray-900 font-semibold">₱ 0.00</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-gray-700 font-medium">Discount:</span>
                                                        <span id="discountAmount" class="text-gray-900 font-semibold text-red-600">- ₱ 0.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                                <div class="text-right">
                                                    <span class="text-gray-800 font-bold">Total Price:</span>
                                                    <div id="totalAmount" class="text-blue-600 font-bold text-2xl">₱ 0.00</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- No Transactions Message --}}
                            <div id="noTransactionsMessage" class="hidden text-center py-12 bg-gray-50 rounded-lg border border-gray-200 h-full flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-gray-500 text-lg">No purchase transactions found for this customer</p>
                            </div>
                        </div>
                    </div>

                    {{-- Close Button --}}
                    <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                        <button id="closeTransactionBtn" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add/Edit Customer Modal --}}
        <div id="customerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
            <div
                class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-auto transform scale-95 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 id="modalTitle" class="text-2xl font-bold text-gray-800">Add New Customer</h3>
                        <button id="closeCustomerModal" class="text-gray-400 hover:text-gray-600 transition duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="customerForm" class="space-y-4">
                        @csrf
                        <input type="hidden" id="customerId" name="id">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name *</label>
                            <input type="text" id="firstName" name="first_name" required
                                class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                            <input type="text" id="lastName" name="last_name" required
                                class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Number *</label>
                            <input type="text" id="contactNo" name="contact_no" required
                                class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender *</label>
                            <select id="gender" name="gender" required
                                class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Street</label>
                            <input type="text" id="street" name="street"
                                class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Barangay</label>
                            <input type="text" id="brgy" name="brgy"
                                class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">City/Province</label>
                            <input type="text" id="cityProvince" name="city_province"
                                class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button type="button" id="cancelBtn"
                                class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-200">
                                Cancel
                            </button>
                            <button type="submit" id="submitBtn"
                                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition duration-200">
                                Save Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Modal elements
    const customerModal = document.getElementById('customerModal');
    const openCustomerModal = document.getElementById('openCustomerModal');
    const closeCustomerModal = document.getElementById('closeCustomerModal');
    const cancelBtn = document.getElementById('cancelBtn');

    // Calculate statistics
    function updateStatistics() {
        const customers = document.querySelectorAll('tbody tr:not(:has(td[colspan]))');
        let totalCustomers = 0;
        let maleCount = 0;
        let femaleCount = 0;

        customers.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 3) {
                totalCustomers++;
                const gender = cells[3].textContent.trim();
                if (gender === 'Male') {
                    maleCount++;
                } else if (gender === 'Female') {
                    femaleCount++;
                }
            }
        });

        document.getElementById('totalCustomersCount').textContent = totalCustomers;
        document.getElementById('maleCount').textContent = maleCount;
        document.getElementById('femaleCount').textContent = femaleCount;
    }

    // Modal functions
    function openModal() {
        customerModal.classList.remove('hidden');
        customerModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            customerModal.querySelector('.max-w-md').classList.remove('scale-95');
            customerModal.querySelector('.max-w-md').classList.add('scale-100');
        }, 10);
    }

    function closeModal() {
        customerModal.querySelector('.max-w-md').classList.remove('scale-100');
        customerModal.querySelector('.max-w-md').classList.add('scale-95');
        setTimeout(() => {
            customerModal.classList.add('hidden');
            customerModal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            document.getElementById('customerForm').reset();
        }, 200);
    }

    // Event listeners for modal
    openCustomerModal.addEventListener('click', openModal);
    closeCustomerModal.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside
    customerModal.addEventListener('click', (e) => {
        if (e.target === customerModal) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !customerModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function (e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr:not(:has(td[colspan]))');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });

        document.getElementById('clearSearch').classList.toggle('hidden', !searchTerm);
    });

    // Clear search
    document.getElementById('clearSearch').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('searchInput').value = '';
        document.getElementById('clearSearch').classList.add('hidden');
        document.querySelectorAll('tbody tr').forEach(row => row.style.display = '');
    });

    async function editCustomer(id) {
        try {
            const response = await fetch(`/customers/${id}`);
            const customer = await response.json();

            document.getElementById('modalTitle').textContent = 'Edit Customer';
            document.getElementById('customerId').value = customer.id;
            document.getElementById('firstName').value = customer.first_name;
            document.getElementById('lastName').value = customer.last_name;
            document.getElementById('contactNo').value = customer.contact_no;
            document.getElementById('gender').value = customer.gender;
            document.getElementById('street').value = customer.street;
            document.getElementById('brgy').value = customer.brgy;
            document.getElementById('cityProvince').value = customer.city_province;
            document.getElementById('submitBtn').textContent = 'Update Customer';
            openModal();
        } catch (error) {
            Swal.fire('Error', 'Failed to load customer data', 'error');
        }
    }

    document.getElementById('customerForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        const customerId = document.getElementById('customerId').value;
        const isEditing = customerId !== '';

        submitBtn.disabled = true;
        submitBtn.textContent = isEditing ? 'Updating...' : 'Saving...';

        const formData = {
            first_name: document.getElementById('firstName').value,
            last_name: document.getElementById('lastName').value,
            contact_no: document.getElementById('contactNo').value,
            gender: document.getElementById('gender').value,
            street: document.getElementById('street').value,
            brgy: document.getElementById('brgy').value,
            city_province: document.getElementById('cityProvince').value,
            _token: document.querySelector('input[name="_token"]').value
        };

        const url = isEditing ? `/customers/${customerId}` : '/customers';
        const method = isEditing ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: isEditing ? 'Customer updated successfully!' : 'Customer added successfully!',
                }).then(() => {
                    window.location.reload();
                });
            } else {
                if (data.errors) {
                    let errorMessage = 'Please fix the following errors:\n';
                    Object.values(data.errors).forEach(error => {
                        errorMessage += `• ${error[0]}\n`;
                    });
                    Swal.fire('Validation Error', errorMessage, 'error');
                } else {
                    throw new Error(data.message || 'Failed to save customer');
                }
            }
        } catch (error) {
            Swal.fire('Error', error.message, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = isEditing ? 'Update Customer' : 'Save Customer';
        }
    });

    // Purchase Transaction Modal Functions
    const transactionModal = document.getElementById('transactionModal');
    const closeTransactionModal = document.getElementById('closeTransactionModal');
    const closeTransactionBtn = document.getElementById('closeTransactionBtn');
    const receiptSelect = document.getElementById('receiptSelect');
    let allTransactions = [];

    function openTransactionModal() {
        transactionModal.classList.remove('hidden');
        transactionModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        setTimeout(() => {
            transactionModal.querySelector('.max-w-7xl').classList.remove('scale-95');
            transactionModal.querySelector('.max-w-7xl').classList.add('scale-100');
        }, 10);
    }

    function closeTransactionModalFunc() {
        transactionModal.querySelector('.max-w-7xl').classList.remove('scale-100');
        transactionModal.querySelector('.max-w-7xl').classList.add('scale-95');
        setTimeout(() => {
            transactionModal.classList.add('hidden');
            transactionModal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            allTransactions = [];
            receiptSelect.innerHTML = '<option value="">-- Select a receipt --</option>';
            // Hide transaction details when closing
            document.getElementById('transactionDetailsContainer').classList.add('hidden');
            // Show no transactions message again
            document.getElementById('noTransactionsMessage').classList.remove('hidden');
        }, 200);
    }

    closeTransactionModal.addEventListener('click', closeTransactionModalFunc);
    closeTransactionBtn.addEventListener('click', closeTransactionModalFunc);

    transactionModal.addEventListener('click', (e) => {
        if (e.target === transactionModal) {
            closeTransactionModalFunc();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !transactionModal.classList.contains('hidden')) {
            closeTransactionModalFunc();
        }
    });

    // Function to update payment method fields based on payment method type
    function updatePaymentMethodFields(transaction) {
        const paymentMethod = transaction.payment_method || '';
        const accountNameContainer = document.getElementById('accountNameContainer');
        const bankNameContainer = document.getElementById('bankNameContainer');
        const referenceNoContainer = document.getElementById('referenceNoContainer');

        // Update payment method display
        document.getElementById('transactionPaymentMethod').textContent = paymentMethod || 'N/A';

        // Update all fields with actual values
        document.getElementById('transactionAccountName').textContent = transaction.account_name || '-';
        document.getElementById('transactionBankName').textContent = transaction.bank_name || '-';
        document.getElementById('transactionReferenceNo').textContent = transaction.reference_no || '-';

        // Hide all containers by default
        accountNameContainer.style.display = 'none';
        bankNameContainer.style.display = 'none';
        referenceNoContainer.style.display = 'none';

        // Show fields based on payment method (matching exact values from purchaseFrame.blade.php)
        if (paymentMethod === 'Cash') {
            // Cash: Hide all fields (Bank, Account Name, Reference No.)
            // All containers are already hidden
        } else if (paymentMethod === 'Gcash') {
            // Gcash: Show only Account Name and Reference No.
            accountNameContainer.style.display = 'block';
            referenceNoContainer.style.display = 'block';
        } else if (paymentMethod === 'Bank Transfer') {
            // Bank Transfer: Show Bank, Account Name, and Reference No.
            accountNameContainer.style.display = 'block';
            bankNameContainer.style.display = 'block';
            referenceNoContainer.style.display = 'block';
        } else {
            // For other payment methods or unknown, show all fields by default
            accountNameContainer.style.display = 'block';
            bankNameContainer.style.display = 'block';
            referenceNoContainer.style.display = 'block';
        }
    }

    async function viewPurchaseTransactions(customerId) {
        try {
            const response = await fetch(`/customers/${customerId}/purchase-transactions`);
            const data = await response.json();

            if (!data.success) {
                Swal.fire('Error', data.message || 'Failed to fetch transactions', 'error');
                return;
            }

            const customer = data.customer;
            document.getElementById('transactionFullName').textContent = customer.full_name;
            document.getElementById('transactionContactNo').textContent = customer.contact_no;
            document.getElementById('transactionFullAddress').textContent = customer.address;

            // Reset payment method fields
            document.getElementById('transactionPaymentMethod').textContent = '-';
            document.getElementById('transactionAccountName').textContent = '-';
            document.getElementById('transactionBankName').textContent = '-';
            document.getElementById('transactionReferenceNo').textContent = '-';
            document.getElementById('accountNameContainer').style.display = 'none';
            document.getElementById('bankNameContainer').style.display = 'none';
            document.getElementById('referenceNoContainer').style.display = 'none';

            receiptSelect.innerHTML = '<option value="">-- Select a receipt --</option>';
            allTransactions = data.receipts;

            if (data.receipts.length === 0) {
                document.getElementById('transactionDetailsContainer').classList.add('hidden');
                document.getElementById('noTransactionsMessage').classList.remove('hidden');
            } else {
                data.receipts.forEach((receipt, index) => {
                    const option = document.createElement('option');
                    option.value = index;
                    option.textContent = receipt.receipt_no;
                    receiptSelect.appendChild(option);
                });
                document.getElementById('noTransactionsMessage').classList.add('hidden');
            }

            openTransactionModal();
        } catch (error) {
            Swal.fire('Error', 'Failed to fetch purchase transactions', 'error');
            console.error('Error:', error);
        }
    }

    receiptSelect.addEventListener('change', function () {
        if (this.value === '') {
            document.getElementById('transactionDetailsContainer').classList.add('hidden');
            // Reset payment method fields when no receipt is selected
            document.getElementById('transactionPaymentMethod').textContent = '-';
            document.getElementById('transactionAccountName').textContent = '-';
            document.getElementById('transactionBankName').textContent = '-';
            document.getElementById('transactionReferenceNo').textContent = '-';
            document.getElementById('accountNameContainer').style.display = 'none';
            document.getElementById('bankNameContainer').style.display = 'none';
            document.getElementById('referenceNoContainer').style.display = 'none';
            return;
        }

        const selectedTransaction = allTransactions[parseInt(this.value)];
        if (!selectedTransaction) return;

        // Update payment method fields based on selected transaction
        updatePaymentMethodFields(selectedTransaction);

        const dateTime = new Date(selectedTransaction.date_time);
        const formattedDate = dateTime.toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
        const formattedTime = dateTime.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });

        document.getElementById('transactionDateTime').textContent = `${formattedDate} ${formattedTime}`;
        document.getElementById('transactionReceiptNo').textContent = selectedTransaction.receipt_no;

        const productsTableBody = document.getElementById('productsTableBody');
        productsTableBody.innerHTML = '';

        selectedTransaction.products.forEach(product => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-100 transition';

            row.innerHTML = `
                <td class="px-4 py-3 text-left break-words whitespace-normal w-2/5">
                    <div class="break-words">${product.product_name}</div>
                </td>
                <td class="px-4 py-3 text-left whitespace-nowrap overflow-hidden text-ellipsis w-1/5">
                    ${product.serial_no}
                </td>
                <td class="px-4 py-3 text-left w-1/5">
                    ${product.warranty}
                </td>
                <td class="px-4 py-3 text-left whitespace-nowrap w-1/5">
                    ₱ ${parseFloat(product.unit_price).toFixed(2)}
                </td>
            `;

            productsTableBody.appendChild(row);
        });

        const discount = selectedTransaction.discount;
        const total = selectedTransaction.total_price;

        document.getElementById('subtotalAmount').textContent = `₱ ${selectedTransaction.subtotal.toFixed(2)}`;
        document.getElementById('discountAmount').textContent = `- ₱ ${discount.toFixed(2)}`;
        document.getElementById('totalAmount').textContent = `₱ ${parseFloat(total).toFixed(2)}`;

        // Update item count
        document.getElementById('itemCount').textContent = selectedTransaction.products.length;

        document.getElementById('transactionDetailsContainer').classList.remove('hidden');
    });

    // Initialize statistics on page load
    document.addEventListener('DOMContentLoaded', updateStatistics);

    // Show messages from server
    @if(session('success'))
        Swal.fire('Success', '{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        Swal.fire('Error', '{{ session('error') }}', 'error');
    @endif
</script>
@endsection