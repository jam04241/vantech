@extends('SIDEBAR.layouts')
@section('title', 'Add Employee')
@section('name', 'Add Employee')

@section('btn')
    <a href="{{ route('staff') }}"
        class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </a>
@endsection
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
@section('content')
    <div class="container mx-auto px-4 py-6">
        {{-- Main Employee Form --}}
        <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-blue-200 pb-3 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                Add New Employee
            </h2>

            {{--
            <form action="{{ route('employees.store') }}" method="POST" class="space-y-8">
                @csrf
                --}}

                {{-- EMPLOYEE NAME SECTION --}}
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Employee Name
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

                {{-- CONTACT AND GENDER SECTION --}}
                <div class="bg-purple-50 p-6 rounded-lg border border-purple-200 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        Contact Details & Gender
                    </h3>
                    <div class="grid md:grid-cols-3 gap-6">
                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200"
                                placeholder="Enter email address" required>
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

                        {{-- Gender --}}
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select id="gender" name="gender" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition duration-200">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                <option value="prefer_not_to_say" {{ old('gender') == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- EMPLOYMENT DETAILS SECTION --}}
                <div class="bg-green-50 p-6 rounded-lg border border-green-200 mb-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Employment Details
                    </h3>
                    <div class="grid md:grid-cols-2 gap-6">
                        {{-- Position --}}
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                                Position <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="position" name="position" value="{{ old('position') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                                placeholder="Enter position" required>
                        </div>

                        {{-- Department --}}
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="department" name="department" value="{{ old('department') }}"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                                placeholder="Enter department" required>
                        </div>
                    </div>
                </div>

                {{-- SUBMIT BUTTON --}}
                {{--
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
                        Save Employee
                    </button>
                </div>
                --}}
                {{--
            </form> --}}

            {{-- SUBMIT --}}
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Product
                </button>
            </div>
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