<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://unpkg.com/html5-qrcode"></script>

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
            color: #e5e7eb;
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
    </style>
</head>

<body class="flex min-h-screen bg-gray-50">

    <div id="sidebar" class="sidebar w-64 border-r border-gray-200 p-4 lg:static lg:block">

        <!-- VANTECH -->
        <div class="image-container mb-6">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-32 mx-auto mb-6">
        </div>

        <nav class="space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="sidebar-item">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('POS') }}" class="sidebar-item">
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
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
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
        <nav class="navbar">
            <div class="flex items-center">
                <button id="menu-toggle" class="menu-toggle">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                @yield('btn')
                <h1 class="ml-4 text-xl font-semibold">@yield('name')</h1>
            </div>
            <div class="flex justify-center mt-8">
                <a href="{{ route('pos.items') }}"
                    class="flex items-center gap-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg hover:scale-105 hover:shadow-xl transition transform duration-200 ease-in-out focus:ring-4 focus:ring-green-300">
            
                    <!-- Cashier / POS icon (Shopping Cart) -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m13-9l2 9M9 21h6">
                        </path>
                    </svg>
            
                    <span class="text-lg">POINT OF SALE</span>
                </a>
            </div>
        </nav>

        <main class="content-area flex-1 p-4 lg:p-6">
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
                        window.location.href = '/login'; // Adjust to your login route
                    });
                }
            });
        });
    </script>

</body>

</html>