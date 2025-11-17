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

            <div class="p-6">
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Supplier Information</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Supplier</label>
                            <select id="supplierSelect"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                                <option selected disabled>Choose a supplier...</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Order Date</label>
                            <input type="date" id="orderDate"
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
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Active
                                </span>
                            </div>
                        </div>
                        <div><span class="font-medium">Address:</span> <span id="supplierAddress">-</span></div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-8 pb-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-800">Order Items</h3>

                        <button id="addItemBtn"
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
                                    <th class="px-4 py-3 w-64 text-left">Item</th>
                                    <th class="px-4 py-3 w-32 text-left">Qty</th>
                                    <th class="px-4 py-3 w-32 text-left">Unit Price</th>
                                    <th class="px-4 py-3 w-32 text-left">Total</th>
                                    <th class="px-4 py-3 w-12"></th>
                                </tr>
                            </thead>

                            <tbody id="itemsTableBody"></tbody>

                            <tfoot>
                                <tr class="bg-gray-50 font-medium">
                                    <td colspan="5" class="px-4 py-3 text-right">Grand Total:</td>
                                    <td class="px-4 py-3" id="grandTotal">0.00</td>
                                    <td></td>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unpaid Amount</label>
                            <input type="number" id="unpaidAmount" value="0.00" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2 mt-4">Order Status</label>
                                <select
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending" selected>Pending</option>
                                    <option value="received">Received</option>
                                    <option value="cancel">Cancel</option>
                                </select>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <button class="px-4 py-2 border rounded-md bg-white hover:bg-gray-50">Cancel</button>
                    <button class="px-4 py-2 rounded-md bg-green-600 text-white hover:bg-green-700">Submit Order</button>
                </div>

            </div>
        </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('addItemBtn').addEventListener('click', addItemRow);

            // add default row on load
            addItemRow();
        });

        function addItemRow() {
            const tbody = document.getElementById('itemsTableBody');
            const rowCount = tbody.children.length + 1;

            const row = document.createElement('tr');
            row.className = "border-b";

            row.innerHTML = `
                <td class="px-4 py-3 text-center font-medium">${rowCount}</td>

                <td class="px-4 py-3">
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2 item-type">
                        <option value="product">Product</option>
                        <option value="bundle">Bundle</option>
                    </select>
                </td>

                <td class="px-4 py-3">
                    <input type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 item-name" placeholder="Enter product/bundle name">
                </td>

                <td class="px-4 py-3">
                    <input type="number" value="1" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 quantity"
                           oninput="calculateRowTotal(this)">
                </td>

                <td class="px-4 py-3">
                    <input type="number" value="" step="0.01"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 unit-price"
                           oninput="calculateRowTotal(this)">
                </td>

                <td class="px-4 py-3">
                    <input type="number" value="0.00" step="0.01"
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

            const rows = document.querySelectorAll("#itemsTableBody tr");
            rows.forEach((row, index) => {
                row.children[0].textContent = index + 1;
            });

            updateSummary();
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
    </script>

@endsection