<!-- LEFT SIDE: SERVICES WITH INTEGRATED FORM -->
<!-- 🎯 ADJUST CARD SIZES HERE: Change flex-col lg:flex-row gap-6 -->
<div class="w-full flex flex-col lg:flex-row gap-4 lg:gap-6 h-[calc(100vh-110px)]">

    <!-- SERVICES LIST CARD - Adjust width: flex-1 or lg:w-1/3 lg:w-2/5 etc -->
    <div class="flex-1 lg:w-2/5 bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col h-full">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#151F28] to-[#0f161e] text-white p-6">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-list"></i> Services List
            </h2>
        </div>

        <!-- Search & Filter Bar -->
        <div class="border-b border-gray-200 p-4 space-y-3">
            <!-- Search Bar -->
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                <input type="text" id="searchServices" name="search" placeholder="Search services..."
                    class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                    hx-get="{{ route('api.services.list') }}" hx-trigger="input changed delay:250ms, search"
                    hx-target="#servicesContainer" hx-swap="innerHTML"
                    hx-include="[data-status-filter]:checked, input[name='sort']" hx-indicator="#search-loading">
                <!-- Loading indicator -->
                <div id="search-loading" class="htmx-indicator absolute right-3 top-2.5">
                    <svg class="animate-spin h-4 w-4 text-[#151F28]" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Filter by Status -->
            <div class="flex gap-2 flex-wrap">
                <!-- Hidden form inputs for HTMX -->
                <input type="hidden" name="status[]" value="all" data-status-filter="all" id="status-all" checked>
                <input type="checkbox" name="status[]" value="Pending" data-status-filter="Pending" id="status-pending"
                    class="hidden">
                <input type="checkbox" name="status[]" value="In Progress" data-status-filter="In Progress"
                    id="status-in-progress" class="hidden">
                <input type="checkbox" name="status[]" value="On Hold" data-status-filter="On Hold" id="status-on-hold"
                    class="hidden">

                <!-- Sort order hidden input -->
                <input type="hidden" name="sort" value="newest" id="sortOrder">

                <!-- Status Filter Buttons -->
                <ul class="status-navbar flex gap-2 flex-wrap">
                    <li value="ALL">
                        <button type="button" onclick="clearAllStatuses()"
                            class="status-btn px-3 py-1.5 border-2 border-[#151F28] rounded-full text-xs font-semibold text-white bg-[#151F28] hover:bg-[#0f161e] hover:text-white transition-all duration-150 ease-in-out"
                            data-filter="all" hx-get="{{ route('api.services.list') }}" hx-trigger="click"
                            hx-target="#servicesContainer" hx-swap="innerHTML"
                            hx-include="input[name='search'], [data-status-filter]:checked, input[name='sort']"
                            aria-current="true">
                            <i class="fas fa-list mr-1"></i>ALL
                        </button>
                    </li>
                    <li value="Pending">
                        <button type="button" data-status-id="Pending" onclick="toggleStatus('Pending', this)"
                            class="status-btn px-3 py-1.5 border-2 border-[#151F28] rounded-full text-xs font-semibold text-[#151F28] bg-white hover:bg-[#151F28] hover:text-white transition-all duration-150 ease-in-out"
                            data-filter="Pending" hx-get="{{ route('api.services.list') }}" hx-trigger="click"
                            hx-target="#servicesContainer" hx-swap="innerHTML"
                            hx-include="input[name='search'], [data-status-filter]:checked, input[name='sort']"
                            aria-current="false">
                            <i class="fas fa-clock mr-1"></i>Pending
                        </button>
                    </li>
                    <li value="In Progress">
                        <button type="button" data-status-id="In Progress" onclick="toggleStatus('In Progress', this)"
                            class="status-btn px-3 py-1.5 border-2 border-[#151F28] rounded-full text-xs font-semibold text-[#151F28] bg-white hover:bg-[#151F28] hover:text-white transition-all duration-150 ease-in-out"
                            data-filter="In Progress" hx-get="{{ route('api.services.list') }}" hx-trigger="click"
                            hx-target="#servicesContainer" hx-swap="innerHTML"
                            hx-include="input[name='search'], [data-status-filter]:checked, input[name='sort']"
                            aria-current="false">
                            <i class="fas fa-spinner mr-1"></i>In Progress
                        </button>
                    </li>
                    <li value="On Hold">
                        <button type="button" data-status-id="On Hold" onclick="toggleStatus('On Hold', this)"
                            class="status-btn px-3 py-1.5 border-2 border-[#151F28] rounded-full text-xs font-semibold text-[#151F28] bg-white hover:bg-[#151F28] hover:text-white transition-all duration-150 ease-in-out"
                            data-filter="On Hold" hx-get="{{ route('api.services.list') }}" hx-trigger="click"
                            hx-target="#servicesContainer" hx-swap="innerHTML"
                            hx-include="input[name='search'], [data-status-filter]:checked, input[name='sort']"
                            aria-current="false">
                            <i class="fas fa-pause mr-1"></i>On Hold
                        </button>
                    </li>
                </ul>

                <!-- Sort Toggle Buttons (Separate UL) -->
                <ul class="sort-navbar flex gap-2">
                    <li>
                        <button type="button" onclick="toggleSort('newest', this)" id="sortNewest"
                            class="sort-btn px-3 py-1.5 border-2 border-blue-500 rounded-full text-xs font-semibold text-white bg-blue-500 hover:bg-blue-600 transition-all duration-150 ease-in-out"
                            hx-get="{{ route('api.services.list') }}" hx-trigger="click" hx-target="#servicesContainer"
                            hx-swap="innerHTML"
                            hx-include="input[name='search'], [data-status-filter]:checked, input[name='sort']">
                            <i class="fas fa-sort-amount-down mr-1"></i>Newest
                        </button>
                    </li>
                    <li>
                        <button type="button" onclick="toggleSort('oldest', this)" id="sortOldest"
                            class="sort-btn px-3 py-1.5 border-2 border-blue-500 rounded-full text-xs font-semibold text-blue-500 bg-white hover:bg-blue-500 hover:text-white transition-all duration-150 ease-in-out"
                            hx-get="{{ route('api.services.list') }}" hx-trigger="click" hx-target="#servicesContainer"
                            hx-swap="innerHTML"
                            hx-include="input[name='search'], [data-status-filter]:checked, input[name='sort']">
                            <i class="fas fa-sort-amount-up mr-1"></i>Oldest
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Services Container (Scrollable) -->
        <div class="flex-1 overflow-y-auto scrollbar-hide p-6">
            <div class="grid grid-cols-2 gap-4" id="servicesContainer" hx-get="{{ route('api.services.list') }}"
                hx-trigger="load, refreshServices from:body"
                hx-include="[data-status-filter]:checked, input[name='sort']" hx-swap="innerHTML">
                <!-- Loading state -->
                <div class="col-span-2 text-center py-16 text-gray-400">
                    <div class="mb-4">
                        <i class="fas fa-spinner fa-spin text-4xl opacity-50"></i>
                    </div>
                    <p class="text-sm text-gray-500">Loading services...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SERVICE FORM CARD - Adjust width: lg:w-96 or lg:w-1/2 lg:flex-1 -->
    <div class="flex-1 lg:w-1/3 bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col h-full">
        <!-- Form Header with Action Buttons -->
        <div class="bg-gradient-to-r from-[#151F28] to-[#0f161e] text-white p-6 flex justify-between items-center">
            <h2 id="formTitle" class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Create Service
            </h2>
            <div class="flex gap-2">
                <button type="button" id="addServiceTypeBtn"
                    class="px-3 py-1.5 bg-white hover:bg-gray-100 text-[#151F28] rounded-lg font-semibold transition flex items-center gap-1 text-sm">
                    <i class="fas fa-plus"></i>Add Service
                </button>
                <button type="button" id="editServiceTypeBtn"
                    class="px-3 py-1.5 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-semibold transition flex items-center gap-1 text-sm">
                    <i class="fas fa-edit"></i>Edit Service
                </button>
            </div>
        </div>

        <!-- Form Content with Scroll -->
        <div class="flex-1 overflow-y-auto scrollbar-hide p-6">
            <!-- Form Fields -->
            <form id="serviceForm" method="POST" action="{{ route('services.store') }}" class="space-y-3">
                @csrf
                <!-- Hidden Service ID -->
                <input type="hidden" id="serviceIdInput" value="">

                <!-- Customer Input with Auto-Suggest -->
                <div class="relative">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-user mr-1 text-[#151F28]"></i>Customer *
                    </label>
                    <input type="text" name="customer_name" id="customerName"
                        class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                        placeholder="Type customer name..." required autocomplete="off">
                    <input type="hidden" name="customer_id" id="customerId" value="">
                    <div id="customerSuggestions"
                        class="absolute bg-white border border-gray-300 rounded-lg mt-1 w-full max-h-48 overflow-y-auto hidden z-10 shadow-lg">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <!-- Service Type -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            <i class="fas fa-cogs mr-1 text-[#151F28]"></i>Service Type *
                        </label>
                        <select name="service_type_id" id="serviceType"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                            required>
                            <option value="">Select type...</option>
                        </select>
                    </div>
                    <!-- Service Price (Moved Above Service Type) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            <i class="fas fa-peso-sign mr-1 text-[#151F28]"></i>Service Fee *
                        </label>
                        <input type="number" name="total_price" id="totalPrice"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                            step="0.01" min="0" placeholder="0.00" required>
                    </div>
                </div>

                <!-- Type Input with Auto-Suggest -->
                <div class="relative">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-tag mr-1 text-[#151F28]"></i>Type of item*
                    </label>
                    <input type="text" name="type" id="type"
                        class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                        placeholder="e.g., Laptop" required autocomplete="off">
                    <div id="typeSuggestions"
                        class="absolute bg-white border border-gray-300 rounded-lg mt-1 w-full max-h-48 overflow-y-auto hidden z-10 shadow-lg">
                    </div>
                </div>

                <!-- Brand & Model Row -->
                <div class="grid grid-cols-2 gap-2">
                    <div class="relative">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            <i class="fas fa-trademark mr-0.5 text-[#151F28]"></i>Brand
                        </label>
                        <input type="text" name="brand" id="brand"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                            placeholder="Type brand name..." autocomplete="off">
                        <div id="brandSuggestions"
                            class="absolute left-0 right-0 mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-40 overflow-y-auto z-50 hidden">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            <i class="fas fa-microchip mr-0.5 text-[#151F28]"></i>Model
                        </label>
                        <input type="text" name="model" id="model"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                            placeholder="Model">
                    </div>
                </div>

                <!-- Date In & Date Out Row -->
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            <i class="fas fa-calendar-check mr-0.5 text-[#151F28]"></i>Date Received
                        </label>
                        <input type="date" name="date_in" id="dateIn"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            <i class="fas fa-calendar-times mr-0.5 text-[#151F28]"></i>Date to Complete
                        </label>
                        <input type="date" name="date_out" id="dateOut"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm">
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-align-left mr-1 text-[#151F28]"></i>Description *
                    </label>
                    <textarea name="description" id="description"
                        class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                        rows="2" placeholder="Service details..." required></textarea>
                </div>

                <!-- Action -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        <i class="fas fa-tools mr-1 text-[#151F28]"></i>Action Taken
                    </label>
                    <textarea name="action" id="action"
                        class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                        rows="2" placeholder="Actions..."></textarea>
                </div>

                <!-- Status Row (Status shows only when editing) -->
                <div>
                    <!-- Hidden Status Input - Always set to Pending by default -->
                    <input type="hidden" name="status" id="status" value="Pending">

                    <!-- Visible Status Dropdown (Show only when editing) -->
                    <div id="statusContainer" style="display: none;">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            <i class="fas fa-flag mr-0.5 text-[#151F28]"></i>Status *
                        </label>
                        <select id="statusDropdown"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                            required>
                            <option value="">Select...</option>
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="On Hold">On Hold</option>
                        </select>
                    </div>
                </div>

                <!-- Button Group - Save & Receipt (Toggle based on status) -->
                <div id="actionButtons" class="flex gap-2 pt-3 border-t mt-3">
                    <!-- Submit Button (Show when not completed) -->
                    <button type="submit" id="saveBtn"
                        class="flex-1 py-1.5 bg-[#151F28] hover:bg-[#0f161e] text-white rounded-lg font-semibold transition flex items-center justify-center gap-1 text-sm">
                        <i class="fas fa-save"></i>Save
                    </button>

                    <!-- Service Receipt Button (Show only when status is Completed) -->
                    <button type="button" id="serviceReceiptBtn" style="display: none;"
                        class="flex-1 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition flex items-center justify-center gap-1 text-sm">
                        <i class="fas fa-receipt"></i>Receipt
                    </button>
                </div>

                <!-- Acknowledgment Receipt Button (Show except when Completed) -->
                <button type="button" id="acknowledgmentBtn"
                    class="w-full py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2 text-sm mt-2">
                    <i class="fas fa-handshake"></i>Acknowledgment Receipt
                </button>

                <!-- Delete Button (Only show if editing) -->
                <button type="button" id="deleteBtn" style="display: none;"
                    class="w-full py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition text-sm mt-2">
                    <i class="fas fa-trash mr-1"></i>Archive
                </button>
            </form>
        </div>
    </div>

    <!-- ADD/EDIT SERVICE TYPE MODALS -->
    <!-- Add Service Type Modal -->
    <div id="addServiceTypeModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8 relative transform scale-95 transition-transform duration-300"
            id="addServiceTypeModalContent">
            <button id="closeAddServiceTypeModal"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex items-center gap-3 mb-6">
                <h3 class="text-xl font-bold text-gray-800">Add New Service Type</h3>
            </div>
            <form id="addServiceTypeForm" action="" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="service_type_name" class="block text-sm font-medium text-gray-700 mb-2">Service Type
                        Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="service_type_name"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        placeholder="Enter service type name" required>
                </div>
                <div>
                    <label for="service_type_price" class="block text-sm font-medium text-gray-700 mb-2">Service Price
                        (₱) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="service_type_price" step="0.01" min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        placeholder="Enter price" required>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="cancelAddServiceTypeModal"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-500 transition duration-200">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">Save
                        Service Type</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Service Type Modal -->
    <div id="editServiceTypeModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8 relative transform scale-95 transition-transform duration-300"
            id="editServiceTypeModalContent">
            <button id="closeEditServiceTypeModal"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="flex items-center gap-3 mb-6">
                <h3 class="text-xl font-bold text-gray-800">Edit Service Type</h3>
            </div>
            <form id="editServiceTypeForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_service_type_select" class="block text-sm font-medium text-gray-700 mb-2">Select
                        Service Type <span class="text-red-500">*</span></label>
                    <select id="edit_service_type_select"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        required>
                        <option value="">Select a service type...</option>
                    </select>
                </div>
                <div>
                    <label for="edit_service_type_name" class="block text-sm font-medium text-gray-700 mb-2">Service
                        Type Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_service_type_name"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        placeholder="Enter service type name" required>
                </div>
                <div>
                    <label for="edit_service_type_price" class="block text-sm font-medium text-gray-700 mb-2">Service
                        Price (₱) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="edit_service_type_price" step="0.01" min="0"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                        placeholder="Enter price" required>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" id="cancelEditServiceTypeModal"
                        class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 focus:ring-2 focus:ring-gray-500 transition duration-200">Cancel</button>
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 shadow-lg">Update
                        Service Type</button>
                </div>
            </form>
        </div>
    </div>

    <!-- PARTS REPLACEMENT CARD - Column 2, Auto-size based on content -->
    <div id="replacementCard" style="display: none;"
        class="bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col lg:w-1/3 min-w-fit h-full">
        <!-- Replacement Header -->
        <div class="bg-gradient-to-r from-[#151F28] to-[#0f161e] text-white p-6">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-exchange-alt"></i> Parts Replacement
            </h2>
        </div>

        <!-- Replacement Content with Scroll -->
        <div class="flex-1 overflow-y-auto scrollbar-hide p-6">
            <!-- Input Section Header with Add Button -->
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-200">
                <h3 class="text-xs font-bold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-exchange-alt text-[#151F28]"></i>Add New Item
                </h3>
                <button type="button" id="addReplacementBtn"
                    class="px-3 py-1.5 bg-[#151F28] hover:bg-[#0f161e] text-white rounded-lg font-semibold transition text-lg flex items-center justify-center w-10 h-10">
                    <i class="fas fa-plus"></i>
                </button>
            </div>

            <!-- Replacement Input Fields -->
            <!-- Row 1 - Item to Replace, Condition -->
            <div class="grid grid-cols-2 gap-2 mb-2">
                <!-- Item to Replace -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Item *</label>
                    <input type="text" id="itemName" placeholder="eg. RTX 3060 8GB"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-xs">
                </div>

                <!-- Old Item Condition -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Condition</label>
                    <input type="text" id="oldCondition" placeholder="eg. Damaged"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-xs">
                </div>
            </div>

            <!-- Row 2 - New Item, Price, Warranty -->
            <div class="grid grid-cols-3 gap-2 mb-3 pb-3 border-b border-gray-200">
                <!-- New Item -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">New Item</label>
                    <input type="text" id="newItem" placeholder="eg. 1TB SSD"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-xs">
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Price</label>
                    <input type="number" id="newPrice" placeholder="0.00"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-xs"
                        step="0.01" min="0">
                </div>

                <!-- Warranty -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Warranty</label>
                    <select type="text" id="warranty" placeholder="1 Year" class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-xs">
                        <option value="" selected hidden>Select Warranty</option>
                        <option value="3 days">3 days</option>
                        <option value="7 days">7 days</option>
                        <option value="10 days">10 days</option>
                        <option value="15 days">15 days</option>
                        <option value="30 days">30 days</option>
                        <option value="1 year">1 year</option>
                    </select>
              </div>
            </div>

            <!-- Replacements List - Scrollable Container -->
            <div id="replacementsList" class="space-y-3 max-h-80 overflow-y-auto scrollbar-hide">
                <!-- Replacement items will be added here dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
    // Multi-status selection with HTMX (similar to POS system)
    let selectedStatuses = [];
    let currentSort = 'newest'; // Default to newest first

    // Initialize with any pre-selected status
    document.addEventListener('DOMContentLoaded', function () {
        // Start with 'all' selected by default
        selectedStatuses = [];
        console.log('Services filter loaded with multi-selection functionality');
    });

    // Toggle sort order
    function toggleSort(sortType, buttonElement) {
        currentSort = sortType;

        // Update hidden input
        document.getElementById('sortOrder').value = sortType;

        // Update button states
        const newestBtn = document.getElementById('sortNewest');
        const oldestBtn = document.getElementById('sortOldest');

        if (sortType === 'newest') {
            newestBtn.classList.remove('text-blue-500', 'bg-white');
            newestBtn.classList.add('text-white', 'bg-blue-500');
            oldestBtn.classList.remove('text-white', 'bg-blue-500');
            oldestBtn.classList.add('text-blue-500', 'bg-white');
        } else {
            oldestBtn.classList.remove('text-blue-500', 'bg-white');
            oldestBtn.classList.add('text-white', 'bg-blue-500');
            newestBtn.classList.remove('text-white', 'bg-blue-500');
            newestBtn.classList.add('text-blue-500', 'bg-white');
        }

        // Update services display
        updateServicesDisplay();
    }

    // Toggle individual status
    function toggleStatus(statusId, buttonElement) {
        const index = selectedStatuses.indexOf(statusId.toString());

        if (index > -1) {
            // Remove status
            selectedStatuses.splice(index, 1);
            buttonElement.classList.remove('bg-[#151F28]', 'text-white');
            buttonElement.classList.add('bg-white', 'text-[#151F28]');
            buttonElement.setAttribute('aria-current', 'false');

            // Update hidden checkbox
            const checkbox = document.querySelector(`[data-status-filter="${statusId}"]`);
            if (checkbox && checkbox.type === 'checkbox') {
                checkbox.removeAttribute('checked');
            }
        } else {
            // Add status
            selectedStatuses.push(statusId.toString());
            buttonElement.classList.remove('bg-white', 'text-[#151F28]');
            buttonElement.classList.add('bg-[#151F28]', 'text-white');
            buttonElement.setAttribute('aria-current', 'true');

            // Update hidden checkbox
            const checkbox = document.querySelector(`[data-status-filter="${statusId}"]`);
            if (checkbox && checkbox.type === 'checkbox') {
                checkbox.setAttribute('checked', 'checked');
            }
        }

        // Deactivate ALL button when any specific status is selected
        if (selectedStatuses.length > 0) {
            const allButton = document.querySelector('li[value="ALL"] button');
            const allInput = document.querySelector('[data-status-filter="all"]');

            allButton.classList.remove('bg-[#151F28]', 'text-white');
            allButton.classList.add('bg-white', 'text-[#151F28]');
            allButton.setAttribute('aria-current', 'false');
            allInput.removeAttribute('checked');
        }

        // Update services display
        updateServicesDisplay();
    }

    // Clear all statuses (activate ALL)
    function clearAllStatuses() {
        selectedStatuses = [];

        // Reset all status buttons
        document.querySelectorAll('.status-btn').forEach(btn => {
            if (btn.getAttribute('data-status-id')) {
                btn.classList.remove('bg-[#151F28]', 'text-white');
                btn.classList.add('bg-white', 'text-[#151F28]');
                btn.setAttribute('aria-current', 'false');
            }
        });

        // Reset all checkboxes
        document.querySelectorAll('[data-status-filter]:not([data-status-filter="all"])').forEach(checkbox => {
            checkbox.removeAttribute('checked');
        });

        // Update ALL button state
        const allButton = document.querySelector('li[value="ALL"] button');
        const allInput = document.querySelector('[data-status-filter="all"]');

        allButton.classList.remove('bg-white', 'text-[#151F28]');
        allButton.classList.add('bg-[#151F28]', 'text-white');
        allButton.setAttribute('aria-current', 'true');
        allInput.setAttribute('checked', 'checked');

        // Update services display
        updateServicesDisplay();
    }

    // Update services display via HTMX
    function updateServicesDisplay() {
        const params = new URLSearchParams();

        // Add search filter
        const searchInput = document.getElementById('searchServices');
        if (searchInput && searchInput.value) {
            params.append('search', searchInput.value);
        }

        // Add selected statuses
        if (selectedStatuses.length > 0) {
            selectedStatuses.forEach(status => {
                params.append('status[]', status);
            });
        } else {
            // If no specific statuses selected, use 'all'
            params.append('status[]', 'all');
        }

        // Add sort parameter
        params.append('sort', currentSort);

        // Make HTMX request
        const url = '{{ route("api.services.list") }}' + (params.toString() ? '?' + params.toString() : '');

        htmx.ajax('GET', url, {
            target: '#servicesContainer',
            swap: 'innerHTML'
        });
    }
</script>