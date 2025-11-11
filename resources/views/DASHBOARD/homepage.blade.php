@extends('SIDEBAR.layouts')
@section('title', 'Dashboard')
@section('name', 'Dashboard')


@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow border">
            <p class="text-gray-500 text-sm">Employees</p>
            <h1 class="text-2xl font-bold"></h1>
        </div>
        <div class="bg-white p-4 rounded-xl shadow border">
            <p class="text-gray-500 text-sm">Total Customers</p>
            <h1 class="text-2xl font-bold"></h1>
        </div>

        <div class="bg-white p-4 rounded-xl shadow border">
            <p class="text-gray-500 text-sm">Total Products / Items in Stock</p>
            <h1 class="text-2xl font-bold"></h1>
        </div>

        <div class="bg-white p-4 rounded-xl shadow border">
            <p class="text-gray-500 text-sm">Today's Orders / Sales</p>
            <h1 class="text-2xl font-bold"></h1>
        </div>

    </div>
@endsection