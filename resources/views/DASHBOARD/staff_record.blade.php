@extends('SIDEBAR.layouts')
@section('title', 'Staff Management')
@section('name', 'Staff Management')
@section('content')
    <div class="space-y-6">
        <!-- Header with Search and Add Button -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <!-- Search Bar -->
                    <div class="relative flex-1 sm:flex-none min-w-[300px]">
                        <input type="text" id="searchInput" placeholder="Search employees by name, role, address..."
                            class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        <svg class="absolute left-4 top-3.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <!-- Role Filter -->
                    <select id="roleFilter"
                        class="px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out bg-white min-w-[180px]">
                        <option value="">All Roles</option>
                        <option value="Staff">Staff</option>
                        <option value="Technical">Technical</option>
                        <option value="Cashier">Cashier</option>
                        <option value="Assistant">Assistant</option>
                    </select>
                </div>

                <!-- Add Employee Button -->
                <a href="{{ route('add.employee') }}"
                    class="flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150 ease-in-out w-full lg:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Add Employee
                </a>
            </div>
        </div>

        <!-- Employees Table and Pagination Info -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Pagination Info -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ $employees->firstItem() ?? 0 }}</span>
                        to
                        <span class="font-medium">{{ $employees->lastItem() ?? 0 }}</span>
                        of
                        <span class="font-medium">{{ $employees->total() }}</span>
                        results
                    </p>

                    @if($employees->total() > 50)
                        <p class="text-sm text-gray-600">
                            Page <span class="font-medium">{{ $employees->currentPage() }}</span>
                            of <span class="font-medium">{{ $employees->lastPage() }}</span>
                        </p>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Employee</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Address</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Gender</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="employeesTable">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                            <span class="text-indigo-600 font-semibold">
                                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $employee->first_name }} {{ $employee->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $employee->street }}</div>
                                    <div class="text-sm text-gray-600">{{ $employee->barangay }}</div>
                                    <div class="text-sm text-gray-500">{{ $employee->city }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $employee->phone_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $employee->role == 'Staff' ? 'bg-purple-100 text-purple-800' : ($employee->role == 'Technical' ? 'bg-pink-100 text-pink-800' : ($employee->role == 'Cashier' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800')) }}">
                                        {{ $employee->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $employee->gender == 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ ucfirst($employee->gender) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button onclick="openEditModal({{ $employee->id }})"
                                            class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-2 rounded-lg transition duration-150 ease-in-out flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-600 mb-2">No employees found</p>
                                        <p class="text-gray-500 mb-4">Get started by adding your first employee.</p>
                                        <a href="{{ route('add.employee') }}"
                                            class="text-indigo-600 hover:text-indigo-700 font-medium">
                                            Add New Employee
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            @if($employees->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Showing info -->
                        <div class="text-sm text-gray-700">
                            Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }}
                            results
                        </div>

                        <!-- Pagination Links -->
                        <nav class="flex items-center space-x-2">
                            <!-- Previous Page Link -->
                            @if ($employees->onFirstPage())
                                <span class="px-3 py-2 text-gray-400 border border-gray-300 rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $employees->previousPageUrl() }}"
                                    class="px-3 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif

                            <!-- Page Numbers -->
                            <div class="flex space-x-1">
                                @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                                    @if ($page == $employees->currentPage())
                                        <span
                                            class="px-4 py-2 bg-indigo-600 text-white border border-indigo-600 rounded-lg font-medium">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150">
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Next Page Link -->
                            @if ($employees->hasMorePages())
                                <a href="{{ $employees->nextPageUrl() }}"
                                    class="px-3 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span class="px-3 py-2 text-gray-400 border border-gray-300 rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Employee
                </h3>
            </div>

            <form id="editForm" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_employee_id" name="id">

                <!-- Personal Information -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name
                            *</label>
                        <input type="text" id="edit_first_name" name="first_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                    </div>
                    <div>
                        <label for="edit_last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" id="edit_last_name" name="last_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-800 border-b border-gray-200 pb-2">Address Information</h4>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label for="edit_street" class="block text-sm font-medium text-gray-700 mb-1">Street *</label>
                            <input type="text" id="edit_street" name="street"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                        </div>
                        <div>
                            <label for="edit_barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay
                                *</label>
                            <input type="text" id="edit_barangay" name="barangay"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                        </div>
                        <div>
                            <label for="edit_city" class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                            <input type="text" id="edit_city" name="city"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                        </div>
                    </div>
                </div>

                <!-- Contact & Gender -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number
                            *</label>
                        <input type="tel" id="edit_phone_number" name="phone_number"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="male"
                                    class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-gray-700">Male</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="female"
                                    class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-gray-700">Female</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Employment Details -->
                <div>
                    <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                    <select id="edit_role" name="role"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                        <option value="">Select Role</option>
                        <option value="Staff">Staff</option>
                        <option value="Assistant">Assistant</option>
                        <option value="Technical">Technical</option>
                        <option value="Cashier">Cashier</option>
                    </select>
                </div>

                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()"
                        class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 transition duration-200">
                        Update Employee
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Simple Edit Modal Functions
        function openEditModal(employeeId) {
            // Set the form action
            document.getElementById('editForm').action = `/employees/${employeeId}`;

            // Show modal
            document.getElementById('editModal').classList.remove('hidden');

            // Fetch employee data
            fetch(`/employees/${employeeId}/edit`)
                .then(response => response.json())
                .then(employee => {
                    // Populate form fields
                    document.getElementById('edit_first_name').value = employee.first_name;
                    document.getElementById('edit_last_name').value = employee.last_name;
                    document.getElementById('edit_street').value = employee.street;
                    document.getElementById('edit_barangay').value = employee.barangay;
                    document.getElementById('edit_city').value = employee.city;
                    document.getElementById('edit_phone_number').value = employee.phone_number;
                    document.getElementById('edit_role').value = employee.role;

                    // Set gender
                    document.querySelectorAll('input[name="gender"]').forEach(radio => {
                        radio.checked = radio.value === employee.gender;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load employee data');
                });
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function (e) {
            if (e.target === this) closeEditModal();
        });

        // Simple search and filter (client-side for current page only)
        document.getElementById('searchInput').addEventListener('input', filterEmployees);
        document.getElementById('roleFilter').addEventListener('change', filterEmployees);

        function filterEmployees() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const role = document.getElementById('roleFilter').value;
            const rows = document.querySelectorAll('#employeesTable tr');

            rows.forEach(row => {
                if (row.cells.length < 6) return;

                const name = row.cells[0].textContent.toLowerCase();
                const address = row.cells[1].textContent.toLowerCase();
                const rowRole = row.cells[3].textContent.trim();

                const show = (name.includes(search) || address.includes(search)) &&
                    (!role || rowRole === role);

                row.style.display = show ? '' : 'none';
            });
        }
    </script>
@endsection