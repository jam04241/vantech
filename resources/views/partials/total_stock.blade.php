@extends('SIDEBAR.layouts')

@section('title', 'Total Stocks')
@section('btn')
    <a href="{{ route('inventory.list') }}"
        class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </a>
@endsection

@section('name', 'Total Stocks')
@section('content')


    <div class="overflow-x-auto border">
        <table class="min-w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-200 bg-white">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Brand</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Prev Qty
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">New Stock
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-sm text-gray-800">Smartphone X5</td>
                    <td class="px-4 py-3 text-sm text-gray-700">123</td>
                    <td class="px-4 py-3 text-sm text-gray-700">123</td>
                    <td class="px-4 py-3 text-sm text-gray-700">15</td>
                    <td class="px-4 py-3 text-sm text-gray-700">123</td>
                    <td class="px-4 py-3 text-sm text-gray-500">Oct 18, 2023</td>
                </tr>
            </tbody>
        </table>
    </div>


@endsection