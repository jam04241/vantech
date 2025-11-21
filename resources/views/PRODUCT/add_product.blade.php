@extends('SIDEBAR.layouts')

@section('btn')
    <a href="{{ route('inventory') }}"
        class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Back
    </a>
@endsection

@section('name', 'Add Product')

@section('content')
                {{-- Action buttons --}}

                <div class="flex items-center gap-3 mb-6">
                    <button id="openBrandModal"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-black rounded-lg shadow-sm bg-white text-black hover:bg-gray-100 focus:ring-2 focus:ring-black transition duration-200 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Brand
                    </button>

                    <button id="openCategoryModal"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-black rounded-lg shadow-sm bg-white text-black hover:bg-gray-100 focus:ring-2 focus:ring-black transition duration-200 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Category
                    </button>

                    <!-- Push camera button to the far right -->
                    <div class="ml-auto">
                        <button id="openCamera"
                            class="inline-flex items-center gap-2 px-4 py-2 border border-black rounded-lg shadow-sm bg-white text-black hover:bg-gray-100 focus:ring-2 focus:ring-black transition duration-200 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M1 21v-5h2v3h3v2zm17 0v-2h3v-3h2v5zM4 18V6h2v12zm3 0V6h1v12zm3 0V6h2v12zm3 0V6h3v12zm4 0V6h1v12zm2 0V6h1v12zM1 8V3h5v2H3v3zm20 0V5h-3V3h5v5z" />
                            </svg>
                            Scan Barcode
                        </button>
                    </div>
                </div>

                {{-- Main Product Form --}}
                <div class="bg-white p-8 rounded-xl shadow-lg w-full border border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b-2 border-indigo-200 pb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Add New Product
                    </h2>

                    {{-- ADDED: Form with ID for SweetAlert confirmation --}}
                    <form id="addProductForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        {{-- PRODUCT INFORMATION --}}
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Product Information
                            </h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- ============= AUTO-SUGGESTION PRODUCT NAME INPUT ============= -->
                                <div class="relative">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="product_name"
                                        value="{{ old('product_name') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                        placeholder="Enter product name or select from suggestions" required
                                        autocomplete="off">
                                    <!-- ============= AUTO-SUGGESTION DROPDOWN ============= -->
                                    <div id="productSuggestions" 
                                        class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden z-50 max-h-64 overflow-y-auto">
                                    </div>
                                    <!-- ============= END AUTO-SUGGESTION DROPDOWN ============= -->
                                </div>
                                <!-- ============= END AUTO-SUGGESTION PRODUCT NAME INPUT ============= -->
                                <div>
                                    <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">
                                        Serial Number</label>
                                    <input type="text" id="serial_number" name="serial_number"
                                        value="{{ old('serial_number') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                        placeholder="Enter serial number (optional)" autofocus required>
                                </div>
                            </div>
                        </div>

            {{-- CONDITION CHECKBOX --}}
            <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200">
                <div class="flex items-start gap-3">
                    <input type="checkbox" id="is_used" name="is_used" value="1"
                        class="mt-1 w-5 h-5 text-yellow-600 border-gray-300 rounded focus:ring-2 focus:ring-yellow-500"
                        {{ old('is_used') ? 'checked' : '' }}>
                    <div>
                        <label for="is_used" class="block text-sm font-semibold text-gray-800 cursor-pointer">
                            This is a Used Product
                        </label>
                        <p class="text-xs text-gray-600 mt-1">
                            Check this box if the product is used/second-hand. Used products don't require a supplier.
                        </p>
                    </div>
                </div>
            </div>
            {{-- RELATIONAL FIELDS --}}
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    Relations
                </h3>
                        <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span
                                class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select id="brand_id" name="brand_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200" required>
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->brand_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                     <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Supplier <span id="supplier" class="text-red-500">*</span>
                        </label>
                        <select id="supplier_id" name="supplier_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->supplier_name }} - {{ $supplier->company_name }}
                             </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

                        {{-- STOCK INFO --}}
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                Stock Information
                            </h3>
                            <div class="grid md:grid-cols-3 gap-6">

                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (₱) <span
                                            class="text-red-500">*</span></label>
                                    <input type="number" id="price" name="price" step="0.01"
                                        value="{{ old('price') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                        placeholder="Enter price" required>
                                </div>

                                <div>
                                    <label for="warranty" class="block text-sm font-medium text-gray-700 mb-2">Warranty</label>
                                    <input type="text" id="warranty" name="warranty_period"
                                        value="{{ old('warranty_period') }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                        placeholder="e.g. 1 year" required>
                                </div>
                            </div>
                        </div>

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
                    </form>
                </div>


                {{-- ================= MODALS ================= --}}

        {{-- Brand Modal --}}
        <div id="brandModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8 relative transform scale-95 transition-transform duration-300"
                id="brandModalContent">
                <button id="closeBrandModal"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="flex items-center gap-3 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Add New Brand</h3>
                </div>
                <form id="brandForm" action="{{ route('brands.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-2">Brand Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="brand_name" id="brand_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            placeholder="Enter brand name" required>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelBrandModal"
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-500 transition duration-200">Cancel</button>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">Save
                            Brand</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Category Modal --}}
        <div id="categoryModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8 relative transform scale-95 transition-transform duration-300"
                id="categoryModalContent">
                <button id="closeCategoryModal"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="flex items-center gap-3 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Add New Category</h3>
                </div>
                <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="category_name" class="block text-sm font-medium text-gray-700 mb-2">Category Name <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="category_name" id="category_name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                            placeholder="Enter category name" required>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelCategoryModal"
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-500 transition duration-200">Cancel</button>
                        <button type="submit"
                            class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">Save
                            Category</button>
                    </div>
                </form>
            </div>
        </div>

                {{-- QR Scanner Modal --}}
                <div id="qrScannerModal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300">
                    <div
                        class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 relative transform scale-95 transition-transform duration-300">
                        <button id="closeQrModal"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-3 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M1 21v-5h2v3h3v2zm17 0v-2h3v-3h2v5zM4 18V6h2v12zm3 0V6h1v12zm3 0V6h2v12zm3 0V6h3v12zm4 0V6h1v12zm2 0V6h1v12zM1 8V3h5v2H3v3zm20 0V5h-3V3h5v5z" />
                            </svg>
                            <h3 class="text-xl font-bold text-gray-800">Scan Barcode</h3>
                        </div>

                        <div id="reader" class="w-full h-64 border-2 border-dashed border-gray-300 rounded-lg mb-4"></div>

                        <div id="qrResult" class="text-center text-sm text-gray-600 mb-4"></div>

                        <div class="flex justify-between gap-3">
                            <button id="switchCamera"
                                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-500 transition duration-200">
                                Switch Camera
                            </button>
                            <button id="stopScanner"
                                class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-500 transition duration-200">
                                Stop Scanner
                            </button>
                        </div>
                    </div>
                </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus on serial number field on page load
            const serialNumberInput = document.getElementById('serial_number');
            if (serialNumberInput) {
                serialNumberInput.focus();
            }

            // ============= AUTO-SUGGESTION PRODUCT NAME FUNCTIONALITY =============
            // Get the product name input and suggestions dropdown
            const productNameInput = document.getElementById('name');
            const suggestionsDropdown = document.getElementById('productSuggestions');
            
            // Event listener for input changes to fetch suggestions
            if (productNameInput) {
                productNameInput.addEventListener('input', async function() {
                    const searchTerm = this.value.trim();
                    
                    // Show suggestions only if there's input
                    if (searchTerm.length > 0) {
                        try {
                            // Fetch recent products from API
                            const response = await fetch(`/api/products/recent?search=${encodeURIComponent(searchTerm)}`);
                            const products = await response.json();
                            
                            // Clear previous suggestions
                            suggestionsDropdown.innerHTML = '';
                            
                            if (products.length > 0) {
                                // Display suggestions
                                products.forEach(product => {
                                    const suggestionItem = document.createElement('div');
                                    suggestionItem.className = 'px-4 py-3 hover:bg-indigo-50 cursor-pointer border-b border-gray-200 transition duration-150';
                                    suggestionItem.innerHTML = `
                                        <div class="font-medium text-gray-800">${product.product_name}</div>
                                        <div class="text-sm text-gray-600">
                                            Brand: ${product.brand_name} | Category: ${product.category_name} | Price: ₱${parseFloat(product.price).toFixed(2)}
                                        </div>
                                    `;
                                    
                                    // Click handler to fill form fields
                                    suggestionItem.addEventListener('click', function() {
                                        // ============= AUTO-FILL FORM FIELDS FROM SUGGESTION =============
                                        productNameInput.value = product.product_name;
                                        document.getElementById('brand_id').value = product.brand_id || '';
                                        document.getElementById('category_id').value = product.category_id || '';
                                        document.getElementById('price').value = product.price || '';
                                        // ============= END AUTO-FILL FORM FIELDS =============
                                        
                                        // Hide suggestions dropdown
                                        suggestionsDropdown.classList.add('hidden');
                                    });
                                    
                                    suggestionsDropdown.appendChild(suggestionItem);
                                });
                                
                                // Show suggestions dropdown
                                suggestionsDropdown.classList.remove('hidden');
                            } else {
                                // Hide dropdown if no suggestions
                                suggestionsDropdown.classList.add('hidden');
                            }
                        } catch (error) {
                            console.error('Error fetching suggestions:', error);
                            suggestionsDropdown.classList.add('hidden');
                        }
                    } else {
                        // Hide dropdown if input is empty
                        suggestionsDropdown.classList.add('hidden');
                    }
                });
                
                // Hide suggestions when clicking outside
                document.addEventListener('click', function(event) {
                    if (event.target !== productNameInput && !suggestionsDropdown.contains(event.target)) {
                        suggestionsDropdown.classList.add('hidden');
                    }
                });
            }
            // ============= END AUTO-SUGGESTION PRODUCT NAME FUNCTIONALITY =============

            // Handle form submission with serial number validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const serialNumber = document.getElementById('serial_number').value.trim();
                    
                    // Validate serial number is not empty
                    if (!serialNumber) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Serial Number Required',
                            text: 'Please enter a serial number before saving.',
                            confirmButtonColor: '#E11D48'
                        });
                        return;
                    }
                    
                    // Check if serial number already exists
                    try {
                        const response = await fetch(`/api/products/check-serial?serial=${encodeURIComponent(serialNumber)}`);
                        const data = await response.json();
                        
                        if (data.exists) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Duplicate Serial Number',
                                html: `<p>Serial number <strong>${serialNumber}</strong> is already registered in the system.</p>
                                       <p style="font-size: 0.9em; color: #666; margin-top: 10px;">Please use a different serial number.</p>`,
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#f59e0b'
                            });
                            return;
                        }
                        
                        // Serial number is unique, proceed with form submission
                        // Scroll to top of the page
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                        // Clear any stored scroll position
                        localStorage.removeItem('formScrollPosition');
                        // Submit the form
                        form.submit();
                    } catch (error) {
                        console.error('Error checking serial number:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while checking the serial number. Please try again.',
                            confirmButtonColor: '#E11D48'
                        });
                    }
                });
            }
        });

        // Function to show SweetAlert messages
        function showSweetAlerts() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#4F46E5',
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#E11D48',
                    timer: 3000
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: '{!! implode('<br>', $errors->all()) !!}',
                    confirmButtonColor: '#E11D48',
                    timer: 3000
                });
            @endif
        }

        // After page load
        document.addEventListener('DOMContentLoaded', function() {
            // Show SweetAlert messages
            showSweetAlerts();

            // Focus on serial number field after page load
            const serialNumberInput = document.getElementById('serial_number');
            if (serialNumberInput) {
                // Small timeout to ensure the field is ready to receive focus
                setTimeout(() => {
                    serialNumberInput.focus();
                    serialNumberInput.select();
                }, 100);
            }

            // Initialize checkbox state
            toggleSupplierField();
        });

        // ============= USED PRODUCT CHECKBOX LOGIC =============
            const isUsedCheckbox = document.getElementById('is_used');
            const supplierSelect = document.getElementById('supplier_id');
            const supplierRequired = document.getElementById('supplier');

            function toggleSupplierField() {
                if (isUsedCheckbox.checked) {
                    // Disable supplier field for used products
                    supplierSelect.disabled = true;
                    supplierSelect.required = false;
                    supplierSelect.value = '';
                    supplierSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
                    supplierRequired.classList.add('hidden');
                } else {
                    // Enable supplier field for new products
                    supplierSelect.disabled = false;
                    supplierSelect.required = true;
                    supplierSelect.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    supplierRequired.classList.remove('hidden');
                }
            }

            // Listen for checkbox changes
            if (isUsedCheckbox) {
                isUsedCheckbox.addEventListener('change', toggleSupplierField);
                // Initialize on page load
                toggleSupplierField();
            }

            // ============= QR SCANNER LOGIC =============
            let html5QrCode;
            let currentCameraId = null;
            let cameras = [];

                    function initializeQrScanner() {
                        const qrResult = document.getElementById('qrResult');
                        qrResult.textContent = 'Initializing scanner...';

                        Html5Qrcode.getCameras().then(devices => {
                            if (devices && devices.length) {
                                cameras = devices;
                                currentCameraId = devices[0].id;
                                startScanner(currentCameraId);
                            } else {
                                qrResult.textContent = 'No cameras found on your device.';
                            }
                        }).catch(err => {
                            console.error('Error getting cameras:', err);
                            qrResult.textContent = 'Error accessing camera. Please check permissions.';
                        });
                    }

                    function startScanner(cameraId) {
                        const qrResult = document.getElementById('qrResult');
                        qrResult.textContent = 'Starting scanner with autofocus...';

                        html5QrCode = new Html5Qrcode("reader");

                        const config = {
                            fps: 10,
                            qrbox: { width: 500, height: 500 },
                            videoConstraints: {
                                focusMode: "continuous",
                                width: { ideal: 3840 },
                                height: { ideal: 2160 },
                                facingMode: "environment",
                                advanced: [
                                    { focusMode: "continuous" },
                                    { focusDistance: { ideal: 0.1 } }
                                ]
                            }
                        };

                        html5QrCode.start(
                            cameraId,
                            config,
                            (decodedText, decodedResult) => {
                                onScanSuccess(decodedText, decodedResult);
                            },
                            (error) => {}
                        ).then(() => {
                            qrResult.textContent = 'Scanner started. Move camera to focus on QR code.';
                            setTimeout(() => {
                                enhanceFocus();
                            }, 1500);
                        }).catch(err => {
                            console.error('Error with autofocus config:', err);
                            qrResult.textContent = 'Retrying with basic settings...';
                            startScannerBasic(cameraId);
                        });
                    }

                    function startScannerBasic(cameraId) {
                        const basicConfig = {
                            fps: 10,
                            qrbox: { width: 250, height: 250 }
                        };

                        html5QrCode.start(
                            cameraId,
                            basicConfig,
                            (decodedText, decodedResult) => {
                                onScanSuccess(decodedText, decodedResult);
                            },
                            (error) => {}
                        ).then(() => {
                            document.getElementById('qrResult').textContent = 'Scanner started (basic mode).';
                        }).catch(err => {
                            console.error('Error in basic scanner:', err);
                            document.getElementById('qrResult').textContent = 'Scanner error: ' + err.message;
                        });
                    }

                    function enhanceFocus() {
                        if (!html5QrCode) return;
                        try {
                            html5QrCode.applyVideoConstraints({
                                advanced: [{ focusMode: "continuous" }]
                            });
                        } catch (err) {
                            console.log('Focus enhancement not supported');
                        }
                    }

                    function stopQrScanner() {
                        if (html5QrCode && html5QrCode.isScanning) {
                            html5QrCode.stop().then(() => {
                                console.log('QR Scanner stopped');
                                document.getElementById('qrResult').textContent = 'Scanner stopped';
                            }).catch(err => {
                                console.error('Error stopping scanner:', err);
                            });
                        }
                    }

                    function onScanSuccess(decodedText, decodedResult) {
                        const qrResult = document.getElementById('qrResult');
                        qrResult.innerHTML = `<span class="text-green-600 font-medium">✅ Scanned: ${decodedText}</span>`;
                        document.getElementById('serial_number').value = decodedText;
                        console.log('Scanned QR Code:', decodedText);
                        setTimeout(() => {
                            qrScannerModal.classList.add('hidden');
                            stopQrScanner();
                        }, 1500);
                    }

                    function switchCamera() {
                        if (cameras.length < 2) {
                            document.getElementById('qrResult').textContent = 'Only one camera available';
                            return;
                        }
                        stopQrScanner();
                        let currentIndex = cameras.findIndex(camera => camera.id === currentCameraId);
                        let nextIndex = (currentIndex + 1) % cameras.length;
                        currentCameraId = cameras[nextIndex].id;
                        startScanner(currentCameraId);
                        document.getElementById('qrResult').textContent = `Switched to ${cameras[nextIndex].label || 'Camera ' + (nextIndex + 1)}`;
                    }

                    document.getElementById('switchCamera').addEventListener('click', switchCamera);
                    document.getElementById('stopScanner').addEventListener('click', () => {
                        stopQrScanner();
                        qrScannerModal.classList.add('hidden');
                    });

                    document.getElementById('openCamera').addEventListener('click', () => {
                        qrScannerModal.classList.remove('hidden');
                        setTimeout(initializeQrScanner, 300);
                    });

                    document.getElementById('closeQrModal').addEventListener('click', () => {
                        qrScannerModal.classList.add('hidden');
                        stopQrScanner();
                    });

                    window.addEventListener('click', (e) => {
                        if (e.target === qrScannerModal) {
                            qrScannerModal.classList.add('hidden');
                            stopQrScanner();
                        }
                    });

                   // AUTOFOCUS FOR BARCODE SCANNER INPUT
                //    const sn = document.getElementById("serial_number");

                //     sn.addEventListener("keydown", function (e) {
                //         if (e.key === "Enter") {
                //             e.preventDefault();
                //             console.log("Scanned:", sn.value);

                //             sn.value = "";
                //             sn.focus();
                //         }
                //     });
                // AUTOFOCUS FOR BARCODE SCANNER INPUT (BEST USE FOR POS)
                // document.addEventListener("DOMContentLoaded", function () {
                //         const field = document.getElementById("serial_number");

                //         // Always force the scanner to type here
                //         setInterval(() => {
                //             if (document.activeElement !== field) {
                //                 field.focus();
                //             }
                //         }, 300);
                //     });

            // Brand Modal
            const brandModal = document.getElementById('brandModal');
            document.getElementById('openBrandModal').addEventListener('click', () => {
                brandModal.classList.remove('hidden');
                brandModal.classList.add('flex');
            });
            document.getElementById('closeBrandModal').addEventListener('click', () => {
                brandModal.classList.add('hidden');
                brandModal.classList.remove('flex');
            });
            document.getElementById('cancelBrandModal').addEventListener('click', () => {
                brandModal.classList.add('hidden');
                brandModal.classList.remove('flex');
            });

            // Category Modal
            const categoryModal = document.getElementById('categoryModal');
            document.getElementById('openCategoryModal').addEventListener('click', () => {
                categoryModal.classList.remove('hidden');
                categoryModal.classList.add('flex');
            });
            document.getElementById('closeCategoryModal').addEventListener('click', () => {
                categoryModal.classList.add('hidden');
                categoryModal.classList.remove('flex');
            });
            document.getElementById('cancelCategoryModal').addEventListener('click', () => {
                categoryModal.classList.add('hidden');
                categoryModal.classList.remove('flex');
            });

            // SweetAlert messages
            // SweetAlert messages are now handled by the showSweetAlerts() function

            // ADDED: SweetAlert confirmation for product registration
            const addProductForm = document.getElementById('addProductForm');
            if (addProductForm) {
                addProductForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    Swal.fire({
                        title: 'Confirm Product Registration',
                        text: 'Are you sure you want to register this product?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#4F46E5',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Yes, Register',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit the form if confirmed
                            addProductForm.submit();
                        }
                    });
                });
            }
    </script>
@endsection