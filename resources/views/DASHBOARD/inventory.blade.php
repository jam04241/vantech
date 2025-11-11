@extends('SIDEBAR.layouts')
@section('name', 'Inventory')
@section('content')

    <div class="flex items-center gap-3 mb-4">

        <a href="#" class="px-4 py-2 rounded-lg bg-gray-600 text-white font-medium shadow ">
            Brand History
        </a>

        <a href="#" class="px-4 py-2 rounded-lg bg-gray-600 text-white font-medium shadow">
            Categories History
        </a>

    </div>

    <div class="py-6 rounded-xl">
        <div class="flex flex-col sm:flex-row justify-between gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto flex-1">
                <input type="text" placeholder="Search inventory..."
                    class="w-1/2 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                <select
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white">
                    <option value="">All Categories</option>
                    <option value="processors">Processors (CPU)</option>
                    <option value="motherboards">Motherboards</option>
                    <option value="graphics-cards">Graphics Cards (GPU)</option>
                    <option value="memory">Memory (RAM)</option>
                    <option value="storage">Storage (SSD/HDD)</option>
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
    <div class="bg-white rounded-2xl shadow-lg border overflow-x-auto mt-4">
        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-700 text-base">
                <tr>
                    <th class="p-4 font-semibold">ID</th>
                    <th class="p-4 font-semibold">Product</th>
                    <th class="p-4 font-semibold">Serial Number</th>
                    <th class="p-4 font-semibold">Warranty</th>
                    <th class="p-4 font-semibold">Brand</th>
                    <th class="p-4 font-semibold">Categories</th>
                    <th class="p-4 font-semibold">Stocks</th>
                    <th class="p-4 font-semibold"></th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>

            <tbody class="text-base">
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="p-4 text-blue-600 font-semibold"></td>
                    <td class="p-4"></td>
                    <td class="p-4"></td>
                    <td class="p-4"></td>

                    <td class="p-4">

                    </td>

                    <td class="p-4"></td>

                    <td class="p-4">
                    </td>
                </tr>

            </tbody>
        </table>
    </div>


@endsection
