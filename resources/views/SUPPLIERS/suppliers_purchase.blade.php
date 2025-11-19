@extends('SIDEBAR.layouts')
@section('title', 'Suppliers Orders')
@section('name', 'PURCHASE ORDERS')

@section('content')
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">New Purchase Order</h2>
            <a href="{{ route('suppliers.list') }}" class="px-4 py-2 border rounded-lg bg-white">Back to Orders</a>
        </div>

        <form action="{{ route('purchase.store') }}" method="POST">
            @csrf
            <div class="p-6">
                {{-- Hidden inputs: items JSON and status --}}
                <input type="hidden" name="items" id="itemsInput">
                <input type="hidden" name="status" id="statusInput" value="Pending">

                <!-- Supplier Information -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Supplier Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Supplier</label>
                            <select id="supplierSelect" name="supplier_id" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="" selected disabled>Choose a supplier...</option>
                                @forelse($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" data-company="{{ $supplier->company_name }}"
                                        data-contact="{{ $supplier->supplier_name }}" data-address="{{ $supplier->address }}"
                                        data-status="{{ $supplier->status }}">
                                        {{ $supplier->supplier_name }} - {{ $supplier->company_name }}
                                    </option>
                                @empty
                                    <option value="" disabled>No active suppliers found</option>
                                @endforelse
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order Date</label>
                            <input type="date" id="orderDate" name="order_date" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Supplier Detail Box -->
                    <div id="supplierDetails" class="mt-4 bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500 hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                            <div><span class="font-medium">Company:</span> <span id="companyName">-</span></div>
                            <div><span class="font-medium">Contact:</span> <span id="contactName">-</span></div>
                            <div>
                                <span class="font-medium">Status:</span>
                                <span id="statusBadge" class="px-2.5 py-0.5 rounded-full text-xs font-medium"></span>
                            </div>
                        </div>
                        <div><span class="font-medium">Address:</span> <span id="supplierAddress">-</span></div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Order Items</h3>
                        <button type="button" id="addItemBtn"
                            class="px-3 py-2 border border-blue-500 text-blue-500 rounded-md hover:bg-blue-50">
                            + Add Item
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm" id="itemsTable">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-3 w-12 text-left">#</th>
                                    <th class="px-4 py-3 w-40 text-left">Bundle Name</th>
                                    <th class="px-4 py-3 w-64 text-left">Bundle Type</th>
                                    <th class="px-4 py-3 w-64 text-left">Quantity Bundle</th>
                                    <th class="px-4 py-3 w-32 text-left">Quantity Ordered</th>
                                    <th class="px-4 py-3 w-32 text-left">Unit Price</th>
                                    <th class="px-4 py-3 w-32 text-left">Total</th>
                                    <th class="px-4 py-3 w-12"></th>
                                </tr>
                            </thead>

                            <tbody id="itemsTableBody"></tbody>

                            <tfoot>
                                <tr class="bg-gray-50 font-medium">
                                    <td colspan="4" class="px-4 py-3 text-right">Grand Total:</td>
                                    <td class="px-4 py-3" id="grandTotal">0.00</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Order Summary</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2 bg-blue-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 gap-3">
                                <div><strong>Supplier:</strong> <span id="summarySupplier">-</span></div>
                                <div><strong>Date:</strong> <span id="summaryDate">-</span></div>
                                <div><strong>Items:</strong> <span id="summaryItems">0</span></div>
                                <div><strong>Total:</strong> ₱<span id="summaryTotal">0.00</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('suppliers.list') }}"
                        class="px-4 py-2 border rounded-md bg-white hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">
                        Submit Order
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize variables
            let rowCount = 0;
            const bundles = @json($bundles ?? []);
            const bundleTypes = ['product', 'pack', 'bundle'];

            // Set current date as default
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('orderDate').value = today;
            updateSummaryDate(today);

            // Supplier selection handler
            document.getElementById('supplierSelect').addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    showSupplierDetails(
                        selectedOption.dataset.company,
                        selectedOption.dataset.contact,
                        selectedOption.dataset.address,
                        selectedOption.dataset.status
                    );
                    updateSummarySupplier(selectedOption.dataset.company);
                } else {
                    hideSupplierDetails();
                }
            });

            // Add item button handler
            document.getElementById('addItemBtn').addEventListener('click', function () {
                addNewRow();
            });

            // Form submission handler
            document.querySelector('form').addEventListener('submit', function (e) {
                if (!validateForm()) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Validation Error',
                        text: 'Please fill in all required fields and add at least one item.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                } else {
                    prepareItemsForSubmission();
                }
            });

            // SweetAlert notifications
            @if(session('success'))
                Swal.fire({
                    title: 'Saved',
                    text: @json(session('success')),
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    title: 'Error',
                    text: @json(session('error')),
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    title: 'Validation Error',
                    html: @json(implode('<br>', $errors->all())),
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            @endif

                // Function to add new row to items table
                function addNewRow() {
                    rowCount++;
                    const tbody = document.getElementById('itemsTableBody');

                    const row = document.createElement('tr');
                    row.className = 'border-b border-gray-200';
                    row.innerHTML = `
                        <td class="px-4 py-3">${rowCount}</td>
                        <td class="px-4 py-3">
                            <input type="text" name="bundle_name" class="bundle-name w-full border border-gray-300 rounded px-2 py-1" 
                                placeholder="Enter bundle name" required>
                        </td>
                        <td class="px-4 py-3">
                            <select name="bundle_type" class="bundle-type w-full border border-gray-300 rounded px-2 py-1" required>
                                <option value="" disabled selected>Select Type</option>
                                ${bundleTypes.map(type =>
                        `<option value="${type}">${type.charAt(0).toUpperCase() + type.slice(1)}</option>`
                    ).join('')}
                            </select>
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="quantity_bundle" class="quantity-bundle w-full border border-gray-300 rounded px-2 py-1" 
                                min="1" value="1" required>
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="quantity_ordered" class="quantity-ordered w-full border border-gray-300 rounded px-2 py-1" 
                                min="1" value="1" required>
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="unit_price" class="unit-price w-full border border-gray-300 rounded px-2 py-1" 
                                min="0" step="0.01" value="0.00" required>
                        </td>
                        <td class="px-4 py-3 total-amount">0.00</td>
                        <td class="px-4 py-3">
                            <button type="button" class="remove-row text-red-500 hover:text-red-700">
                                ✕
                            </button>
                        </td>
                    `;

                    tbody.appendChild(row);
                    attachRowEventListeners(row);
                    updateSummaryItems();
                }

            // Function to attach event listeners to row elements
            function attachRowEventListeners(row) {
                const quantityInput = row.querySelector('.quantity-ordered');
                const unitPriceInput = row.querySelector('.unit-price');
                const removeBtn = row.querySelector('.remove-row');

                // Quantity and price change handlers
                quantityInput.addEventListener('input', () => calculateRowTotal(row));
                unitPriceInput.addEventListener('input', () => calculateRowTotal(row));

                // Remove row handler
                removeBtn.addEventListener('click', function () {
                    row.remove();
                    updateRowNumbers();
                    updateSummaryItems();
                    calculateGrandTotal();
                });
            }

            // Function to calculate row total
            function calculateRowTotal(row) {
                const quantity = parseFloat(row.querySelector('.quantity-ordered').value) || 0;
                const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                const total = quantity * unitPrice;

                row.querySelector('.total-amount').textContent = total.toFixed(2);
                calculateGrandTotal();
            }

            // Function to calculate grand total
            function calculateGrandTotal() {
                const totalElements = document.querySelectorAll('.total-amount');
                let grandTotal = 0;

                totalElements.forEach(element => {
                    grandTotal += parseFloat(element.textContent) || 0;
                });

                document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
                document.getElementById('summaryTotal').textContent = grandTotal.toFixed(2);
            }

            // Function to update row numbers after removal
            function updateRowNumbers() {
                const rows = document.querySelectorAll('#itemsTableBody tr');
                rows.forEach((row, index) => {
                    row.querySelector('td:first-child').textContent = index + 1;
                });
                rowCount = rows.length;
            }

            // Function to prepare items for form submission
            function prepareItemsForSubmission() {
                const items = [];
                const rows = document.querySelectorAll('#itemsTableBody tr');

                rows.forEach(row => {
                    const bundleName = row.querySelector('.bundle-name').value;
                    const bundleType = row.querySelector('.bundle-type').value;
                    const quantityBundle = row.querySelector('.quantity-bundle').value;
                    const quantityOrdered = row.querySelector('.quantity-ordered').value;
                    const unitPrice = row.querySelector('.unit-price').value;

                    if (bundleName && bundleType && quantityBundle && quantityOrdered && unitPrice) {
                        items.push({
                            bundle_name: bundleName,
                            bundle_type: bundleType,
                            quantity_bundle: parseInt(quantityBundle),
                            quantity_ordered: parseInt(quantityOrdered),
                            unit_price: parseFloat(unitPrice)
                        });
                    }
                });

                document.getElementById('itemsInput').value = JSON.stringify(items);
            }

            // Function to validate form before submission
            function validateForm() {
                const supplierSelect = document.getElementById('supplierSelect');
                const orderDate = document.getElementById('orderDate');
                const items = document.querySelectorAll('#itemsTableBody tr');

                if (!supplierSelect.value || !orderDate.value || items.length === 0) {
                    return false;
                }

                // Check if all items have required fields
                let allItemsValid = true;
                items.forEach(row => {
                    const bundleName = row.querySelector('.bundle-name').value;
                    const bundleType = row.querySelector('.bundle-type').value;
                    const quantityBundle = row.querySelector('.quantity-bundle').value;
                    const quantityOrdered = row.querySelector('.quantity-ordered').value;
                    const unitPrice = row.querySelector('.unit-price').value;

                    if (!bundleName || !bundleType || !quantityBundle || !quantityOrdered || !unitPrice) {
                        allItemsValid = false;
                        // Highlight empty fields
                        if (!bundleName) row.querySelector('.bundle-name').classList.add('border-red-500');
                        if (!bundleType) row.querySelector('.bundle-type').classList.add('border-red-500');
                        if (!quantityBundle) row.querySelector('.quantity-bundle').classList.add('border-red-500');
                        if (!quantityOrdered) row.querySelector('.quantity-ordered').classList.add('border-red-500');
                        if (!unitPrice) row.querySelector('.unit-price').classList.add('border-red-500');
                    }
                });

                return allItemsValid;
            }

            // Supplier details functions
            function showSupplierDetails(company, contact, address, status) {
                document.getElementById('companyName').textContent = company;
                document.getElementById('contactName').textContent = contact;
                document.getElementById('supplierAddress').textContent = address;

                const statusBadge = document.getElementById('statusBadge');
                statusBadge.textContent = status;
                statusBadge.className = `px-2.5 py-0.5 rounded-full text-xs font-medium ${status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    }`;

                document.getElementById('supplierDetails').classList.remove('hidden');
            }

            function hideSupplierDetails() {
                document.getElementById('supplierDetails').classList.add('hidden');
            }

            // Summary update functions
            function updateSummarySupplier(supplier) {
                document.getElementById('summarySupplier').textContent = supplier;
            }

            function updateSummaryDate(date) {
                document.getElementById('summaryDate').textContent = new Date(date).toLocaleDateString();
            }

            function updateSummaryItems() {
                const itemCount = document.querySelectorAll('#itemsTableBody tr').length;
                document.getElementById('summaryItems').textContent = itemCount;
            }
        });
    </script>
@endsection