<!-- RIGHT SIDE: SERVICE FORM (Sticky) -->
<div class="lg:w-96 sticky top-20 h-fit bg-white rounded-2xl shadow-lg overflow-hidden">
    <!-- Form Header -->
    <div class="bg-gradient-to-r from-[#151F28] to-[#0f161e] text-white p-6">
        <h2 id="formTitle" class="text-xl font-bold flex items-center gap-2">
            <i class="fas fa-plus-circle"></i> Add Service
        </h2>
    </div>

    <!-- Form Content -->
    <div class="p-6">
        <!-- Form Fields -->
        <form id="serviceForm" method="POST" action="{{ route('services.store') }}" class="space-y-4">
            @csrf
            <!-- Hidden Service ID -->
            <input type="hidden" id="serviceIdInput" value="">

            <!-- Customer Select -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-[#151F28]"></i>Customer *
                </label>
                <select name="customer_id" id="customerId"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition"
                    required>
                    <option value="">Select customer...</option>
                </select>
            </div>

            <!-- Service Type -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-cogs mr-2 text-[#151F28]"></i>Service Type *
                </label>
                <select name="service_type_id" id="serviceType"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition"
                    required>
                    <option value="">Select type...</option>
                </select>
            </div>

            <!-- Type Input -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tag mr-2 text-[#151F28]"></i>Type *
                </label>
                <input type="text" name="type" id="type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition"
                    placeholder="e.g., Laptop, Desktop" required>
            </div>

            <!-- Brand & Model Row -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-trademark mr-1 text-[#151F28]"></i>Brand
                    </label>
                    <input type="text" name="brand" id="brand"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition"
                        placeholder="Brand">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-microchip mr-1 text-[#151F28]"></i>Model
                    </label>
                    <input type="text" name="model" id="model"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition"
                        placeholder="Model">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-2 text-[#151F28]"></i>Description *
                </label>
                <textarea name="description" id="description"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition"
                    rows="2" placeholder="Service description..." required></textarea>
            </div>

            <!-- Action -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tools mr-2 text-[#151F28]"></i>Action Taken
                </label>
                <textarea name="action" id="action"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition"
                    rows="2" placeholder="Actions performed..."></textarea>
            </div>

            <!-- Status & Price Row -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-flag mr-1 text-[#151F28]"></i>Status *
                    </label>
                    <select name="status" id="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition"
                        required>
                        <option value="">Select...</option>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="On Hold">On Hold</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-peso-sign mr-1 text-[#151F28]"></i>Price *
                    </label>
                    <input type="number" name="total_price" id="totalPrice"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#151F28] transition"
                        step="0.01" min="0" placeholder="0.00" required>
                </div>
            </div>

            <!-- Button Group -->
            <div class="flex gap-3 pt-4 border-t">
                <!-- Submit Button -->
                <button type="submit"
                    class="flex-1 py-2 bg-[#151F28] hover:bg-[#0f161e] text-white rounded-lg font-semibold transition flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i>Save
                </button>

                <!-- Reset Button -->
                <button type="reset"
                    class="flex-1 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-semibold transition flex items-center justify-center gap-2">
                    <i class="fas fa-redo"></i>Clear
                </button>
            </div>

            <!-- Delete Button (Only show if editing) -->
            <button type="button" id="deleteBtn" style="display: none;"
                class="w-full py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold transition">
                <i class="fas fa-trash mr-2"></i>Delete Service
            </button>
        </form>
    </div>
</div>