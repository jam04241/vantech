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
                    <option value="staff">Staff</option>
                    <option value="manager">Manager</option>
                    <option value="ceo">CEO</option>
                    <option value="admin">Admin</option>
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

        <!-- Staff Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div
                class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center overflow-hidden">
                            <span class="text-indigo-600 font-semibold text-lg">JD</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">John Doe</h3>
                            <p class="text-gray-600">Administrator</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button
                            class="flex items-center justify-center p-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>

                        </button>
                        <button
                            class="flex items-center justify-center p-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div
                class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="h-16 w-16 rounded-full bg-green-100 flex items-center justify-center overflow-hidden">
                            <span class="text-green-600 font-semibold text-lg">SJ</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Sarah Johnson</h3>
                            <p class="text-gray-600">Manager</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button
                            class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit
                        </button>
                        <button
                            class="flex items-center justify-center p-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div
                class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                <div class="p-6">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="h-16 w-16 rounded-full bg-purple-100 flex items-center justify-center overflow-hidden">
                            <span class="text-purple-600 font-semibold text-lg">MR</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Michael Roberts</h3>
                            <p class="text-gray-600">Developer</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button
                            class="flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit
                        </button>
                        <button
                            class="flex items-center justify-center p-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection