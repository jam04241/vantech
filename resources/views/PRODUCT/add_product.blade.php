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
                    </div>
    {{-- Main Product Form --}}
    <div class="bg-white p-6 rounded-xl shadow-lg w-full border border-gray-200">
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b border-indigo-200 pb-2 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            Add New Product
        </h2>

        <form id="addProductForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            {{-- COMPACT GRID LAYOUT --}}
            <div class="grid md:grid-cols-2 gap-6">

                {{-- LEFT COLUMN --}}
                <div class="space-y-6">
                    {{-- PRODUCT INFORMATION --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-md font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Product Information
                        </h3>
                        <div class="space-y-4">
                            {{-- Product Name with Auto-suggestion --}}
                            <div class="relative">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" id="name" name="product_name" value="{{ old('product_name') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter product name" required autocomplete="off">
                                <div id="productSuggestions"
                                    class="absolute top-full left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden z-50 max-h-48 overflow-y-auto">
                                </div>
                            </div>

                            {{-- Serial Number --}}
                            <div>
                                <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Serial Number</label>
                                <input type="text" id="serial_number" name="serial_number"
                                    value="{{ old('serial_number') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter serial number (optional)" autofocus required>
                            </div>
                        </div>
                    </div>

                    {{-- CONDITION CHECKBOX --}}
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="is_used" name="is_used" value="1"
                                class="mt-0.5 w-4 h-4 text-yellow-600 border-gray-300 rounded focus:ring-2 focus:ring-yellow-500"
                                {{ old('is_used') ? 'checked' : '' }}>
                            <div>
                                <label for="is_used" class="block text-sm font-semibold text-gray-800 cursor-pointer">
                                    This is a Used Product
                                </label>
                                <p class="text-xs text-gray-600 mt-1">
                                    Check this box if the product is used/second-hand. Used products don't require a
                                    supplier.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN --}}
                <div class="space-y-6">
                    {{-- RELATIONAL FIELDS --}}
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-md font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            Relations
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span
                                        class="text-red-500">*</span></label>
                                <select id="category_id" name="category_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                                <select id="brand_id" name="brand_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    required>
                                    <option value="">Select Brand</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Supplier <span id="supplier" class="text-red-500">*</span>
                                </label>
                                <select id="supplier_id" name="supplier_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
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
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h3 class="text-md font-semibold text-gray-700 mb-3 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                            Stock Information
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (₱) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" id="price" name="price" step="0.01" value="{{ old('price') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                    placeholder="Enter price" required>
                            </div>

                            <div>
                                <label for="warranty" class="block text-sm font-medium text-gray-700 mb-1">Warranty</label>
                                <select id="warranty" name="warranty_period" value="{{ old('warranty_period') }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                                    <option value="" selected hidden> Select Warranty</option>
                                    <option value="3 days">3 days</option>
                                    <option value="7 days">7 days</option>
                                    <option value="10 days">10 days</option>
                                    <option value="15 days">15 days</option>
                                    <option value="30 days">30 days</option>
                                    <option value="1 year">1 year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT BUTTON - Now much more accessible without scrolling --}}
            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 text-white px-6 py-2.5 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
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
                        confirmButtonColor: '#4F46E5'
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#E11D48'
                    });
                @endif

                @if($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: '{!! implode('<br>', $errors->all()) !!}',
                        confirmButtonColor: '#E11D48'
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