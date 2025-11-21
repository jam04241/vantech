<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="sidebar-item">
<svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h16V10" />
</svg>

                Dashboard
            </a>

            <a href="{{ route('Sales') }}" class="sidebar-item">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Sales
            </a>

            <!-- Inventory Dropdown -->
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
                </div>
            </details>

            <!-- Suppliers Dropdown -->
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

            {{-- STAFF FOR CREATE AND MANAGE ACCOUNT --}}
            <a href="{{ route('staff') }}" class="sidebar-item">
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5.121 17.804A7 7 0 0112 14a7 7 0 016.879 3.804M12 12a5 5 0 100-10 5 5 0 000 10z" />
            </svg>

                Staff
            </a>

            <!-- Logout -->
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
                <!-- POS Button -->
                <div class="relative group">
                    <a href="{{ route("pos.brands") }}"
                        class="sidebar-item flex items-center justify-center w-8 h-8 bg-[#46647F] hover:bg-[#3a5469] text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:scale-110 p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m13-9l2 9M9 21h6">
                            </path>
                        </svg>
                    </a>
                    <div
                        class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
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
                    // Assuming a logout route or form submission
                    // Replace with actual logout logic, e.g., window.location.href = '/logout';
                    // Or submit a form if using CSRF
                    // For demo, just show success and redirect
                    Swal.fire(
                        'Logged out!',
                        'You have been successfully logged out.',
                        'success'
                    ).then(() => {
                        window.location.href = '/LOGIN_FORM'; // Adjust to your login route
                    });
                }
            });
        });
    </script>

</body>

</html>