@extends('SIDEBAR.layouts')
@section('title', 'Staff')
@section('name', 'Staff')
@section('content')
    <div class="bg-white rounded-xl shadow-sm p-6">
        <!-- Header with Search and Add Button -->
        <!-- Header with Search and Add Button -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <!-- Search Bar -->
                <div class="relative flex-1 sm:flex-none">
                    <input type="text" placeholder="Search employees by name, role, or email..."
                        class="w-full sm:w-80 px-4 py-2 pl-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <!-- Role Dropdown -->
                <select
                    class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white w-full sm:w-auto">
                    <option value="" selected>Select Role</option>
                </select>
            </div>

            <!-- Add Employee Button -->
            <a href="{{ route('add.employee') }}" class="flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg
                    hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition
                    duration-150 ease-in-out w-full sm:w-auto mt-4 sm:mt-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Add Employee
            </a>
        </div>
    </div>
@endsection