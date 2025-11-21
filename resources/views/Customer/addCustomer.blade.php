@extends('POS_SYSTEM.sidebar.app')

@section('title', 'Add Customer')
@section('name', 'Add Customer')
<style>
    .scrollbar-hide {
        overflow-y: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@section('content_items')
    <div class="container mx-auto px-4 py-6">
        {{-- Main Customer Form --}}
        <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-blue-200 pb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Add New Customer
            </h2>

            {{-- <form action="{{ route('customers.store') }}" method="POST" class="space-y-8">
                @csrf --}}

                {{-- CUSTOMER NAME SECTION --}}
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Customer Name
                    </h3>
                    <div class="grid md:grid-cols-3 gap-4">
                        {{-- First Name --}}
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Enter first name" required>
                        </div>

                        {{-- Middle Name --}}
                        <div>
                            <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Middle Name
                            </label>
                            <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Enter middle name">
                        </div>

                        {{-- Last Name --}}
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                placeholder="Enter last name" required>
                        </div>
                    </div>
                </div>

                {{-- ADDRESS SECTION --}}
                <div class="bg-green-50 p-6 rounded-lg border border-green-200 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Address Information
                    </h3>
                    <div class="space-y-4">
                        {{-- Street --}}
                        <div>
                            <label for="street" class="block text-sm font-medium text-gray-700 mb-2">
                                Street Address <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="street" name="street" value="{{ old('street') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                                placeholder="Enter street address" required>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            {{-- Barangay --}}
                            <div>
                                <label for="barangay" class="block text-sm font-medium text-gray-700 mb-2">
                                    Barangay <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="barangay" name="barangay" value="{{ old('barangay') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                                    placeholder="Enter barangay" required>
                            </div>

                            {{-- City/Province --}}
                            <div>
                                <label for="city_province" class="block text-sm font-medium text-gray-700 mb-2">
                                    City/Province <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="city_province" name="city_province"
                                    value="{{ old('city_province') }}"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                                    placeholder="Enter city/province" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONTACT DETAILS SECTION --}}
                <div class="bg-purple-50 p-6 rounded-lg border border-purple-200 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Contact Details
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                                placeholder="Enter email address">
                        </div>

                        {{-- Contact Number --}}
                        <div>
                            <label for="contact_no" class="block text-sm font-medium text-gray-700 mb-2">
                                Contact Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="contact_no" name="contact_no" value="{{ old('contact_no') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                                placeholder="Enter contact number" required>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button type="button" onclick="window.history.back()"
                        class="mr-4 inline-flex items-center gap-2 bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // SweetAlert messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#2563EB'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#DC2626'
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '{!! implode("<br>", $errors->all()) !!}',
                confirmButtonColor: '#DC2626'
            });
        @endif

        // Phone number formatting
        document.getElementById('contact_no').addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.slice(0, 3) + '-' + value.slice(3);
                } else if (value.length <= 10) {
                    value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6);
                } else {
                    value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 10);
                }
            }
            e.target.value = value;
        });
    </script>
@endsection