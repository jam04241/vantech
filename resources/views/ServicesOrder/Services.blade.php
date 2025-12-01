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
            loadAllServices(); // Load all services from backend
            setupEventListeners();
        });

        // ============ FETCH DATA FUNCTIONS ============
        function loadCustomers() {
            fetch('/api/customers')
                .then(response => response.json())
                .then(data => {
                    customersData = data;
                })
                .catch(error => console.error('Error loading customers:', error));
        }

        function loadServiceTypes() {
            fetch('/api/service-types')
                .then(response => response.json())
                .then(data => {
                    serviceTypesData = data;
                    populateServiceTypeDropdown(data);
                })
                .catch(error => console.error('Error loading service types:', error));
        }

        function loadBrands() {
            fetch('/api/brands')
                .then(response => response.json())
                .then(data => {
                    brandsData = data;
                })
                .catch(error => console.error('Error loading brands:', error));
        }

        function loadServiceItems() {
            fetch('/api/service-items')
                .then(response => response.json())
                .then(data => {
                    modelsData = data;
                })
                .catch(error => console.error('Error loading service items:', error));
        }

        // Load all services from backend (NEW)
        function loadAllServices(status = 'all') {
            const url = status === 'all'
                ? '/api/services'
                : `/api/services?status=${status}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    displayServices(data);
                })
                .catch(error => console.error('Error loading services:', error));
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

            // Load services for all active statuses
            Promise.all(activeStatuses.map(status =>
                fetch(`/api/services?status=${status}`).then(r => r.json())
            )).then(results => {
                // Merge all results
                const allServices = results.flat();
                // Remove duplicates by service ID
                const uniqueServices = Array.from(new Map(allServices.map(s => [s.id, s])).values());
                displayServices(uniqueServices);
            }).catch(error => console.error('Error loading filtered services:', error));
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

                const card = document.createElement('div');
                card.setAttribute('data-service-id', service.id);
                card.className = 'border border-gray-200 rounded-lg p-4 hover:shadow-md transition hover:border-[#151F28] cursor-pointer';
                card.innerHTML = `
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">#${index + 1} - ${service.customer?.first_name || 'N/A'}</h3>
                            <p class="text-xs text-gray-600 mt-0.5"><i class="fas fa-user mr-1"></i>${service.customer?.first_name || '-'} ${service.customer?.last_name || ''}</p>
                        </div>
                        <span class="text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap ${statusColors[service.status] || 'bg-gray-100'}">
                            <i class="fas fa-info-circle mr-1"></i>${service.status}
                        </span>
                    </div>
                    <div class="text-xs space-y-1 mb-2">
                        <p class="text-gray-600"><span class="font-semibold">Service Type:</span> ${service.serviceType?.name || '-'}</p>
                        <p class="text-gray-600"><span class="font-semibold">Type of Item:</span> ${service.type || '-'}</p>
                        <p class="text-gray-600"><span class="font-semibold">Brand:</span> ${service.brand || '-'}</p>
                        <p class="text-gray-600"><span class="font-semibold">Model:</span> ${service.model || '-'}</p>
                        <p class="text-gray-600"><span class="font-semibold">Price:</span> ₱${parseFloat(service.total_price || 0).toFixed(2)}</p>
                    </div>
                    <p class="text-xs text-gray-700 border-t pt-2 line-clamp-2">${service.description || '-'}</p>
                `;
                servicesContainer.appendChild(card);
            });
        }

        // ============ EVENT LISTENERS SETUP ============
        function setupEventListeners() {
            // Service card click handler
            document.addEventListener('click', function (e) {
                const card = e.target.closest('[data-service-id]');
                if (card && !e.target.closest('button')) {
                    const serviceId = card.dataset.serviceId;
                    toggleServiceSelection(serviceId, card);
                }
            });

            // Filter buttons
            document.querySelectorAll('[data-filter]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const filter = this.dataset.filter;

                    if (filter === 'all') {
                        // "All" button: deselect ALL other buttons and ensure "All" is selected
                        document.querySelectorAll('[data-filter]:not([data-filter="all"])').forEach(b => {
                            b.classList.remove('bg-[#151F28]', 'text-white');
                            b.classList.add('bg-gray-200', 'text-gray-800');
                        });
                        // Ensure "All" button is properly activated
                        this.classList.remove('bg-gray-200', 'text-gray-800');
                        this.classList.add('bg-[#151F28]', 'text-white');
                        loadAllServices('all');
                    } else {
                        // Other buttons: toggle individual status
                        const isCurrentlyActive = this.classList.contains('bg-[#151F28]');

                        if (isCurrentlyActive) {
                            // Deactivating this button
                            this.classList.remove('bg-[#151F28]', 'text-white');
                            this.classList.add('bg-gray-200', 'text-gray-800');
                        } else {
                            // Activating this button
                            this.classList.add('bg-[#151F28]', 'text-white');
                            this.classList.remove('bg-gray-200', 'text-gray-800');
                        }

                        // Check if ANY status button is active
                        const allBtn = document.querySelector('[data-filter="all"]');
                        const anyStatusActive = document.querySelectorAll('[data-filter]:not([data-filter="all"]).bg-[#151F28]').length > 0;

                        if (anyStatusActive) {
                            // At least one status button is active: deselect "All"
                            allBtn.classList.remove('bg-[#151F28]', 'text-white');
                            allBtn.classList.add('bg-gray-200', 'text-gray-800');
                            // Load combined filters
                            loadFilteredServices();
                        } else {
                            // No status buttons are active: activate "All"
                            allBtn.classList.remove('bg-gray-200', 'text-gray-800');
                            allBtn.classList.add('bg-[#151F28]', 'text-white');
                            loadAllServices('all');
                        }
                    }
                });
            });

            // Search bar
            const searchInput = document.getElementById('searchServices');
            if (searchInput) {
                searchInput.addEventListener('input', debounce(function (e) {
                    const search = e.target.value.trim();
                    if (search.length > 0) {
                        fetch(`/api/services?search=${encodeURIComponent(search)}`)
                            .then(response => response.json())
                            .then(data => displayServices(data))
                            .catch(error => console.error('Search error:', error));
                    } else {
                        loadAllServices('all');
                    }
                }, 300));
            }

            // Status change
            document.getElementById('status').addEventListener('change', function (e) {
                toggleReceiptButtons(e.target.value);
            });

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
                    .then(response => response.json())
                    .then(data => {
                        selectedServiceData = data;
                        populateServiceForm(data);
                    })
                    .catch(error => {
                        console.error('Error fetching service:', error);
                        Swal.fire('Error', 'Failed to load service details', 'error');
                    });
            }
        }

        // ============ FORM POPULATION ============
        function populateServiceForm(data) {
            document.getElementById('serviceIdInput').value = data.id;
            document.getElementById('customerName').value = `${data.customer?.first_name || ''} ${data.customer?.last_name || ''}`;
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

            // Load replacements
            displayReplacements(data.replacements || []);

            // Toggle buttons based on status
            toggleReceiptButtons(data.status);
        }

        function clearServiceForm() {
            document.getElementById('serviceForm').reset();
            document.getElementById('serviceIdInput').value = '';
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
                    loadAllServices('all');
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

        // Save all replacements to database
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
                is_disabled: 0  // Enable it immediately
            };

            fetch('/api/service-replacements', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(replacementData)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to add replacement');
                    }
                    return response.json();
                })
                .then(data => {
                    // Add to DOM with database ID
                    addReplacementItem(itemName, oldCondition, newItem, newPrice, warranty, data.id);

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
                    }).then(() => {
                        // Optional: refresh if needed
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
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

                if (dbReplacementId) {
                    // Has database ID - soft delete by setting is_disabled = 1
                    try {
                        const updatePayload = {
                            service_id: parseInt(serviceId),
                            item_name: itemName,
                            old_item_condition: oldCondition,
                            new_item: newItem,
                            new_item_price: parseFloat(newPrice),
                            new_item_warranty: warranty || null,
                            is_disabled: 1  // Disable it (soft delete)
                        };

                        const response = await fetch(`/api/service-replacements/${dbReplacementId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN
                            },
                            body: JSON.stringify(updatePayload)
                        });

                        if (response.ok) {
                            replacementRow.remove();
                            replacementCount--;
                            renumberReplacements();

                            Swal.fire({
                                icon: 'success',
                                title: 'Removed',
                                text: 'Part replacement removed successfully',
                                confirmButtonColor: '#151F28',
                                timer: 1000
                            });
                        } else {
                            const error = await response.json();
                            throw new Error(error.message || 'Failed to remove replacement');
                        }
                    } catch (error) {
                        console.error('Error:', error);
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
                    replacement.new_item_warranty
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

            if (!customerName || !serviceType || !type || !description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields before viewing acknowledgement',
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
            window.location.href = '/acknowledgement-receipt';
        }

        async function handleServiceReceipt(e) {
            e.preventDefault();

            const customerName = document.getElementById('customerName').value.trim();
            const serviceType = document.getElementById('serviceType').value;
            const type = document.getElementById('type').value.trim();
            const description = document.getElementById('description').value.trim();

            if (!customerName || !serviceType || !type || !description) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please fill in all required fields before viewing service receipt',
                    confirmButtonColor: '#151F28'
                });
                return;
            }

            try {
                // First, update service status to Completed
                const serviceId = document.getElementById('serviceIdInput').value;
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
            window.location.href = '/service-receipt';
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
                    loadAllServices('all');
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