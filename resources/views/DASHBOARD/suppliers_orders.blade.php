@extends('SIDEBAR.layouts')

@section('title', 'Suppliers Orders')
@section('name', 'PURCHASE ORDERS')

@section('content')
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

            <div class="bg-white p-4 rounded-xl shadow border">
                <p class="text-gray-500 text-sm">Total Orders</p>
                <h1 class="text-2xl font-bold"></h1>
            </div>

            <div class="bg-white p-4 rounded-xl shadow border">
                <p class="text-gray-500 text-sm">Ordered Items Over Time</p>
                <h1 class="text-2xl font-bold"></h1>
            </div>

            <div class="bg-white p-4 rounded-xl shadow border">
                <p class="text-gray-500 text-sm">Returns</p>
                <h1 class="text-2xl font-bold"></h1>
            </div>

            <div class="bg-white p-4 rounded-xl shadow border">
                <p class="text-gray-500 text-sm">Delivered Over Time</p>
                <h1 class="text-2xl font-bold"></h1>
            </div>

        </div>

        <div class="flex flex-wrap items-center  justify-between gap-3 mb-4">
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg border bg-gray-900 text-white">All</button>
                <button class="px-4 py-2 rounded-lg border bg-white text-gray-600">Pending</button>
                <button class="px-4 py-2 rounded-lg border bg-white text-gray-600">Unpaid</button>
            </div>

            <div>
                <a href="{{ route('suppliers.purchase-orders') }}" class="px-4 py-2 border rounded-lg bg-white">Purchase
                    Orders</a>
            </div>

        </div>

        {{-- SEARCH + SORT + FILTER --}}
        <div class="flex flex-wrap justify-between items-center mb-4 gap-3">

            <input type="text" placeholder="Find order"
                class="px-4 py-2 border rounded-lg w-1/2 mt-4 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                aria-label="Search suppliers">

            <div class="flex gap-2">
                <select class="px-4 py-2 border rounded-lg bg-white">Sort
                    <option value="">Sorting</option>
                </select>
                <select class="px-4 py-2 border rounded-lg bg-white">Filter
                    <option value="">Filtering</option>
                    <option value="">Today</option>
                </select>
                <button class="px-4 py-2 border rounded-lg bg-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v4H6v-4z" />
                    </svg>
                    Export
                </button>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-lg border overflow-x-auto mt-4">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-700 text-base">
                    <tr>
                        <th class="p-4 font-semibold">Order ID</th>
                        <th class="p-4 font-semibold">Date</th>
                        <th class="p-4 font-semibold">Supplier</th>
                        <th class="p-4 font-semibold">Quantity</th>
                        <th class="p-4 font-semibold">Unit Price</th>
                        <th class="p-4 font-semibold">Total</th>
                        <th class="p-4 font-semibold">Order Status</th>
                        <th class="p-4 font-semibold">Payment</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>

                <tbody class="text-base">
                    <tr class="border-t hover:bg-gray-50 transition">
                        <td class="p-4 text-blue-600 font-semibold"></td>
                        <td class="p-4"></td>
                        <td class="p-4"></td>
                        <td class="p-4"></td>
                        <td class="p-4"></td>
                        <td class="p-4"></td>
                        <td class="p-4"></td>
                        <td class="p-4"></td>
                        <td class="p-4"></td>
                    </tr>

                </tbody>
            </table>
        </div>


    </div>
@endsection