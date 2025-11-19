@extends('SIDEBAR.layouts')

@section('title', 'Suppliers Orders')
@section('name', 'PURCHASE ORDERS')

@section('content')
    <div class="p-6">
        <!-- SweetAlert Notifications -->
        @if(session('success'))
            <div class="mb-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl shadow border">
                <p class="text-gray-500 text-sm">Total Orders</p>
                <h1 class="text-2xl font-bold" id="totalOrdersCount">{{ $totalOrders }}</h1>
            </div>

            <div class="bg-white p-4 rounded-xl shadow border">
                <p class="text-gray-500 text-sm">Pending Orders</p>
                <h1 class="text-2xl font-bold" id="pendingOrdersCount">{{ $pendingOrders }}</h1>
            </div>

            <div class="bg-white p-4 rounded-xl shadow border">
                <p class="text-gray-500 text-sm">Received Orders</p>
                <h1 class="text-2xl font-bold" id="receivedOrdersCount">{{ $receivedOrders }}</h1>
            </div>

            <div class="bg-white p-4 rounded-xl shadow border">
                <p class="text-gray-500 text-sm">Cancelled Orders</p>
                <h1 class="text-2xl font-bold" id="cancelledOrdersCount">{{ $cancelledOrders }}</h1>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
            <div class="flex gap-2">
                <button class="px-4 py-2 rounded-lg border bg-gray-900 text-white">All</button>
                <button class="px-4 py-2 rounded-lg border bg-white text-gray-600">Pending</button>
                <button class="px-4 py-2 rounded-lg border bg-white text-gray-600">Unpaid</button>
            </div>

            <div>
                <a href="{{ route('suppliers.purchase-orders') }}"
                    class="px-4 py-2 border rounded-lg bg-white hover:bg-gray-50 transition duration-200">
                    + New Purchase Order
                </a>
            </div>
        </div>

        {{-- SEARCH + SORT + FILTER --}}
        <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
            <input type="text" placeholder="Find order" id="searchInput"
                class="px-4 py-2 border rounded-lg w-1/2 mt-4 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm"
                aria-label="Search orders">

            <div class="flex gap-2">
                <select class="px-4 py-2 border rounded-lg bg-white" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Received">Received</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <select class="px-4 py-2 border rounded-lg bg-white" id="dateFilter">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
                <button class="px-4 py-2 border rounded-lg bg-white flex items-center gap-2 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v4H6v-4z" />
                    </svg>
                    Export
                </button>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-lg border overflow-x-auto mt-4">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-700 text-base">
                    <tr>
                        <th class="p-4 font-semibold">Order ID</th>
                        <th class="p-4 font-semibold">Date</th>
                        <th class="p-4 font-semibold">Supplier</th>
                        <th class="p-4 font-semibold">Bundle Name</th>
                        <th class="p-4 font-semibold">Bundle Type</th>
                        <th class="p-4 font-semibold">Bundle Quantity</th>
                        <th class="p-4 font-semibold">Quantity</th>
                        <th class="p-4 font-semibold">Unit Price</th>
                        <th class="p-4 font-semibold">Total</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="text-base" id="ordersTableBody">
                    @forelse($purchaseOrders as $order)
                        <tr class="border-t hover:bg-gray-50 transition" data-status="{{ $order->status }}"
                            data-order-id="{{ $order->id }}">
                            <td class="p-4 text-blue-600 font-semibold">#{{ $order->id }}</td>
                            <td class="p-4">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</td>
                            <td class="p-4">
                                @if($order->supplier)
                                    {{ $order->supplier->company_name }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($order->bundle)
                                    {{ $order->bundle->bundle_name }}
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($order->bundle)
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs capitalize">
                                        {{ $order->bundle->bundle_type }}
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($order->bundle)
                                    <span class="p-4 text-blue-600 font-semibold">
                                        {{ $order->bundle->quantity_bundles }}
                                    </span>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="p-4">{{ $order->quantity_ordered }}</td>
                            <td class="p-4">₱{{ number_format($order->unit_price, 2) }}</td>
                            <td class="p-4 font-semibold">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="p-4">
                                @if($order->status == 'Pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium"
                                        id="status-badge-{{ $order->id }}">Pending</span>
                                @elseif($order->status == 'Received')
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium"
                                        id="status-badge-{{ $order->id }}">Received</span>
                                @elseif($order->status == 'Cancelled')
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium"
                                        id="status-badge-{{ $order->id }}">Cancelled</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex space-x-2">
                                    @if($order->status == 'Pending')
                                        <button
                                            class="confirm-order-btn border bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 transition duration-200"
                                            data-order-id="{{ $order->id }}">
                                            Confirm
                                        </button>
                                    @elseif($order->status == 'Received')
                                        <button class="border bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed" disabled>
                                            Done
                                        </button>
                                    @else
                                        <button class="border bg-red-400 text-white px-3 py-1 rounded cursor-not-allowed" disabled>
                                            Cancelled
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-lg font-medium mb-2">No purchase orders found</p>
                                    <p class="text-sm mb-4">Get started by creating your first purchase order</p>
                                    <a href="{{ route('suppliers.purchase-orders') }}"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                                        Create Purchase Order
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($purchaseOrders->hasPages())
            <div class="mt-6">
                {{ $purchaseOrders->links() }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const ordersTableBody = document.getElementById('ordersTableBody');
            const rows = ordersTableBody.getElementsByTagName('tr');

            function filterOrders() {
                const searchTerm = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;

                for (let row of rows) {
                    const text = row.textContent.toLowerCase();
                    const status = row.getAttribute('data-status');

                    const matchesSearch = text.includes(searchTerm);
                    const matchesStatus = statusValue === '' || status === statusValue;

                    if (matchesSearch && matchesStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            }

            searchInput.addEventListener('input', filterOrders);
            statusFilter.addEventListener('change', filterOrders);

            // Confirm order functionality
            const confirmButtons = document.querySelectorAll('.confirm-order-btn');

            confirmButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');
                    const orderRow = document.querySelector(`tr[data-order-id="${orderId}"]`);
                    const orderNumber = orderRow.querySelector('td:first-child').textContent;

                    Swal.fire({
                        title: 'Confirm Order Receipt?',
                        html: `Are you sure you want to mark order <strong>${orderNumber}</strong> as received?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10B981',
                        cancelButtonColor: '#6B7280',
                        confirmButtonText: 'Yes, mark as received!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Send AJAX request to update status
                            fetch(`/purchase/${orderId}/confirm`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: JSON.stringify({
                                    _method: 'PUT'
                                })
                            })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Update status badge
                                        const statusBadge = document.getElementById(`status-badge-${orderId}`);
                                        statusBadge.textContent = 'Received';
                                        statusBadge.className = 'px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium';

                                        // Update row data-status attribute
                                        orderRow.setAttribute('data-status', 'Received');

                                        // Update button
                                        const actionButton = button;
                                        actionButton.textContent = 'Done';
                                        actionButton.className = 'border bg-gray-400 text-white px-3 py-1 rounded cursor-not-allowed';
                                        actionButton.disabled = true;
                                        actionButton.classList.remove('confirm-order-btn', 'hover:bg-green-700');

                                        // Update statistics counters
                                        updateOrderCounters();

                                        Swal.fire({
                                            title: 'Success!',
                                            text: `Order ${orderNumber} has been marked as received.`,
                                            icon: 'success',
                                            confirmButtonColor: '#10B981',
                                            confirmButtonText: 'OK'
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: data.message || 'Failed to update order status.',
                                            icon: 'error',
                                            confirmButtonColor: '#EF4444',
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Failed to update order status. Please try again.',
                                        icon: 'error',
                                        confirmButtonColor: '#EF4444',
                                        confirmButtonText: 'OK'
                                    });
                                });
                        }
                    });
                });
            });

            // Function to update order counters
            function updateOrderCounters() {
                fetch('/purchase/statistics', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('totalOrdersCount').textContent = data.totalOrders;
                            document.getElementById('pendingOrdersCount').textContent = data.pendingOrders;
                            document.getElementById('receivedOrdersCount').textContent = data.receivedOrders;
                            document.getElementById('cancelledOrdersCount').textContent = data.cancelledOrders;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching statistics:', error);
                    });
            }
        });
    </script>
@endsection