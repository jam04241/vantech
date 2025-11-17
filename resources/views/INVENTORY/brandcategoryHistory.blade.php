@extends('SIDEBAR.layouts')
@section('title', 'Brand & Category History')
@section('name', 'Brand & Category History')
@section('content')
    <div class="space-y-6">
        {{-- Brand Table --}}
        <div class="bg-white rounded-2xl shadow-lg border overflow-x-auto">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Brands</h2>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-700 text-base">
                    <tr>
                        <th class="p-4 font-semibold">Brand</th>
                        <th class="p-4 font-semibold">Date Added</th>
                        <th class="p-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-base">
                    @isset($brands)
                        @forelse($brands as $brand)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="p-4 font-semibold text-gray-800">
                                    {{ $brand->brand_name }}
                                </td>
                                <td class="p-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($brand->created_at)->format('M d, Y') }}
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center space-x-3">
                                        {{-- Edit Icon --}}
                                        <button onclick="editBrand('{{ $brand->id }}')"
                                            class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition duration-200"
                                            title="Edit Brand">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        {{-- Deactivate Icon --}}
                                        <button onclick="deactivateBrand('{{ $brand->id }}')"
                                            class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition duration-200"
                                            title="Deactivate Brand">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td colspan="3" class="p-8 text-center text-gray-500">
                                    No brands available.
                                </td>
                            </tr>
                        @endforelse
                    @else
                        <tr class="border-t">
                            <td colspan="3" class="p-8 text-center text-gray-500">
                                No brands available.
                            </td>
                        </tr>
                    @endisset
                </tbody>
            </table>
        </div>

        {{-- Category Table --}}
        <div class="bg-white rounded-2xl shadow-lg border overflow-x-auto">
            <div class="p-4 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Categories</h2>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-700 text-base">
                    <tr>
                        <th class="p-4 font-semibold">Category</th>
                        <th class="p-4 font-semibold">Date Added</th>
                        <th class="p-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-base">
                    @isset($categories)
                        @forelse($categories as $category)
                            <tr class="border-t hover:bg-gray-50 transition">
                                <td class="p-4 font-semibold text-gray-800">
                                    {{ $category->category_name }}
                                </td>
                                <td class="p-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($category->created_at)->format('M d, Y') }}
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center space-x-3">
                                        {{-- Edit Icon --}}
                                        <button onclick="editCategory('{{ $category->id }}')"
                                            class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition duration-200"
                                            title="Edit Category">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        {{-- Deactivate Icon --}}
                                        <button onclick="deactivateCategory('{{ $category->id }}')"
                                            class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition duration-200"
                                            title="Deactivate Category">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td colspan="3" class="p-8 text-center text-gray-500">
                                    No categories available.
                                </td>
                            </tr>
                        @endforelse
                    @else
                        <tr class="border-t">
                            <td colspan="3" class="p-8 text-center text-gray-500">
                                No categories available.
                            </td>
                        </tr>
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
@endsection