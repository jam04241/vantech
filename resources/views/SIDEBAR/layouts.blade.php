<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ... other head content ... -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    {{-- Tailwind & Vite --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

    <style>
        .sidebar {
            transition: all 0.3s ease;
            background-color: #151F28;
            /* Custom dark background color */
        }

        /* For mobile screens */
        @media (max-width: 1024px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                height: 100vh;
                width: 250px;
                background-color: #151F28;
                /* Ensure consistent background on mobile */
                z-index: 50;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .sidebar.open {
                left: 0;
            }

            .overlay {
                display: block;
                position: fixed;
                inset: 0;
                background-color: rgba(0, 0, 0, 0.4);
                z-index: 40;
            }

            .overlay.hidden {
                display: none;
            }

            .content-area {
                margin-left: 0 !important;
            }
        }

        /* Custom styles for sidebar items - adjusted for dark background */
        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #e5e7eb;
            /* Light text for dark background */
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background-color 0.2s, color 0.2s;
        }

        .sidebar-item:hover {
            background-color: #374151;
            /* Darker hover for contrast */
            color: #ffffff;
        }

        .sidebar-item.active {
            background-color: #1d4ed8;
            /* Blue accent for active state */
            color: #ffffff;
        }

        .dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 0.75rem 1rem;
            color: #e5e7eb;
            /* Light text */
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background-color 0.2s, color 0.2s;
            cursor: pointer;
        }

        .dropdown-toggle:hover {
            background-color: #374151;
            color: #ffffff;
        }

        .dropdown-content {
            margin-left: 1rem;
            margin-top: 0.25rem;
        }

        .dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #d1d5db;
            /* Slightly lighter for sub-items */
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background-color 0.2s, color 0.2s;
        }

        .dropdown-item:hover {
            background-color: #4b5563;
            color: #ffffff;
        }

        .icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
            /* Icon color to match text */
        }

        /* Navbar styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            width: 100%;
            box-sizing: border-box;
        }

        .menu-toggle {
            display: none;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }

        .menu-toggle:hover {
            background-color: #f3f4f6;
        }

        @media (max-width: 1024px) {
            .menu-toggle {
                display: block;
            }
        }

        /* Prevent horizontal scrolling */
        body {
            overflow-x: hidden;
        }

        /* Topbar specific styles */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 0.5rem 1rem;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            box-sizing: border-box;
        }

        .topbar-nav {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding: 0.25rem 0;
        }

        .topbar-nav::-webkit-scrollbar {
            display: none;
        }

        .topbar-nav {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .topbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-left: 1rem;
            white-space: nowrap;
        }

        @media (max-width: 768px) {
            .topbar {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .topbar-nav {
                width: 100%;
                justify-content: flex-start;
            }

            .topbar-title {
                margin-left: 0;
            }
        }
    </style>
</head>

<body class="flex min-h-screen bg-gray-50">

    <div id="sidebar" class="sidebar w-64 border-r border-gray-200 p-4 lg:static lg:block">

        <!-- VANTECH -->
        <div class="image-container mb-6">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-32 mx-auto mb-6">
        </div>

        <nav class="space-y-4">
            <!-- Dashboard - Available to all authenticated users -->
            <a href="{{ route('dashboard') }}" class="sidebar-item">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l9-9 9 9M4 10v10h16V10" />
                </svg>
                Dashboard
            </a>

            <!-- Sales - Admin Only -->
            @if(Auth::user() && Auth::user()->role === 'admin')
                <button onclick="checkAdminAccess('{{ route('Sales') }}')" class="sidebar-item w-full text-left">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Sales
                </button>
            @elseif(Auth::user() && Auth::user()->role === 'staff')
                <button onclick="showAdminVerificationModal('{{ route('Sales') }}')"
                    class="sidebar-item w-full text-left relative group">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Sales
                    <span
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs bg-red-500 text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Admin
                        Only</span>
                </button>
            @endif

            <!-- Inventory Dropdown - Available to all authenticated users -->
            <details class="group">
                <summary class="dropdown-toggle">
                    <div class="flex items-center">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h13v10H3zM16 10h3l2 3v4h-5zM5 17a2 2 0 11-.001 3.999A2 2 0 015 17zm11 0a2 2 0 11-.001 3.999A2 2 0 0116 17z">
                            </path>
                        </svg>
                        Inventory
                    </div>
                    <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <div class="dropdown-content space-y-1">
                    <a href="{{ route('inventory')}}" class="dropdown-item">Inventory Manage</a>
                    <a href="{{ route('inventory.list') }}" class="dropdown-item">Inventory List</a>
                    <a href="" class="dropdown-item">Inventory Archive</a>
                </div>
            </details>

            <!-- Suppliers Dropdown - Admin Only -->
            @if(Auth::user() && Auth::user()->role === 'admin')
                <details class="group">
                    <summary class="dropdown-toggle">
                        <div class="flex items-center">
                            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            Suppliers
                        </div>
                        <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </summary>
                    <div class="dropdown-content space-y-1">
                        <a href="{{ route('suppliers') }}" class="dropdown-item">Supplier Manage</a>
                        <a href="{{ route('suppliers.list') }}" class="dropdown-item">Purchase Orders</a>
                    </div>
                </details>
            @elseif(Auth::user() && Auth::user()->role === 'staff')
                <button onclick="showAdminVerificationModal('{{ route('suppliers') }}')"
                    class="dropdown-toggle w-full relative group">
                    <div class="flex items-center">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        Suppliers
                    </div>
                    <span
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs bg-red-500 text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Admin
                        Only</span>
                </button>
            @endif

            <!-- Audit Logs - Admin Only -->
            @if(Auth::user() && Auth::user()->role === 'admin')
                <a href="{{ route('audit.logs') }}" class="sidebar-item">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 2h6l4 4v6m-4 10H7a2 2 0 01-2-2V4a2 2 0 012-2h2m5 14a4 4 0 100-8 4 4 0 000 8zm5 5l-3.5-3.5">
                        </path>
                    </svg>
                    Audit Logs
                </a>
            @elseif(Auth::user() && Auth::user()->role === 'staff')
                <button onclick="showAdminVerificationModal('{{ route('audit.logs') }}')"
                    class="sidebar-item w-full text-left relative group">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 2h6l4 4v6m-4 10H7a2 2 0 01-2-2V4a2 2 0 012-2h2m5 14a4 4 0 100-8 4 4 0 000 8zm5 5l-3.5-3.5">
                        </path>
                    </svg>
                    Audit Logs
                    <span
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs bg-red-500 text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Admin
                        Only</span>
                </button>
            @endif

            <!-- Staff Management - Admin Only -->
            @if(Auth::user() && Auth::user()->role === 'admin')
                <a href="{{ route('staff.record') }}" class="sidebar-item">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A7 7 0 0112 14a7 7 0 016.879 3.804M12 12a5 5 0 100-10 5 5 0 000 10z" />
                    </svg>
                    Staff
                </a>
            @elseif(Auth::user() && Auth::user()->role === 'staff')
                <button onclick="showAdminVerificationModal('{{ route('staff.record') }}')"
                    class="sidebar-item w-full text-left relative group">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A7 7 0 0112 14a7 7 0 016.879 3.804M12 12a5 5 0 100-10 5 5 0 000 10z" />
                    </svg>
                    Staff
                    <span
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs bg-red-500 text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Admin
                        Only</span>
                </button>
            @endif

            <!-- Customer Manage - Admin Only -->
            @if(Auth::user() && Auth::user()->role === 'admin')
                <a href="{{ route('customer.records') }}" class="sidebar-item">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 2h6l4 4v14a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 
                                                                                       012-2h2zm3 8a3 3 0 110 6 3 3 0 010-6zm0 6c2.21 0 4 
                                                                                       1.79 4 4H8c0-2.21 1.79-4 4-4z" />
                    </svg>
                    Customer Records
                </a>
            @elseif(Auth::user() && Auth::user()->role === 'staff')
                <button onclick="showAdminVerificationModal('{{ route('customer.records') }}')"
                    class="sidebar-item w-full text-left relative group">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 2h6l4 4v14a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 
                                                                                       012-2h2zm3 8a3 3 0 110 6 3 3 0 010-6zm0 6c2.21 0 4 
                                                                                       1.79 4 4H8c0-2.21 1.79-4 4-4z" />
                    </svg>
                    Customer Records
                    <span
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs bg-red-500 text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Admin
                        Only</span>
                </button>
            @endif

            <!-- Logout -->
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button id="logout-btn" class="sidebar-item w-full text-left">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Logout
            </button>
        </nav>
    </div>

    <div id="overlay" class="overlay hidden"></div>
    <div class="flex-1 flex flex-col">
        <div class="topbar">
            <div class="flex items-center">
                <button id="menu-toggle" class="menu-toggle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                @yield('btn')
                <h1 class="topbar-title">@yield('name')</h1>
            </div>
            {{-- TOPBAR NAVIGATION --}}
            <div class="topbar-nav">
                <!-- Services Button -->
                <div class="relative group">
                    <a href="{{ route('services.dashboard') }}"
                        class="sidebar-item flex items-center justify-center w-8 h-8 bg-[#46647F] hover:bg-[#3a5469] text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:scale-110 p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                    </a>
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10"
                        title="Services">
                        Services
                    </div>
                </div>

                <!-- POS Button -->
                <div class="relative group">
                    <a href="{{ route("pos.itemlist") }}"
                        class="sidebar-item flex items-center justify-center w-8 h-8 bg-[#46647F] hover:bg-[#3a5469] text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:scale-110 p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m13-9l2 9M9 21h6">
                            </path>
                        </svg>
                    </a>
                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10"
                        title="Point of Sale">
                        Point of Sale
                    </div>
                </div>
            </div>

        </div>

        <main class="content-area flex-1 p-4 lg:p-6 overflow-auto">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menu-toggle');
        const logoutBtn = document.getElementById('logout-btn');

        // Toggle sidebar open/close
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        });

        // Close sidebar when clicking outside
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
        });

        // Admin Verification Modal
        let intendedUrl = null;

        function showAdminVerificationModal(url) {
            intendedUrl = url;
            Swal.fire({
                title: 'Admin Verification Required',
                text: 'This page requires admin verification. Please enter an admin password to continue.',
                icon: 'warning',
                input: 'password',
                inputPlaceholder: 'Enter admin password',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Verify',
                cancelButtonText: 'Cancel',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit verification form with CSRF token and intended URL
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("verify.admin.password") }}';

                    // Get CSRF token from meta tag
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

                    form.innerHTML = `
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="admin_password" value="${result.value}">
                        <input type="hidden" name="intended_url" value="${intendedUrl}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function checkAdminAccess(url) {
            window.location.href = url;
        }

        // Logout with SweetAlert confirmation
        logoutBtn.addEventListener('click', () => {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You will be logged out of your account.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    </script>

</body>

</html>