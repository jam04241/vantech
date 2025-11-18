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
                                    <th class="px-4 py-3 w-40 text-left">Item Type</th>
                                    <th class="px-4 py-3 w-64 text-left">Item Name / Bundle</th>
                                    <th class="px-4 py-3 w-64 text-left">Item Quantity</th>
                                    <th class="px-4 py-3 w-32 text-left">Quantity</th>
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

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Total Amount</label>
                            <input type="number" id="unpaidAmount" name="total_price" value="0.00" readonly step="0.01"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-100">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize supplier select
            const supplierSelect = document.getElementById('supplierSelect');
            if (supplierSelect) {
                supplierSelect.addEventListener('change', updateSupplierDetails);
            }

            // Initialize order date
            const orderDate = document.getElementById('orderDate');
            if (orderDate) {
                orderDate.valueAsDate = new Date();
                updateSummaryDate();
            }

            // Add item functionality
            document.getElementById('addItemBtn').addEventListener('click', addItemRow);

            // Add default row on load
            addItemRow();
        });

        function updateSupplierDetails() {
            const select = document.getElementById('supplierSelect');
            const selectedOption = select.options[select.selectedIndex];
            const detailsDiv = document.getElementById('supplierDetails');

            if (selectedOption && selectedOption.value) {
                document.getElementById('companyName').textContent = selectedOption.getAttribute('data-company') || '-';
                document.getElementById('contactName').textContent = selectedOption.getAttribute('data-contact') || '-';
                document.getElementById('supplierAddress').textContent = selectedOption.getAttribute('data-address') || '-';

                // Update status badge
                const status = selectedOption.getAttribute('data-status');
                const statusBadge = document.getElementById('statusBadge');
                if (statusBadge) {
                    statusBadge.textContent = status || 'Unknown';
                    statusBadge.className = `px-2.5 py-0.5 rounded-full text-xs font-medium ${status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
                }

                // Update summary
                document.getElementById('summarySupplier').textContent = selectedOption.textContent;

                detailsDiv.classList.remove('hidden');
            } else {
                if (detailsDiv) detailsDiv.classList.add('hidden');
            }
        }

        function updateSummaryDate() {
            const dateInput = document.getElementById('orderDate');
            if (dateInput) {
                document.getElementById('summaryDate').textContent = dateInput.value;
            }
        }

        function addItemRow() {
            const tbody = document.getElementById('itemsTableBody');
            const rowCount = tbody.children.length + 1;

            const row = document.createElement('tr');
            row.className = "border-b";

            row.innerHTML = `
                    <td class="px-4 py-3 text-center font-medium">${rowCount}</td>

                    <td class="px-4 py-3">
                        <select name="item_type[]" class="w-full border border-gray-300 rounded-lg px-3 py-2 item-type" onchange="handleItemTypeChange(this)">
                            <option value="product">Product</option>
                            <option value="bundle">Bundle</option>
                            <option value="pack">Pack</option>
                        </select>
                    </td>

                    <td class="px-4 py-3">
                        <div class="item-name-container">
                            <input type="text" name="item_name[]" class="w-full border border-gray-300 rounded-lg px-3 py-2 item-name" placeholder="Enter item name" required>
                        </div>
                    </td>

                    <td class="px-4 py-3">
                        <input type="number" name="quantity[]" value="1" min="1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 quantity"
                               oninput="calculateRowTotal(this)" required>
                    </td>

                    <td class="px-4 py-3">
                        <input type="number" name="quantity[]" value="1" min="1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 quantity"
                               oninput="calculateRowTotal(this)" required>
                    </td>

                    <td class="px-4 py-3">
                        <input type="number" name="unit_price[]" value="0" step="0.01" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 unit-price"
                               oninput="calculateRowTotal(this)" required>
                    </td>

                    <td class="px-4 py-3">
                        <input type="number" name="row_total[]" value="0.00" step="0.01"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 total"
                               readonly>
                    </td>

                    <td class="px-4 py-3 text-center">
                        <button type="button" class="text-red-600 hover:text-red-800"
                                onclick="removeItemRow(this)">
                            ✖
                        </button>
                    </td>
                `;

            tbody.appendChild(row);
            updateSummary();
        }
        function handleItemTypeChange(select) {
            // All item types use the manual item-name input only.
            const row = select.closest('tr');
            const itemNameInput = row.querySelector('.item-name');
            if (itemNameInput) itemNameInput.classList.remove('hidden');
        }

        function handleBundleSelect(select) {
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.value) {
                const bundleName = selectedOption.getAttribute('data-name');
                const row = select.closest('tr');
                const itemNameInput = row.querySelector('.item-name');
                // Set the bundle name in the hidden item_name field
                itemNameInput.value = bundleName;
            }
        }

        function calculateRowTotal(input) {
            const row = input.closest("tr");
            const qty = parseFloat(row.querySelector(".quantity").value) || 0;
            const price = parseFloat(row.querySelector(".unit-price").value) || 0;
            const total = qty * price;

            row.querySelector(".total").value = total.toFixed(2);
            updateSummary();
        }

        function removeItemRow(btn) {
            btn.closest("tr").remove();
            updateRowNumbers();
            updateSummary();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll("#itemsTableBody tr");
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
        }

        function updateSummary() {
            let grandTotal = 0;
            let count = 0;

            document.querySelectorAll("#itemsTableBody tr").forEach(row => {
                const total = parseFloat(row.querySelector(".total").value) || 0;
                if (total > 0) {
                    grandTotal += total;
                    count++;
                }
            });

            document.getElementById("grandTotal").textContent = grandTotal.toFixed(2);
            document.getElementById("summaryTotal").textContent = grandTotal.toFixed(2);
            document.getElementById("summaryItems").textContent = count;
            document.getElementById("unpaidAmount").value = grandTotal.toFixed(2);
        }

        // Update summary when order date changes
        document.getElementById('orderDate')?.addEventListener('change', updateSummaryDate);

                // Build items JSON and validate before submit
                const purchaseForm = document.querySelector('form[action="{{ route('purchase.store') }}"]');
                if (purchaseForm) {
                    purchaseForm.addEventListener('submit', function (e) {
                        const rows = document.querySelectorAll('#itemsTableBody tr');
                        const items = [];

                        for (let i = 0; i < rows.length; i++) {
                            const row = rows[i];
                            const type = row.querySelector('.item-type')?.value;
                            // All item types use manual input only.
                            const manualName = row.querySelector('.item-name')?.value?.trim() || '';
                            const qty = parseInt(row.querySelector('.quantity')?.value) || 0;
                            const unit = parseFloat(row.querySelector('.unit-price')?.value) || 0;
                            const total = parseFloat(row.querySelector('.total')?.value) || 0;

                            if (!type || qty < 1 || !manualName) {
                                e.preventDefault();
                                Swal.fire({
                                    title: 'Validation Error',
                                    text: 'Please complete all item rows: provide an item name and valid quantity.',
                                    icon: 'warning',
                                });
                                return false;
                            }

                            items.push({
                                item_id: null,
                                item_type: type,
                                item_name: manualName,
                                quantity: qty,
                                unit_price: unit,
                                total: total,
                            });
                        }

                        if (items.length === 0) {
                            e.preventDefault();
                            Swal.fire({
                                title: 'Validation Error',
                                text: 'At least one item is required.',
                                icon: 'warning',
                            });
                            return false;
                        }

                        document.getElementById('itemsInput').value = JSON.stringify(items);
                        // statusInput is already set to Pending by default; you can change it via UI if needed
                    });
                }

        document.addEventListener('DOMContentLoaded', function () {
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
            });
    </script>

@endsection