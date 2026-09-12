@extends('SIDEBAR.layouts')
@section('title', 'Staff Management')
@section('name', 'Staff Management')

@section('content')
    <div class="bg-white border rounded-lg p-6 shadow-sm">

        {{-- Page Title --}}
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Staff Management</h2>
                <p class="text-gray-600 mt-1">
                    Review staff who registered, activate their accounts, and keep their records up to date.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-lg">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                <span class="text-sm font-medium text-indigo-600">
                    <span class="font-bold">{{ $employees->total() }}</span> staff shown
                </span>
            </div>
        </div>

        {{-- Pending applications need the owner's attention, so lead with them --}}
        @if ($pendingCount > 0)
            <div class="flex items-start gap-3 p-4 mb-6 bg-amber-50 border border-amber-300 rounded-lg">
                <svg class="w-6 h-6 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.74-2.99l-6.93-12a2 2 0 00-3.48 0l-6.93 12A2 2 0 005.07 19z" />
                </svg>
                <div class="flex-1">
                    <p class="font-semibold text-amber-900">
                        {{ $pendingCount }} {{ Str::plural('registration', $pendingCount) }} waiting for approval
                    </p>
                    <p class="text-sm text-amber-800 mt-0.5">
                        These employees cannot log in until you activate their account.
                    </p>
                </div>
                <a href="{{ route('staff.record', ['status' => 'pending']) }}"
                    class="px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition whitespace-nowrap">
                    Review now
                </a>
            </div>
        @endif

        {{-- Search and filters. Server-side so they apply across every page, not
             just the rows currently rendered. --}}
        <form method="GET" action="{{ route('staff.record') }}" id="filterForm"
            class="flex flex-col lg:flex-row lg:items-end gap-4 mb-6">
            <div class="flex-1 min-w-0">
                <label for="searchInput" class="block text-xs font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" id="searchInput" name="search" value="{{ $searchQuery }}"
                        placeholder="Name, username, role, address or phone..."
                        class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm"
                        aria-label="Search employees">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    @if ($searchQuery)
                        <a href="{{ route('staff.record', array_filter(['status' => $statusFilter, 'role' => $roleFilter])) }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition"
                            title="Clear search">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <label for="roleFilter" class="block text-xs font-medium text-gray-700 mb-2">Position</label>
                <select id="roleFilter" name="role" onchange="document.getElementById('filterForm').submit()"
                    class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-white">
                    <option value="">All Positions</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected($roleFilter === $role)>{{ $role }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="statusFilter" class="block text-xs font-medium text-gray-700 mb-2">Account Status</label>
                <select id="statusFilter" name="status" onchange="document.getElementById('filterForm').submit()"
                    class="px-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm bg-white">
                    <option value="">All Statuses</option>
                    <option value="pending" @selected($statusFilter === 'pending')>Pending Approval ({{ $pendingCount }})</option>
                    <option value="active" @selected($statusFilter === 'active')>Active ({{ $activeCount }})</option>
                    <option value="inactive" @selected($statusFilter === 'inactive')>Deactivated ({{ $inactiveCount }})</option>
                    <option value="no_account" @selected($statusFilter === 'no_account')>No Account</option>
                </select>
            </div>

            <button type="submit"
                class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition font-medium">
                Apply
            </button>
        </form>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        {{-- Staff Table Card --}}
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Employee</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Address</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Contact</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Position</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Account</th>
                            <th class="px-6 py-4 text-left font-semibold text-gray-700 text-sm uppercase tracking-wide">
                                Actions</th>
                        </tr>
                    </thead>

                    <tbody id="employeesTable" class="divide-y divide-gray-200">
                        @forelse($employees as $employee)
                            @php
                                $account = $employee->user;
                                $status = $employee->account_status;
                                $isSelf = $account && $account->id === auth()->id();
                            @endphp
                            <tr class="hover:bg-gray-50 transition {{ $status === 'pending' ? 'bg-amber-50/50' : '' }}"
                                data-employee-id="{{ $employee->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="h-10 w-10 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-semibold text-sm">
                                            {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $employee->full_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $account ? '@' . $account->username : 'No login account' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    @if ($employee->address)
                                        <div class="text-sm text-gray-900">{{ $employee->street }}</div>
                                        <div class="text-sm text-gray-600">
                                            {{ collect([$employee->barangay, $employee->city])->filter()->join(', ') }}
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">Not set yet</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $employee->phone_number }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ ucfirst($employee->gender) }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($employee->role)
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                                            {{ $employee->role == 'Staff'
                                                ? 'bg-purple-100 text-purple-800'
                                                : ($employee->role == 'Technical'
                                                    ? 'bg-pink-100 text-pink-800'
                                                    : ($employee->role == 'Cashier'
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-orange-100 text-orange-800')) }}">
                                            {{ $employee->role }}
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-500 border border-dashed border-gray-400">
                                            Unassigned
                                        </span>
                                    @endif
                                </td>

                                {{-- Account state --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badge = match ($status) {
                                            'pending' => 'bg-amber-100 text-amber-800 border-amber-300',
                                            'active' => 'bg-green-100 text-green-800 border-green-300',
                                            'inactive' => 'bg-red-100 text-red-800 border-red-300',
                                            default => 'bg-gray-100 text-gray-600 border-gray-300',
                                        };
                                    @endphp
                                    <span
                                        class="px-3 py-1 inline-flex items-center gap-1.5 text-xs font-semibold rounded-full border {{ $badge }}">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full
                                            {{ $status === 'active' ? 'bg-green-600' : ($status === 'pending' ? 'bg-amber-600' : ($status === 'inactive' ? 'bg-red-600' : 'bg-gray-400')) }}"></span>
                                        {{ $employee->account_status_label }}
                                    </span>
                                    @if ($account?->approved_at && $status === 'active')
                                        <div class="text-xs text-gray-500 mt-1">
                                            Since {{ $account->approved_at->format('M d, Y') }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <button onclick="openEditModal({{ $employee->id }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>

                                        @if (!$account)
                                            <span class="text-xs text-gray-400 italic px-2">No account</span>
                                        @elseif ($isSelf)
                                            <span class="text-xs text-gray-400 italic px-2">This is you</span>
                                        @elseif ($status === 'active')
                                            <form method="POST" action="{{ route('staff.deactivate', $employee) }}"
                                                class="js-account-action" data-action="deactivate"
                                                data-name="{{ $employee->full_name }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                    Deactivate
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('staff.activate', $employee) }}"
                                                class="js-account-action" data-action="activate"
                                                data-name="{{ $employee->full_name }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    {{ $status === 'pending' ? 'Approve' : 'Reactivate' }}
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        <p class="text-lg font-medium text-gray-600 mb-2">No staff found</p>
                                        <p class="text-gray-500">
                                            @if ($searchQuery || $statusFilter || $roleFilter)
                                                Try clearing your search or filters.
                                            @else
                                                Staff appear here once they register through the employee
                                                registration page.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($employees->hasPages())
                <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-200 px-6 py-4">
                    <div class="text-sm text-gray-600">
                        Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of
                        {{ $employees->total() }}
                        results
                    </div>

                    <nav class="flex items-center space-x-2">
                        @if ($employees->onFirstPage())
                            <button class="px-4 py-2 bg-gray-100 text-gray-400 rounded cursor-not-allowed" disabled>
                                ← Previous
                            </button>
                        @else
                            <a href="{{ $employees->previousPageUrl() }}"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                                ← Previous
                            </a>
                        @endif

                        <div class="flex space-x-1">
                            @foreach ($employees->getUrlRange(1, $employees->lastPage()) as $page => $url)
                                @if ($page == $employees->currentPage())
                                    <span class="px-4 py-2 bg-indigo-600 text-white rounded font-medium">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-4 py-2 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition">{{ $page }}</a>
                                @endif
                            @endforeach
                        </div>

                        @if ($employees->hasMorePages())
                            <a href="{{ $employees->nextPageUrl() }}"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-200">
                                Next →
                            </a>
                        @else
                            <button class="px-4 py-2 bg-gray-100 text-gray-400 rounded cursor-not-allowed" disabled>
                                Next →
                            </button>
                        @endif
                    </nav>
                </div>
            @endif
        </div>
    </div>

    {{-- Edit Employee Modal --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-auto transform scale-95 transition-all duration-300">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Edit Employee</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition duration-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Content --}}
                <div id="modalContent" class="p-6">
                    {{-- Loading spinner --}}
                    <div class="flex justify-center items-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                        <span class="ml-2 text-gray-600">Loading employee data...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form template for employee data -->
    <div id="employeeData" class="hidden">
        @foreach ($employees as $employee)
            <div class="employee-template" data-id="{{ $employee->id }}" data-first-name="{{ $employee->first_name }}"
                data-last-name="{{ $employee->last_name }}" data-street="{{ $employee->street }}"
                data-barangay="{{ $employee->barangay }}" data-city="{{ $employee->city }}"
                data-phone="{{ $employee->phone_number }}" data-gender="{{ $employee->gender }}"
                data-role="{{ $employee->role }}">
            </div>
        @endforeach
    </div>

    <script>
        // Activating or switching off a login is not undoable from the staff
        // member's side, so confirm before submitting.
        document.querySelectorAll('.js-account-action').forEach(form => {
            form.addEventListener('submit', function (e) {
                if (form.dataset.confirmed === 'yes') return;

                e.preventDefault();

                const isDeactivate = form.dataset.action === 'deactivate';
                const name = form.dataset.name;

                Swal.fire({
                    icon: isDeactivate ? 'warning' : 'question',
                    title: isDeactivate ? 'Deactivate this account?' : 'Activate this account?',
                    html: isDeactivate
                        ? `<p><strong>${name}</strong> will be signed out and will not be able to log in again until you reactivate them.</p>`
                        : `<p><strong>${name}</strong> will be able to log in to the system straight away.</p>`,
                    showCancelButton: true,
                    confirmButtonText: isDeactivate ? 'Yes, deactivate' : 'Yes, activate',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: isDeactivate ? '#dc2626' : '#16a34a',
                    cancelButtonColor: '#6b7280',
                }).then(result => {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'yes';
                        form.submit();
                    }
                });
            });
        });

        // Modal functions
        function openEditModal(employeeId) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');

            // Find the employee data from hidden templates
            const employeeTemplate = document.querySelector(`.employee-template[data-id="${employeeId}"]`);

            if (employeeTemplate) {
                // Get all employee data from data attributes
                const employeeData = {
                    id: employeeTemplate.getAttribute('data-id'),
                    first_name: employeeTemplate.getAttribute('data-first-name'),
                    last_name: employeeTemplate.getAttribute('data-last-name'),
                    street: employeeTemplate.getAttribute('data-street'),
                    barangay: employeeTemplate.getAttribute('data-barangay'),
                    city: employeeTemplate.getAttribute('data-city'),
                    phone_number: employeeTemplate.getAttribute('data-phone'),
                    gender: employeeTemplate.getAttribute('data-gender'),
                    role: employeeTemplate.getAttribute('data-role')
                };

                // Create the edit form HTML
                const formHTML = `
                            <form action="/employees/${employeeData.id}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit_first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                                        <input type="text" id="edit_first_name" name="first_name" value="${employeeData.first_name || ''}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>

                                    <div>
                                        <label for="edit_last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                                        <input type="text" id="edit_last_name" name="last_name" value="${employeeData.last_name || ''}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="flex items-baseline justify-between border-b border-gray-200 pb-2">
                                        <h4 class="text-sm font-semibold text-gray-800">Address Information</h4>
                                        <span class="text-xs text-gray-500">Optional</span>
                                    </div>
                                    <div class="grid md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="edit_street" class="block text-sm font-medium text-gray-700 mb-1">Street</label>
                                            <input type="text" id="edit_street" name="street" value="${employeeData.street || ''}"
                                                placeholder="Not set"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>

                                        <div>
                                            <label for="edit_barangay" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                                            <input type="text" id="edit_barangay" name="barangay" value="${employeeData.barangay || ''}"
                                                placeholder="Not set"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>

                                        <div>
                                            <label for="edit_city" class="block text-sm font-medium text-gray-700 mb-1">City / Province</label>
                                            <input type="text" id="edit_city" name="city" value="${employeeData.city || ''}"
                                                placeholder="Not set"
                                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Leave any part blank if it is not known. The employee can fill it in themselves.
                                    </p>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="edit_phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                                        <input type="tel" id="edit_phone_number" name="phone_number" value="${employeeData.phone_number || ''}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                                        <div class="flex gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender" value="male"
                                                    ${employeeData.gender === 'male' ? 'checked' : ''}
                                                    class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-gray-700">Male</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="gender" value="female"
                                                    ${employeeData.gender === 'female' ? 'checked' : ''}
                                                    class="text-indigo-600 focus:ring-indigo-500">
                                                <span class="ml-2 text-gray-700">Female</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                                    <select id="edit_role" name="role" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                        <option value="">Select Position</option>
                                        <option value="Staff" ${employeeData.role === 'Staff' ? 'selected' : ''}>Staff</option>
                                        <option value="Assistant" ${employeeData.role === 'Assistant' ? 'selected' : ''}>Assistant</option>
                                        <option value="Technical" ${employeeData.role === 'Technical' ? 'selected' : ''}>Technical</option>
                                        <option value="Cashier" ${employeeData.role === 'Cashier' ? 'selected' : ''}>Cashier</option>
                                    </select>
                                </div>

                                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                        Update Employee
                                    </button>
                                </div>
                            </form>
                        `;

                // Insert the form into the modal
                document.getElementById('modalContent').innerHTML = formHTML;

                // Trigger animation
                setTimeout(() => {
                    document.getElementById('editModal').querySelector('.max-w-2xl').classList.remove('scale-95');
                    document.getElementById('editModal').querySelector('.max-w-2xl').classList.add('scale-100');
                }, 10);
            } else {
                document.getElementById('modalContent').innerHTML = `
                            <div class="p-6 text-center text-red-600">
                                <p>Error loading employee data. Please try again.</p>
                                <button onclick="closeEditModal()" class="mt-4 px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                    Close
                                </button>
                            </div>
                        `;
            }
        }

        function closeEditModal() {
            const modalContent = document.getElementById('editModal').querySelector('.max-w-2xl');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                document.getElementById('editModal').classList.add('hidden');
                document.getElementById('editModal').classList.remove('flex');
            }, 200);
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
@endsection
