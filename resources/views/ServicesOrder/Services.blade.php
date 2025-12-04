<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Services - Job Order Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/htmx.org"></script>
    <style>
        .scrollbar-hide {
            overflow-y: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Top Bar -->
        <div class="bg-white shadow-md border-b border-gray-200 sticky top-0 z-40">
            <div class="px-6 py-4 flex justify-start items-center gap-6">
                <a href="/" class="bg-[#151F28] hover:bg-[#0f161e] text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold text-[#151F28]">
                        <i class="fas fa-wrench mr-2"></i>Services & Job Order
                    </h1>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <div class="mx-auto h-full flex gap-4 lg:gap-6">
                <!-- LEFT SIDE: Services Card List with integrated Form -->
                @include('ServicesOrder.partials.CardServices')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        let selectedServiceId = null;
        let selectedServiceData = null;
        let customersData = [];
        let serviceTypesData = [];
        let brandsData = [];
        let modelsData = [];
        let replacementCount = 0;
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadCustomers();
            loadServiceTypes();
            loadBrands();
            loadServiceItems();
            setupEventListeners();

            // HTMX service card click event listener using event delegation
            document.addEventListener('click', function (e) {
                const card = e.target.closest('.service-card[data-service-id]');
                if (card && !e.target.closest('button')) {
                    const serviceId = card.dataset.serviceId;
                    toggleServiceSelection(serviceId, card);
                }
            });
        });

        // Function to handle new HTMX loaded content
        document.body.addEventListener('htmx:afterSwap', function (evt) {
            // Re-apply click handlers to new service cards loaded by HTMX
            if (evt.detail.target.id === 'servicesContainer') {
                console.log('🔄 HTMX content loaded, service cards ready for interaction');
                // Service card click handlers are now handled by event delegation above
            }
        });

        // Function to handle new HTMX loaded content
        document.body.addEventListener('htmx:afterSwap', function (evt) {
            // Re-apply click handlers to new service cards loaded by HTMX
            if (evt.detail.target.id === 'servicesContainer') {
                console.log('🔄 HTMX content loaded, service cards ready for interaction');
                // Service card click handlers are now handled by event delegation above
            }
        });

        // ============ FETCH DATA FUNCTIONS ============
        function loadCustomers() {
            fetch('/api/customers')
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP ${response.status} loading customers`);
                    return response.json();
                })
                .then(data => {
                    customersData = Array.isArray(data) ? data : [];
                    console.log('✅ Customers loaded:', customersData.length);
                })
                .catch(error => console.error('❌ Error loading customers:', error));
        }

        function loadServiceTypes() {
            fetch('/api/service-types')
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP ${response.status} loading service types`);
                    return response.json();
                })
                .then(data => {
                    serviceTypesData = Array.isArray(data) ? data : [];
                    console.log('✅ Service types loaded:', serviceTypesData.length);
                    populateServiceTypeDropdown(data);
                })
                .catch(error => console.error('❌ Error loading service types:', error));
        }

        function loadBrands() {
            fetch('/api/brands')
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP ${response.status} loading brands`);
                    return response.json();
                })
                .then(data => {
                    brandsData = Array.isArray(data) ? data : [];
                    console.log('✅ Brands loaded:', brandsData.length);
                })
                .catch(error => console.error('❌ Error loading brands:', error));
        }

        function loadServiceItems() {
            fetch('/api/service-items')
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP ${response.status} loading service items`);
                    return response.json();
                })
                .then(data => {
                    modelsData = Array.isArray(data) ? data : [];
                    console.log('✅ Service items loaded:', modelsData.length);
                })
                .catch(error => console.error('❌ Error loading service items:', error));
        }

        // Load services based on selected filters (for multiple status selection)
        function loadFilteredServices() {
            const activeStatuses = [];
            document.querySelectorAll('[data-filter]:not([data-filter="all"]).bg-[#151F28]').forEach(btn => {
                activeStatuses.push(btn.dataset.filter);
            });

            if (activeStatuses.length === 0) {
                loadAllServices('all');
                return;
            }

            console.log('🔄 Loading filtered services for statuses:', activeStatuses);

            // Load services for all active statuses
            Promise.all(activeStatuses.map(status =>
                fetch(`/api/services?status=${status}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status} for status ${status}`);
                        }
                        return response.json();
                    })
            )).then(results => {
                // Merge all results
                const mergedServices = results.flat();
                // Remove duplicates by service ID
                const uniqueServices = Array.from(new Map(mergedServices.map(s => [s.id, s])).values());
                allServices = uniqueServices; // Store globally for search
                console.log('✅ Filtered services loaded:', { count: uniqueServices.length, statuses: activeStatuses });
                console.log('📦 Global allServices updated:', allServices.length, 'services');
                displayServices(uniqueServices);
            }).catch(error => {
                console.error('❌ Error loading filtered services:', error);
                document.getElementById('servicesContainer').innerHTML = `
                    <div class="col-span-2 text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
                        <p class="font-semibold">Error Loading Services</p>
                        <p class="text-xs mt-1">${error.message}</p>
                    </div>
                `;
            });
        }

        // ============ POPULATE DROPDOWNS ============
        function populateServiceTypeDropdown(serviceTypes) {
            const select = document.getElementById('serviceType');
            select.innerHTML = '<option value="">Select type...</option>';
            serviceTypes.forEach(type => {
                const option = document.createElement('option');
                option.value = type.id;
                option.textContent = type.name;
                select.appendChild(option);
            });
        }

        // ============ AUTO-SUGGEST FUNCTIONS ============
        // Customer auto-suggest
        document.addEventListener('input', function (e) {
            if (e.target.id === 'customerName') {
                const searchTerm = e.target.value.toLowerCase().trim();
                const suggestionsDiv = document.getElementById('customerSuggestions');

                if (searchTerm.length < 1) {
                    suggestionsDiv.classList.add('hidden');
                    return;
                }

                const filtered = customersData.filter(customer =>
                    (`${customer.first_name} ${customer.last_name}`).toLowerCase().includes(searchTerm)
                );

                if (filtered.length === 0) {
                    suggestionsDiv.classList.add('hidden');
                    return;
                }

                suggestionsDiv.innerHTML = '';
                filtered.forEach(customer => {
                    const div = document.createElement('div');
                    div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer text-xs text-gray-800 border-b border-gray-100';
                    div.textContent = `${customer.first_name} ${customer.last_name}`;
                    div.addEventListener('click', function () {
                        document.getElementById('customerName').value = `${customer.first_name} ${customer.last_name}`;
                        document.getElementById('customerId').value = customer.id;
                        suggestionsDiv.classList.add('hidden');
                    });
                    suggestionsDiv.appendChild(div);
                });

                suggestionsDiv.classList.remove('hidden');
            }
            // Type input auto-suggest
            else if (e.target.id === 'type') {
                const searchTerm = e.target.value.toLowerCase().trim();
                const suggestionsDiv = document.getElementById('typeSuggestions');

                if (searchTerm.length < 1) {
                    suggestionsDiv.classList.add('hidden');
                    return;
                }

                const filtered = modelsData.filter(type =>
                    type.toLowerCase().includes(searchTerm)
                );

                if (filtered.length === 0) {
                    suggestionsDiv.classList.add('hidden');
                    return;
                }

                suggestionsDiv.innerHTML = '';
                filtered.forEach(type => {
                    const div = document.createElement('div');
                    div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer text-xs text-gray-800 border-b border-gray-100';
                    div.textContent = type;
                    div.addEventListener('click', function () {
                        document.getElementById('type').value = type;
                        suggestionsDiv.classList.add('hidden');
                    });
                    suggestionsDiv.appendChild(div);
                });

                suggestionsDiv.classList.remove('hidden');
            }
            // Brand input auto-suggest
            else if (e.target.id === 'brand') {
                const searchTerm = e.target.value.toLowerCase().trim();
                const suggestionsDiv = document.getElementById('brandSuggestions');

                if (searchTerm.length < 1) {
                    suggestionsDiv.classList.add('hidden');
                    return;
                }

                const filtered = brandsData.filter(brand =>
                    brand.brand_name.toLowerCase().includes(searchTerm)
                );

                if (filtered.length === 0) {
                    suggestionsDiv.classList.add('hidden');
                    return;
                }

                suggestionsDiv.innerHTML = '';
                filtered.forEach(brand => {
                    const div = document.createElement('div');
                    div.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer text-xs text-gray-800 border-b border-gray-100';
                    div.textContent = brand.brand_name;
                    div.addEventListener('click', function () {
                        document.getElementById('brand').value = brand.brand_name;
                        suggestionsDiv.classList.add('hidden');
                    });
                    suggestionsDiv.appendChild(div);
                });

                suggestionsDiv.classList.remove('hidden');
            }
        });

        // ============ SERVICE CARD DISPLAY ============
        function displayServices(services) {
            const servicesContainer = document.getElementById('servicesContainer');
            servicesContainer.innerHTML = '';

            if (services.length === 0) {
                servicesContainer.innerHTML = `
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
                `;
                return;
            }

            // Check if "All" button is active (by checking if it has bg-[#151F28] class)
            const allBtn = document.querySelector('[data-filter="all"]');
            const isAllButtonActive = allBtn && allBtn.classList.contains('bg-[#151F28]');

            let filteredServices = services;

            if (isAllButtonActive) {
                // "All" button is active: exclude Completed and Canceled
                filteredServices = services.filter(s => !['Completed', 'Canceled'].includes(s.status));
            }
            // If "All" button is NOT active, show whatever statuses are selected (no filtering needed)

            filteredServices.forEach((service, index) => {
                const statusColors = {
                    'Pending': 'bg-yellow-100 text-yellow-800',
                    'In Progress': 'bg-blue-100 text-blue-800',
                    'Completed': 'bg-green-100 text-green-800',
                    'On Hold': 'bg-red-100 text-red-800',
                    'Canceled': 'bg-gray-100 text-gray-800'
                };

                console.log(`📋 Service #${service.id}:`, {
                    id: service.id,
                    customer: service.customer,
                    serviceType: service.serviceType,
                    type: service.type,
                    brand: service.brand,
                    model: service.model,
                    total_price: service.total_price,
                    status: service.status,
                    description: service.description
                });

                const card = document.createElement('div');
                card.setAttribute('data-service-id', service.id);
                card.className = 'border border-gray-200 rounded-lg p-4 hover:shadow-md transition hover:border-[#151F28] cursor-pointer';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">#${index + 1} - ${service.serviceType?.name || 'N/A'}</h3>
                            <p class="text-xs text-gray-600 mt-0.5"><i class="fas fa-user mr-1"></i>${service.customer?.first_name || '-'} ${service.customer?.last_name || ''}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap ${statusColors[service.status] || 'bg-gray-100'}">
                            <i class="fas fa-info-circle mr-1"></i>${service.status}
                        </span>
                    </div>
                    <div class="text-xs space-y-1 mb-2">
                        <p class="text-gray-600"><span class="font-semibold">Type of Item:</span> ${service.type || '-'}</p>
                        <p class="text-gray-600"><span class="font-semibold">Brand:</span> ${service.brand || '-'}</p>
                        <p class="text-gray-600"><span class="font-semibold">Model:</span> ${service.model || '-'}</p>
                        <p class="text-gray-600"><span class="font-semibold">Service Fee:</span> ₱${parseFloat(service.total_price || 0).toFixed(2)}</p>
                    </div>
                    <p class="text-xs text-gray-700 border-t pt-2 line-clamp-2">${service.description || '-'}</p>
                `;
                servicesContainer.appendChild(card);
            });
        }

        // ============ EVENT LISTENERS SETUP ============
        function setupEventListeners() {
            // Note: Service card click handler is now handled by event delegation in DOMContentLoaded
            // Note: Filter buttons and search bar now use HTMX for real-time functionality

            // Status change (hidden input)
            document.getElementById('status').addEventListener('change', function (e) {
                toggleReceiptButtons(e.target.value);
            });

            // Status dropdown change (visible dropdown for editing)
            const statusDropdown = document.getElementById('statusDropdown');
            if (statusDropdown) {
                statusDropdown.addEventListener('change', function (e) {
                    const newStatus = e.target.value;
                    document.getElementById('status').value = newStatus; // Sync hidden input
                    toggleReceiptButtons(newStatus);
                });
            }

            // Save button
            document.getElementById('saveBtn').addEventListener('click', handleSaveService);

            // Service Receipt button
            document.getElementById('serviceReceiptBtn').addEventListener('click', handleServiceReceipt);

            // Acknowledgment button
            document.getElementById('acknowledgmentBtn').addEventListener('click', handleAcknowledgmentReceipt);

            // Delete/Archive button
            document.getElementById('deleteBtn').addEventListener('click', handleArchiveService);

            // Add Replacement button
            document.getElementById('addReplacementBtn').addEventListener('click', handleAddReplacement);

            // ============ SERVICE TYPE MODAL HANDLERS ============
            // Add Service Type button
            document.getElementById('addServiceTypeBtn').addEventListener('click', function () {
                document.getElementById('addServiceTypeModal').classList.remove('hidden');
            });

            // Close Add Service Type Modal
            document.getElementById('closeAddServiceTypeModal').addEventListener('click', function () {
                document.getElementById('addServiceTypeModal').classList.add('hidden');
                document.getElementById('addServiceTypeForm').reset();
            });

            document.getElementById('cancelAddServiceTypeModal').addEventListener('click', function () {
                document.getElementById('addServiceTypeModal').classList.add('hidden');
                document.getElementById('addServiceTypeForm').reset();
            });

            // Add Service Type Form Submit
            document.getElementById('addServiceTypeForm').addEventListener('submit', async function (e) {
                e.preventDefault();
                const name = document.getElementById('service_type_name').value.trim();
                const price = document.getElementById('service_type_price').value.trim();

                if (!name || !price) {
                    Swal.fire('Error', 'Please fill in all fields', 'error');
                    return;
                }

                const payload = {
                    name: name,
                    price: parseFloat(price)
                };

                console.log('📤 Sending add service type payload:', payload);

                try {
                    const response = await fetch('/api/service-types', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();
                    console.log('📥 Response:', { status: response.status, result });

                    if (response.ok && result.success) {
                        console.log('✅ Service Type created:', result.data);
                        Swal.fire('Success', 'Service Type created successfully!', 'success');
                        document.getElementById('addServiceTypeModal').classList.add('hidden');
                        document.getElementById('addServiceTypeForm').reset();
                        loadServiceTypes(); // Reload service types
                    } else {
                        console.error('❌ Error creating service type:', result);
                        let errorMsg = result.message || 'Failed to create service type';
                        if (result.errors) {
                            const errorList = Object.values(result.errors).flat().join(', ');
                            errorMsg = errorList || errorMsg;
                        }
                        Swal.fire('Error', errorMsg, 'error');
                    }
                } catch (error) {
                    console.error('❌ Error:', error);
                    Swal.fire('Error', 'An error occurred while creating service type', 'error');
                }
            });

            // Edit Service Type button
            document.getElementById('editServiceTypeBtn').addEventListener('click', function () {
                const editSelect = document.getElementById('edit_service_type_select');
                editSelect.innerHTML = '<option value="">Select a service type...</option>';
                serviceTypesData.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type.id;
                    option.textContent = `${type.name} (₱${parseFloat(type.price).toFixed(2)})`;
                    editSelect.appendChild(option);
                });
                document.getElementById('editServiceTypeModal').classList.remove('hidden');
            });

            // Close Edit Service Type Modal
            document.getElementById('closeEditServiceTypeModal').addEventListener('click', function () {
                document.getElementById('editServiceTypeModal').classList.add('hidden');
                document.getElementById('editServiceTypeForm').reset();
            });

            document.getElementById('cancelEditServiceTypeModal').addEventListener('click', function () {
                document.getElementById('editServiceTypeModal').classList.add('hidden');
                document.getElementById('editServiceTypeForm').reset();
            });

            // Edit Service Type Select Change
            document.getElementById('edit_service_type_select').addEventListener('change', function () {
                const selectedId = this.value;
                if (selectedId) {
                    const selectedType = serviceTypesData.find(t => t.id == selectedId);
                    if (selectedType) {
                        document.getElementById('edit_service_type_name').value = selectedType.name;
                        document.getElementById('edit_service_type_price').value = selectedType.price;
                    }
                }
            });

            // Edit Service Type Form Submit
            document.getElementById('editServiceTypeForm').addEventListener('submit', async function (e) {
                e.preventDefault();
                const serviceTypeId = document.getElementById('edit_service_type_select').value;

                if (!serviceTypeId) {
                    Swal.fire('Error', 'Please select a service type to edit', 'error');
                    return;
                }

                const name = document.getElementById('edit_service_type_name').value.trim();
                const price = document.getElementById('edit_service_type_price').value.trim();

                if (!name || !price) {
                    Swal.fire('Error', 'Please fill in all fields', 'error');
                    return;
                }

                const payload = {
                    name: name,
                    price: parseFloat(price)
                };

                console.log('📤 Sending update service type payload:', { serviceTypeId, ...payload });

                try {
                    const response = await fetch(`/api/service-types/${serviceTypeId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const result = await response.json();
                    console.log('📥 Response:', { status: response.status, result });

                    if (response.ok && result.success) {
                        console.log('✅ Service Type updated:', result.data);
                        Swal.fire('Success', 'Service Type updated successfully!', 'success');
                        document.getElementById('editServiceTypeModal').classList.add('hidden');
                        document.getElementById('editServiceTypeForm').reset();
                        loadServiceTypes(); // Reload service types
                    } else {
                        console.error('❌ Error updating service type:', result);
                        let errorMsg = result.message || 'Failed to update service type';
                        if (result.errors) {
                            const errorList = Object.values(result.errors).flat().join(', ');
                            errorMsg = errorList || errorMsg;
                        }
                        Swal.fire('Error', errorMsg, 'error');
                    }
                } catch (error) {
                    console.error('❌ Error:', error);
                    Swal.fire('Error', 'An error occurred while updating service type', 'error');
                }
            });

            // Service Type Dropdown Change - Auto-fill Service Price
            document.getElementById('serviceType').addEventListener('change', function () {
                const selectedId = this.value;
                if (selectedId) {
                    const selectedType = serviceTypesData.find(t => t.id == selectedId);
                    if (selectedType) {
                        document.getElementById('totalPrice').value = selectedType.price;
                        console.log('💰 Service price auto-filled:', selectedType.price);
                    }
                } else {
                    document.getElementById('totalPrice').value = '';
                }
            });
        }

        // ============ SERVICE SELECTION & TOGGLE ============
        function toggleServiceSelection(serviceId, card) {
            if (selectedServiceId === serviceId) {
                // Toggle off
                clearServiceForm();
                selectedServiceId = null;
                selectedServiceData = null;
                card.classList.remove('ring-2', 'ring-[#151F28]');
            } else {
                // Toggle on - fetch and populate
                if (selectedServiceId) {
                    document.querySelector(`[data-service-id="${selectedServiceId}"]`)?.classList.remove('ring-2', 'ring-[#151F28]');
                }

                selectedServiceId = serviceId;
                card.classList.add('ring-2', 'ring-[#151F28]');

                // Fetch service details from backend
                fetch(`/api/services/${serviceId}`)
                    .then(response => {
                        console.log('📥 Service detail response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP ${response.status}: Failed to load service`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('✅ Service data loaded:', data);
                        selectedServiceData = data;
                        populateServiceForm(data);
                    })
                    .catch(error => {
                        console.error('❌ Error fetching service:', error);
                        Swal.fire('Error', 'Failed to load service details: ' + error.message, 'error');
                    });
            }
        }

        // ============ FORM POPULATION ============
        function populateServiceForm(data) {
            console.log('📝 Populating form with service data:', {
                id: data.id,
                customer: data.customer,
                serviceType: data.serviceType,
                type: data.type,
                description: data.description
            });

            if (!data.customer) {
                console.warn('⚠️  Warning: Customer data is missing from service');
            }
            if (!data.serviceType) {
                console.warn('⚠️  Warning: Service Type data is missing from service');
            }

            document.getElementById('serviceIdInput').value = data.id;
            document.getElementById('customerName').value = data.customer
                ? `${data.customer.first_name || ''} ${data.customer.last_name || ''}`.trim()
                : '';
            document.getElementById('customerId').value = data.customer_id || '';
            document.getElementById('serviceType').value = data.service_type_id || '';
            document.getElementById('type').value = data.type || '';
            document.getElementById('brand').value = data.brand || '';
            document.getElementById('model').value = data.model || '';
            // Format dates from ISO format to yyyy-MM-dd for HTML date input
            document.getElementById('dateIn').value = data.date_in ? data.date_in.split('T')[0] : '';
            document.getElementById('dateOut').value = data.date_out ? data.date_out.split('T')[0] : '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('action').value = data.action || '';
            document.getElementById('status').value = data.status || '';
            document.getElementById('totalPrice').value = data.total_price || '';

            document.getElementById('formTitle').innerHTML = '<i class="fas fa-pencil-alt"></i> Progress Service';
            document.getElementById('deleteBtn').style.display = 'block';
            document.getElementById('saveBtn').style.display = 'flex';
            document.getElementById('replacementCard').style.display = 'flex';

            // Show status dropdown when editing
            document.getElementById('statusContainer').style.display = 'block';
            document.getElementById('statusDropdown').value = data.status || 'Pending';
            document.getElementById('status').value = data.status || 'Pending';

            // Load replacements
            displayReplacements(data.replacements || []);

            // Toggle buttons based on status
            toggleReceiptButtons(data.status);

            console.log('✅ Form populated successfully');
        }

        function clearServiceForm() {
            document.getElementById('serviceForm').reset();
            document.getElementById('serviceIdInput').value = '';
            document.getElementById('status').value = 'Pending'; // Default to Pending
            document.getElementById('statusDropdown').value = ''; // Clear visible dropdown
            document.getElementById('statusContainer').style.display = 'none'; // Hide status dropdown
            document.getElementById('formTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Create Service';
            document.getElementById('deleteBtn').style.display = 'none';
            document.getElementById('saveBtn').style.display = 'flex';
            document.getElementById('serviceReceiptBtn').style.display = 'none';
            document.getElementById('replacementCard').style.display = 'none';
            document.getElementById('replacementsList').innerHTML = '';
            replacementCount = 0;
            selectedServiceData = null;
        }

        function toggleReceiptButtons(status) {
            const saveBtn = document.getElementById('saveBtn');
            const receiptBtn = document.getElementById('serviceReceiptBtn');
            const ackBtn = document.getElementById('acknowledgmentBtn');

            if (status === 'Completed') {
                saveBtn.style.display = 'none';
                receiptBtn.style.display = 'flex';
                ackBtn.style.display = 'none';
            } else {
                saveBtn.style.display = 'flex';
                receiptBtn.style.display = 'none';
                ackBtn.style.display = 'flex';
            }
        }

        // ============ FORM SUBMISSION HANDLERS ============
        async function handleSaveService(e) {
            e.preventDefault();

            // Validate required fields
            const customerName = document.getElementById('customerName').value.trim();
            const customerId = document.getElementById('customerId').value;
            const serviceTypeId = document.getElementById('serviceType').value;
            const type = document.getElementById('type').value.trim();
            const description = document.getElementById('description').value.trim();
            const status = document.getElementById('status').value;
            const totalPrice = document.getElementById('totalPrice').value;

            if (!customerId || !serviceTypeId || !type || !description || !status || !totalPrice) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Required Fields',
                    text: 'Please fill in all required fields',
                    confirmButtonColor: '#151F28'
                });
                return;
            }

            // Prepare service data (matches ServiceRequest validation)
            const serviceData = {
                customer_id: parseInt(customerId),
                service_type_id: parseInt(serviceTypeId),
                type: type,
                brand: document.getElementById('brand').value || null,
                model: document.getElementById('model').value || null,
                date_in: document.getElementById('dateIn').value || null,
                date_out: document.getElementById('dateOut').value || null,
                description: description,
                action: document.getElementById('action').value || null,
                status: status,
                total_price: parseFloat(totalPrice)
            };

            try {
                const serviceId = document.getElementById('serviceIdInput').value;
                let url, method;

                if (serviceId) {
                    // Update existing service
                    url = `/api/services/${serviceId}`;
                    method = 'PUT';
                } else {
                    // Create new service
                    url = '/api/services';
                    method = 'POST';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(serviceData)
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to save service');
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: result.message,
                    confirmButtonColor: '#151F28'
                }).then(() => {
                    clearServiceForm();
                    // Real-time update: Refresh services list via HTMX maintaining current filters
                    refreshServicesList();
                });

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    confirmButtonColor: '#151F28'
                });
            }
        }

        // Function to refresh services list via HTMX (maintains current filters)
        function refreshServicesList() {
            // Trigger custom event to refresh services container
            document.body.dispatchEvent(new CustomEvent('refreshServices'));

            // Cross-tab/window communication for real-time updates
            if (typeof (Storage) !== "undefined") {
                localStorage.setItem('serviceUpdated', Date.now().toString());
            }
        }        // Save all replacements to database
        async function saveReplacements(serviceId) {
            const replacementItems = document.querySelectorAll('#replacementsList > div[data-item-name]');

            for (const item of replacementItems) {
                const replacementData = {
                    service_id: parseInt(serviceId),
                    item_name: item.getAttribute('data-item-name'),
                    old_item_condition: item.getAttribute('data-condition') || '',
                    new_item: item.getAttribute('data-new-item'),
                    new_item_price: parseFloat(item.getAttribute('data-price')),
                    new_item_warranty: item.getAttribute('data-warranty') || null
                };

                try {
                    await fetch('/api/service-replacements', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify(replacementData)
                    });
                } catch (error) {
                    console.error('Error saving replacement:', error);
                }
            }
        }

        // ============ PART REPLACEMENT HANDLERS ============
        function handleAddReplacement(e) {
            e.preventDefault();

            const serviceId = document.getElementById('serviceIdInput').value;
            if (!serviceId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Service Selected',
                    text: 'Please create or select a service first',
                    confirmButtonColor: '#151F28'
                });
                return;
            }

            const itemName = document.getElementById('itemName').value.trim();
            const oldCondition = document.getElementById('oldCondition').value.trim();
            const newItem = document.getElementById('newItem').value.trim();
            const newPrice = document.getElementById('newPrice').value;
            const warranty = document.getElementById('warranty').value.trim();

            const missingFields = [];
            if (!itemName) missingFields.push('Item to Replace');
            if (!oldCondition) missingFields.push('Condition');
            if (!newItem) missingFields.push('New Item');
            if (!newPrice) missingFields.push('Price');

            if (missingFields.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Required Fields',
                    html: '<strong>Please fill in:</strong><br/>' + missingFields.map(field => '• ' + field).join('<br/>'),
                    confirmButtonColor: '#151F28'
                });
                return;
            }

            // Post directly to API
            const replacementData = {
                service_id: parseInt(serviceId),
                item_name: itemName,
                old_item_condition: oldCondition,
                new_item: newItem,
                new_item_price: parseFloat(newPrice),
                new_item_warranty: warranty || null,
                is_disabled: false  // Enable it immediately (send as boolean)
            };

            console.log('📤 Sending replacement data:', replacementData);
            console.log('📤 Payload JSON:', JSON.stringify(replacementData));

            fetch('/api/service-replacements', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(replacementData)
            })
                .then(response => {
                    console.log('📥 Response status:', response.status);
                    console.log('📥 Response content-type:', response.headers.get('content-type'));

                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('❌ Server response text:', text);
                            try {
                                const errorData = JSON.parse(text);
                                console.error('❌ Parsed error data:', errorData);
                                throw new Error(errorData.message || errorData.error || `HTTP ${response.status}: Failed to add replacement`);
                            } catch (parseError) {
                                console.error('❌ Failed to parse error response:', parseError);
                                throw new Error(`HTTP ${response.status}: Failed to add replacement. Server response: ${text.substring(0, 200)}`);
                            }
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Replacement created with data:', data);

                    // Validate response has required fields
                    if (!data.replacement || !data.replacement.id) {
                        console.error('❌ Response missing replacement data:', data);
                        throw new Error('Server response missing replacement ID');
                    }

                    // Add to DOM with database ID
                    addReplacementItem(itemName, oldCondition, newItem, newPrice, warranty, data.replacement.id);

                    // Clear inputs
                    document.getElementById('itemName').value = '';
                    document.getElementById('oldCondition').value = '';
                    document.getElementById('newItem').value = '';
                    document.getElementById('newPrice').value = '';
                    document.getElementById('warranty').value = '';
                    document.getElementById('itemName').focus();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Part replacement added successfully',
                        confirmButtonColor: '#151F28',
                        timer: 1500
                    });
                })
                .catch(error => {
                    console.error('❌ Full error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to add part replacement',
                        confirmButtonColor: '#151F28'
                    });
                });
        }

        function addReplacementItem(itemName, oldCondition, newItem, newPrice, warranty, replacementId = null) {
            replacementCount++;
            const replacementsList = document.getElementById('replacementsList');

            const replacementRow = document.createElement('div');
            replacementRow.className = 'border border-gray-200 rounded-lg bg-gray-50 overflow-hidden';
            replacementRow.setAttribute('data-item-name', itemName);
            replacementRow.setAttribute('data-condition', oldCondition);
            replacementRow.setAttribute('data-new-item', newItem);
            replacementRow.setAttribute('data-price', newPrice);
            replacementRow.setAttribute('data-warranty', warranty);
            if (replacementId) {
                replacementRow.setAttribute('data-replacement-id', replacementId);
            }

            replacementRow.innerHTML = `
                <div class="bg-gray-100 px-3 py-2 flex justify-between items-center border-b border-gray-200">
                    <p class="font-semibold text-gray-800 text-xs"><i class="fas fa-hashtag mr-1 text-[#151F28]"></i>Item #${replacementCount}</p>
                    <button type="button" class="remove-replacement text-red-500 hover:text-red-700 text-xs">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="p-3 space-y-2 text-xs">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-semibold text-gray-700 mb-0.5">Item to Replace</p>
                            <p class="text-gray-800 font-semibold">${itemName}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700 mb-0.5">New Item</p>
                            <p class="text-gray-800 font-semibold">${newItem || '-'}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="font-semibold text-gray-700 mb-0.5">Condition</p>
                            <p class="text-gray-600">${oldCondition || '-'}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-700 mb-0.5">Warranty</p>
                            <p class="text-gray-600">${warranty || '-'}</p>
                        </div>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-700 mb-0.5">Price</p>
                        <p class="text-gray-800 font-semibold">₱${parseFloat(newPrice || 0).toFixed(2)}</p>
                    </div>
                </div>
            `;

            replacementsList.appendChild(replacementRow);

            replacementRow.querySelector('.remove-replacement').addEventListener('click', async function () {
                const dbReplacementId = replacementRow.getAttribute('data-replacement-id');
                const currentServiceId = document.getElementById('serviceIdInput').value; // Get service ID from form

                // Show confirmation dialog
                const result = await Swal.fire({
                    icon: 'warning',
                    title: 'Delete Permanently?',
                    text: 'This part replacement will be permanently deleted. This action cannot be undone.',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, Delete Permanently',
                    cancelButtonText: 'Cancel'
                });

                if (!result.isConfirmed) {
                    return;
                }

                if (dbReplacementId) {
                    // Has database ID - soft delete by setting is_disabled = 1
                    try {
                        const updatePayload = {
                            service_id: parseInt(currentServiceId), // Use the service ID from the form
                            item_name: itemName,
                            old_item_condition: oldCondition,
                            new_item: newItem,
                            new_item_price: parseFloat(newPrice),
                            new_item_warranty: warranty || null,
                            is_disabled: true  // Disable it (soft delete)
                        };

                        console.log('📤 Updating replacement with payload:', updatePayload);
                        console.log('🔗 Service ID from form:', currentServiceId);
                        console.log('🗑️ Replacement ID to delete:', dbReplacementId);

                        const response = await fetch(`/api/service-replacements/${dbReplacementId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            },
                            body: JSON.stringify(updatePayload)
                        });

                        console.log('📥 Response status:', response.status);
                        console.log('📥 Response headers:', {
                            contentType: response.headers.get('content-type')
                        });

                        let responseData;
                        try {
                            const text = await response.text();
                            console.log('📥 Raw response:', text);
                            responseData = text ? JSON.parse(text) : {};
                        } catch (e) {
                            console.error('❌ Failed to parse JSON response:', e);
                            throw new Error('Server returned invalid JSON response');
                        }

                        if (response.ok) {
                            console.log('✅ Replacement deleted successfully:', responseData);
                            replacementRow.remove();
                            replacementCount--;
                            renumberReplacements();

                            Swal.fire({
                                icon: 'success',
                                title: 'Permanently Deleted',
                                text: 'Part replacement has been permanently deleted',
                                confirmButtonColor: '#151F28',
                                timer: 1500
                            });
                        } else {
                            console.error('❌ Server error response:', responseData);
                            throw new Error(responseData.message || responseData.error || 'Failed to remove replacement');
                        }
                    } catch (error) {
                        console.error('❌ Error deleting replacement:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message || 'Failed to remove part replacement',
                            confirmButtonColor: '#151F28'
                        });
                    }
                } else {
                    // No database ID - just remove from UI (temporary item)
                    replacementRow.remove();
                    replacementCount--;
                    renumberReplacements();

                    Swal.fire({
                        icon: 'success',
                        title: 'Removed',
                        text: 'Part replacement has been removed',
                        confirmButtonColor: '#151F28',
                        timer: 1000
                    });
                }
            });
        }

        function renumberReplacements() {
            const items = document.querySelectorAll('#replacementsList > div');
            items.forEach((item, index) => {
                const numberBadge = item.querySelector('p:first-child');
                numberBadge.innerHTML = `<i class="fas fa-hashtag mr-1 text-[#151F28]"></i>Item #${index + 1}`;
            });
            replacementCount = items.length;
        }

        function displayReplacements(replacements) {
            const replacementsList = document.getElementById('replacementsList');
            replacementsList.innerHTML = '';
            replacementCount = 0;

            replacements.forEach((replacement) => {
                addReplacementItem(
                    replacement.item_name,
                    replacement.old_item_condition,
                    replacement.new_item,
                    replacement.new_item_price,
                    replacement.new_item_warranty,
                    replacement.id  // Pass the database replacement ID
                );
            });
        }

        // ============ RECEIPT HANDLERS ============
        function handleAcknowledgmentReceipt(e) {
            e.preventDefault();

            const customerName = document.getElementById('customerName').value.trim();
            const serviceType = document.getElementById('serviceType').value;
            const type = document.getElementById('type').value.trim();
            const description = document.getElementById('description').value.trim();
            const serviceId = document.getElementById('serviceIdInput').value;

            if (!customerName || !serviceType || !type || !description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields before viewing acknowledgement',
                    confirmButtonColor: '#151F28'
                });
                return;
            }

            if (!serviceId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Service Not Saved',
                    text: 'Please save the service first before issuing acknowledgement receipt',
                    confirmButtonColor: '#151F28'
                });
                return;
            }

            const serviceTypeSelect = document.getElementById('serviceType');
            const serviceTypeName = serviceTypeSelect.options[serviceTypeSelect.selectedIndex]?.text || '-';

            const serviceData = {
                customerName: customerName,
                dateIn: document.getElementById('dateIn').value || '-',
                dateOut: document.getElementById('dateOut').value || '-',
                status: document.getElementById('status').value || 'Pending',
                type: type,
                brand: document.getElementById('brand').value || '-',
                model: document.getElementById('model').value || '-',
                serviceTypeName: serviceTypeName,
                description: description,
                totalPrice: document.getElementById('totalPrice').value || '0.00'
            };

            sessionStorage.setItem('serviceData', JSON.stringify(serviceData));

            // Call audit logging endpoint
            fetch(`/services/${serviceId}/log-acknowledgment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to log acknowledgment');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Acknowledgment logged:', data);
                    window.location.href = '/acknowledgement-receipt';
                })
                .catch(error => {
                    console.error('❌ Error logging acknowledgment:', error);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Logging Warning',
                        text: 'Acknowledgment receipt will be issued but logging may have failed',
                        confirmButtonColor: '#151F28'
                    }).then(() => {
                        window.location.href = '/acknowledgement-receipt';
                    });
                });
        }

        async function handleServiceReceipt(e) {
            e.preventDefault();

            const customerName = document.getElementById('customerName').value.trim();
            const serviceType = document.getElementById('serviceType').value;
            const type = document.getElementById('type').value.trim();
            const description = document.getElementById('description').value.trim();
            const serviceId = document.getElementById('serviceIdInput').value;

            if (!customerName || !serviceType || !type || !description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields before viewing service receipt',
                    confirmButtonColor: '#151F28'
                });
                return;
            }

            if (!serviceId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Service Not Saved',
                    text: 'Please save the service first before issuing service receipt',
                    confirmButtonColor: '#151F28'
                });
                return;
            }

            try {
                // First, update service status to Completed
                if (serviceId) {
                    const serviceUpdateData = {
                        customer_id: parseInt(document.getElementById('customerId').value),
                        service_type_id: parseInt(document.getElementById('serviceType').value),
                        type: type,
                        brand: document.getElementById('brand').value || null,
                        model: document.getElementById('model').value || null,
                        date_in: document.getElementById('dateIn').value || null,
                        date_out: document.getElementById('dateOut').value || null,
                        description: description,
                        action: document.getElementById('action').value || null,
                        status: 'Completed',
                        total_price: parseFloat(document.getElementById('totalPrice').value)
                    };

                    const response = await fetch(`/api/services/${serviceId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify(serviceUpdateData)
                    });

                    if (!response.ok) {
                        throw new Error('Failed to update service');
                    }
                }
            } catch (error) {
                console.error('Error updating service:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update service status',
                    confirmButtonColor: '#151F28'
                });
                return;
            }

            const serviceTypeSelect = document.getElementById('serviceType');
            const serviceTypeName = serviceTypeSelect.options[serviceTypeSelect.selectedIndex]?.text || '-';

            const actionTaken = document.getElementById('action')?.value || '-';

            // Get part replacements
            const replacementItems = document.querySelectorAll('#replacementsList > div[data-item-name]');
            let partReplacementText = '-';
            if (replacementItems.length > 0) {
                const parts = [];
                replacementItems.forEach((item, index) => {
                    const itemName = item.getAttribute('data-item-name') || '';
                    const newItem = item.getAttribute('data-new-item') || '';
                    const price = item.getAttribute('data-price') || '0.00';

                    if (itemName.trim()) {
                        parts.push(`${(index + 1)}. ${itemName} → ${newItem} (₱${parseFloat(price).toFixed(2)})`);
                    }
                });
                if (parts.length > 0) {
                    partReplacementText = parts.join('\n');
                }
            }

            const receiptData = {
                customerName: customerName,
                dateIn: document.getElementById('dateIn').value || '-',
                dateOut: document.getElementById('dateOut').value || '-',
                status: 'Completed',
                type: type,
                brand: document.getElementById('brand').value || '-',
                model: document.getElementById('model').value || '-',
                serviceTypeName: serviceTypeName,
                description: description,
                actionTaken: actionTaken,
                partReplacement: partReplacementText,
                totalPrice: document.getElementById('totalPrice').value || '0.00'
            };

            sessionStorage.setItem('serviceData', JSON.stringify(receiptData));

            // Call audit logging endpoint
            fetch(`/services/${serviceId}/log-receipt`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to log service receipt');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('✅ Service receipt logged:', data);
                    window.location.href = '/service-receipt';
                })
                .catch(error => {
                    console.error('❌ Error logging service receipt:', error);
                    Swal.fire({
                        icon: 'warning',
                        title: 'Logging Warning',
                        text: 'Service receipt will be issued but logging may have failed',
                        confirmButtonColor: '#151F28'
                    }).then(() => {
                        window.location.href = '/service-receipt';
                    });
                });
        }

        async function handleArchiveService(e) {
            e.preventDefault();

            const serviceId = document.getElementById('serviceIdInput').value;
            if (!serviceId) {
                Swal.fire('Error', 'No service selected', 'error');
                return;
            }

            const confirm = await Swal.fire({
                icon: 'warning',
                title: 'Archive Service',
                text: 'Are you sure you want to archive this service?',
                showCancelButton: true,
                confirmButtonColor: '#151F28',
                cancelButtonColor: '#6c757d'
            });

            if (!confirm.isConfirmed) return;

            try {
                const response = await fetch(`/api/services/${serviceId}/archive`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Failed to archive service');
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Service archived successfully',
                    confirmButtonColor: '#151F28'
                }).then(() => {
                    clearServiceForm();
                    // Real-time update: Refresh services list via HTMX maintaining current filters
                    refreshServicesList();
                });

            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message,
                    confirmButtonColor: '#151F28'
                });
            }
        }

        // ============ UTILITY FUNCTIONS ============
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        console.log('Services page loaded with backend integration');
    </script>
</body>

</html>