@extends('SIDEBAR.layouts')

@section('title', 'Customer Management')
@section('name', 'Customer Management')

@section('content')
    <div class="space-y-8 bg-gray-50 min-h-screen p-6">

        <!-- Search, Filters, and Add Button -->
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 w-full">

                <!-- Search + Filters -->
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                    <!-- Search Bar -->
                    <div class="relative w-60">
                        <input type="search" placeholder="Search..."
                            class="w-full px-3 py-2 pl-9 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition">
                        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                <!-- Add Button -->
                <button onclick="openModal()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm flex items-center gap-1 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Customer
                </button>

            </div>
        </div>

        <!-- Customer Table -->
        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Customer List</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">First
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Last
                                Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Street</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Barangay</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                City/Province</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $customer->first_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $customer->last_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $customer->contact_no }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $customer->gender }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $customer->street }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $customer->brgy }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $customer->city_province }}</td>

                                <td class="px-6 py-4 text-center">
                                    <button onclick="editCustomer({{ $customer->id }})"
                                        class="inline-flex items-center px-3 py-2 bg-indigo-50 text-indigo-600 rounded-md hover:bg-indigo-100 transition">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Simple Add/Edit Customer Modal -->
        <div id="customerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto hidden">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center pb-3 border-b">
                        <h3 id="modalTitle" class="text-xl font-semibold text-gray-900">Add New Customer</h3>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form id="customerForm" class="space-y-4 mt-4">
                        @csrf
                        <input type="hidden" id="customerId" name="id">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name *</label>
                            <input type="text" id="firstName" name="first_name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                            <input type="text" id="lastName" name="last_name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact Number *</label>
                            <input type="text" id="contactNo" name="contact_no" required
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gender *</label>
                            <select id="gender" name="gender" required
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Street</label>
                            <input type="text" id="street" name="street"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Barangay</label>
                            <input type="text" id="brgy" name="brgy"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">City/Province</label>
                            <input type="text" id="cityProvince" name="city_province"
                                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end space-x-3 pt-4 border-t">
                            <button type="button" onclick="closeModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                                Cancel
                            </button>
                            <button type="submit" id="submitBtn"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                                Save Customer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openModal() {
            document.getElementById('customerModal').classList.remove('hidden');
            document.getElementById('modalTitle').textContent = 'Add New Customer';
            document.getElementById('customerForm').reset();
            document.getElementById('customerId').value = '';
            document.getElementById('submitBtn').textContent = 'Save Customer';
        }

        function closeModal() {
            document.getElementById('customerModal').classList.add('hidden');
        }

        async function editCustomer(id) {
            try {
                const response = await fetch(`/customers/${id}`);
                const customer = await response.json();

                document.getElementById('customerModal').classList.remove('hidden');
                document.getElementById('modalTitle').textContent = 'Edit Customer';
                document.getElementById('customerId').value = customer.id;
                document.getElementById('firstName').value = customer.first_name;
                document.getElementById('lastName').value = customer.last_name;
                document.getElementById('contactNo').value = customer.contact_no;
                document.getElementById('gender').value = customer.gender;
                document.getElementById('street').value = customer.street;
                document.getElementById('brgy').value = customer.brgy;
                document.getElementById('cityProvince').value = customer.city_province;
                document.getElementById('submitBtn').textContent = 'Update Customer';
            } catch (error) {
                Swal.fire('Error', 'Failed to load customer data', 'error');
            }
        }

        document.getElementById('customerForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const customerId = document.getElementById('customerId').value;
            const isEditing = customerId !== '';

            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';

            // Create form data object
            const formData = {
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                contact_no: document.getElementById('contactNo').value,
                gender: document.getElementById('gender').value,
                street: document.getElementById('street').value,
                brgy: document.getElementById('brgy').value,
                city_province: document.getElementById('cityProvince').value,
                _token: document.querySelector('input[name="_token"]').value
            };

            const url = isEditing ? `/customers/${customerId}` : '/customers';
            const method = isEditing ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: isEditing ? 'Customer updated successfully!' : 'Customer added successfully!',
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = 'Please fix the following errors:\n';
                        Object.values(data.errors).forEach(error => {
                            errorMessage += `• ${error[0]}\n`;
                        });
                        Swal.fire('Validation Error', errorMessage, 'error');
                    } else {
                        throw new Error(data.message || 'Failed to save customer');
                    }
                }
            } catch (error) {
                Swal.fire('Error', error.message, 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = isEditing ? 'Update Customer' : 'Save Customer';
            }
        });

        // Close modal when clicking outside
        document.getElementById('customerModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Show messages from server
        @if(session('success'))
            Swal.fire('Success', '{{ session('success') }}', 'success');
        @endif

        @if(session('error'))
            Swal.fire('Error', '{{ session('error') }}', 'error');
        @endif
    </script>
@endsection