@extends('SIDEBAR.layouts')
@section('title', 'Add Employee')
@section('name', 'Add Employee')

@section('btn')
<a href="{{ route('staff.record') }}"
    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
    < Back to Staff
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="bg-blue-600 text-white p-6">
            <h2 class="text-2xl font-semibold">Add New Employee</h2>
            <p class="opacity-90 text-sm">Fill in the employee details below</p>
        </div>

        <!-- Form -->
        <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-8">
            @csrf

            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-medium border-b pb-2 text-gray-700">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="text-gray-700 font-semibold">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            class="mt-1 px-3 py-2 border rounded-lg w-full focus:ring focus:ring-blue-300"
                            required>
                        @error('first_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-gray-700 font-semibold">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            class="mt-1 px-3 py-2 border rounded-lg w-full focus:ring focus:ring-blue-300"
                            required>
                        @error('last_name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div>
                <h3 class="text-lg font-medium border-b pb-2 text-gray-700">Address Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="text-gray-700 font-semibold">Street *</label>
                        <input type="text" name="street" value="{{ old('street') }}"
                            class="mt-1 px-3 py-2 border rounded-lg w-full focus:ring focus:ring-blue-300">
                        @error('street') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-gray-700 font-semibold">Barangay *</label>
                        <input type="text" name="barangay" value="{{ old('barangay') }}"
                            class="mt-1 px-3 py-2 border rounded-lg w-full focus:ring focus:ring-blue-300">
                        @error('barangay') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-gray-700 font-semibold">City *</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="mt-1 px-3 py-2 border rounded-lg w-full focus:ring focus:ring-blue-300">
                        @error('city') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Contact & Gender -->
            <div>
                <h3 class="text-lg font-medium border-b pb-2 text-gray-700">Contact Details & Gender</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="text-gray-700 font-semibold">Phone Number *</label>
                        <input type="tel"  name="phone_number" value="{{ old('phone_number') }}"
                            class="mt-1 px-3 py-2 border rounded-lg w-full focus:ring focus:ring-blue-300" required>
                        @error('phone_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-gray-700 font-semibold block">Gender *</label>
                        <div class="flex gap-6 mt-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="male"
                                    {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                Male
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="female"
                                    {{ old('gender') == 'female' ? 'checked' : '' }} required>
                                Female
                            </label>
                        </div>
                        @error('gender') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Employment Details -->
            <div>
                <h3 class="text-lg font-medium border-b pb-2 text-gray-700">Employment Details</h3>
                <div class="mt-4">
                    <label class="text-gray-700 font-semibold">Role *</label>
                    <select name="role"
                        class="mt-1 px-3 py-2 border rounded-lg w-full focus:ring focus:ring-blue-300" required>
                        <option value="">Select Role</option>
                        <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                        <option value="Technical" {{ old('role') == 'Technical' ? 'selected' : '' }}>Technical</option>
                        <option value="Cashier" {{ old('role') == 'Cashier' ? 'selected' : '' }}>Cashier</option>
                    </select>
                    @error('role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 border-t pt-4">
                <button type="button" onclick="window.history.back()"
                    class="px-5 py-2 rounded-lg border bg-gray-100 hover:bg-gray-200 text-gray-700">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-md">
                    Save Employee
                </button>
            </div>

        </form>
    </div>
</div>


@endsection
