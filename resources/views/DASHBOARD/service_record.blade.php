@extends('SIDEBAR.layouts')

@section('title', 'Service Records')
@section('name', 'Service Records')

@section('content')
    <div class="bg-white border rounded-lg p-6 shadow-sm">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            {{-- Search Bar --}}
            <div class="relative w-full sm:flex-1 sm:max-w-md">
                <input type="text" id="searchInput" placeholder="Search service records..."
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                    aria-label="Search service records">
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

            {{-- Filter Buttons --}}
            <div class="flex gap-2">
                <button
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200 text-sm">
                    <i class="fas fa-filter mr-2"></i>All Records
                </button>
                <button
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 text-sm">
                    <i class="fas fa-clock mr-2"></i>Pending
                </button>
                <button
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200 text-sm">
                    <i class="fas fa-check-circle mr-2"></i>Completed
                </button>
            </div>
        </div>

        <div class="border-t border-gray-200 my-6"></div>

        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Service Records</h2>
                <p class="text-gray-600 mt-1">Track and manage all service transactions</p>
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
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Services</p>
                        <p class="text-2xl font-bold text-gray-900">145</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-5 border border-yellow-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">28</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">117</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200 shadow-sm hover:shadow-md transition duration-200">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500 rounded-xl shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">₱89,420</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Service Records Table --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100 text-gray-700 text-base">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-700">Full Name</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Service</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Fee</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Item</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Date Received</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Date Completed</th>
                            <th class="px-6 py-4 font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-4 text-center font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Sample Data Rows --}}
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">John Mark Santos</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Laptop Repair</td>
                            <td class="px-6 py-4 text-sm text-green-600 font-semibold">₱2,500.00</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Dell Inspiron 15</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Dec 1, 2024</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Dec 3, 2024</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    class="inline-flex items-center px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition duration-200 text-sm">
                                    <i class="fas fa-eye mr-2"></i>View
                                </button>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">Maria Clara Cruz</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Phone Screen Replacement</td>
                            <td class="px-6 py-4 text-sm text-green-600 font-semibold">₱1,800.00</td>
                            <td class="px-6 py-4 text-sm text-gray-500">iPhone 12 Pro</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Dec 2, 2024</td>
                            <td class="px-6 py-4 text-sm text-gray-500">-</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>In Progress
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    class="inline-flex items-center px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition duration-200 text-sm">
                                    <i class="fas fa-eye mr-2"></i>View
                                </button>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">Robert Garcia</td>
                            <td class="px-6 py-4 text-sm text-gray-900">PC Assembly</td>
                            <td class="px-6 py-4 text-sm text-green-600 font-semibold">₱3,200.00</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Custom Gaming PC</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Nov 28, 2024</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Dec 1, 2024</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    class="inline-flex items-center px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition duration-200 text-sm">
                                    <i class="fas fa-eye mr-2"></i>View
                                </button>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">Anna Marie Dela Cruz</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Data Recovery</td>
                            <td class="px-6 py-4 text-sm text-green-600 font-semibold">₱4,500.00</td>
                            <td class="px-6 py-4 text-sm text-gray-500">External HDD 1TB</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Dec 4, 2024</td>
                            <td class="px-6 py-4 text-sm text-gray-500">-</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-hourglass-half mr-1"></i>Pending
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    class="inline-flex items-center px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition duration-200 text-sm">
                                    <i class="fas fa-eye mr-2"></i>View
                                </button>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">James Rodriguez</td>
                            <td class="px-6 py-4 text-sm text-gray-900">Software Installation</td>
                            <td class="px-6 py-4 text-sm text-green-600 font-semibold">₱800.00</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Windows 11 Pro</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Dec 5, 2024</td>
                            <td class="px-6 py-4 text-sm text-gray-500">Dec 5, 2024</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Completed
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button
                                    class="inline-flex items-center px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition duration-200 text-sm">
                                    <i class="fas fa-eye mr-2"></i>View
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-600">
                        Showing 1 to 5 of 145 results
                    </div>
                    <div class="flex space-x-2">
                        <button
                            class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-500 hover:bg-gray-100 transition">
                            Previous
                        </button>
                        <button
                            class="px-3 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition">
                            1
                        </button>
                        <button
                            class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                            2
                        </button>
                        <button
                            class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition">
                            3
                        </button>
                        <button
                            class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-500 hover:bg-gray-100 transition">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection