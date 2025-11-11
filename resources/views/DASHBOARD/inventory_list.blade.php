@extends('SIDEBAR.layouts')
@section('name', 'Inventory List')
@section('content')
    <div class="flex items-center gap-2 w-full sm:w-auto flex-1">
    <input type="text" placeholder="Search inventory..."
        class="w-1/3 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">

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
    <div class="py-6 rounded-xl">
        <table class="min-w-full mt-4">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2 text-left">Product Name</th>
                    <th class="px-4 py-2 text-left">Brand</th>
                    <th class="px-4 py-2 text-left">Category</th>
                    <th class="px-4 py-2 text-left">Price</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection