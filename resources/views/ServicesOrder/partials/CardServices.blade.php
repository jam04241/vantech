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
                <input type="text" id="searchServices" placeholder="Search services..."
                    class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm">
            </div>

            <!-- Filter by Status -->
            <div class="flex gap-2 flex-wrap">
                <button
                    class="px-3 py-1.5 bg-[#151F28] text-white rounded-full text-xs font-semibold transition hover:bg-[#0f161e]"
                    data-filter="all">
                    <i class="fas fa-list mr-1"></i>All
                </button>
                <button
                    class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full text-xs font-semibold transition"
                    data-filter="Pending">
                    <i class="fas fa-clock mr-1"></i>Pending
                </button>
                <button
                    class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full text-xs font-semibold transition"
                    data-filter="In Progress">
                    <i class="fas fa-spinner mr-1"></i>In Progress
                </button>
                <button
                    class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full text-xs font-semibold transition"
                    data-filter="Completed">
                    <i class="fas fa-check mr-1"></i>Completed
                </button>
                <button
                    class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full text-xs font-semibold transition"
                    data-filter="On Hold">
                    <i class="fas fa-pause mr-1"></i>On Hold
                </button>
            </div>
        </div>

        <!-- Services Container (Scrollable) -->
        <div class="flex-1 overflow-y-auto scrollbar-hide p-6">
            <div class="grid grid-cols-2 gap-4" id="servicesContainer">
                <!-- Empty State -->
                <div class="col-span-2 text-center py-16 text-gray-400">
                    <div class="mb-4">
                        <i class="fas fa-inbox text-6xl mb-4 center opacity-40"></i>
                    </div>
                    <p class="text-lg font-bold text-gray-600 mb-2">No Services Found</p>
                    <p class="text-sm text-gray-500 mb-3">There are no active services to display</p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-left text-xs text-blue-700">
                        <p class="font-semibold mb-2"><i class="fas fa-lightbulb mr-2"></i>Tips:</p>
                        <ul class="space-y-1 ml-4">
                            <li>• Create a new service using the form on the right</li>
                            <li>• Check your filters - "Completed" services are hidden by default</li>
                            <li>• Try using the search bar to find existing services</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SERVICE FORM CARD - Adjust width: lg:w-96 or lg:w-1/2 lg:flex-1 -->
    <div class="flex-1 lg:w-1/3 bg-white rounded-2xl shadow-lg overflow-hidden flex flex-col h-full">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-[#151F28] to-[#0f161e] text-white p-6">
            <h2 id="formTitle" class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> Create Service
            </h2>
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

                <!-- Status & Price Row -->
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            <i class="fas fa-flag mr-0.5 text-[#151F28]"></i>Status *
                        </label>
                        <select name="status" id="status"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                            required>
                            <option value="">Select...</option>
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="On Hold">On Hold</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            <i class="fas fa-peso-sign mr-0.5 text-[#151F28]"></i>Price *
                        </label>
                        <input type="number" name="total_price" id="totalPrice"
                            class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-sm"
                            step="0.01" min="0" placeholder="0.00" required>
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
                    <input type="text" id="itemName" placeholder="Hard Drive"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-xs">
                </div>

                <!-- Old Item Condition -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Condition</label>
                    <input type="text" id="oldCondition" placeholder="Damaged"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-xs">
                </div>
            </div>

            <!-- Row 2 - New Item, Price, Warranty -->
            <div class="grid grid-cols-3 gap-2 mb-3 pb-3 border-b border-gray-200">
                <!-- New Item -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">New Item</label>
                    <input type="text" id="newItem" placeholder="1TB SSD"
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
                    <input type="text" id="warranty" placeholder="1 Year"
                        class="w-full px-2 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition text-xs">
                </div>
            </div>

            <!-- Replacements List - Scrollable Container -->
            <div id="replacementsList" class="space-y-3 max-h-80 overflow-y-auto scrollbar-hide">
                <!-- Replacement items will be added here dynamically -->
            </div>
        </div>
    </div>
</div>