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

            {{-- STAFF FOR CREATE AND MANAGE ACCOUNT --}}
            <a href="{{ route('staff') }}" class="sidebar-item">
                <svg class="icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M10.8 19.916q1.106-1.949 2.789-2.682Q15.27 16.5 16.5 16.5q.517 0 .98.071q.464.071.912.202q.658-.854 1.133-2.098T20 12q0-3.35-2.325-5.675T12 4T6.325 6.325T4 12q0 1.298.384 2.448q.383 1.15 1.035 2.102q.948-.558 1.904-.804t2.004-.246q.627 0 1.22.099q.594.099.972.209q-.286.184-.52.373q-.233.188-.472.427q-.185-.05-.532-.08q-.347-.028-.668-.028q-.858 0-1.703.214q-.845.213-1.57.64q.935 1.05 2.162 1.693q1.228.643 2.584.868M12.003 21q-1.866 0-3.51-.708q-1.643-.709-2.859-1.924t-1.925-2.856T3 12.003t.709-3.51Q4.417 6.85 5.63 5.634t2.857-1.925T11.997 3t3.51.709q1.643.708 2.859 1.922t1.925 2.857t.709 3.509t-.708 3.51t-1.924 2.859t-2.856 1.925t-3.509.709M9.5 13q-1.258 0-2.129-.871T6.5 10t.871-2.129T9.5 7t2.129.871T12.5 10t-.871 2.129T9.5 13m0-1q.817 0 1.409-.591q.591-.592.591-1.409t-.591-1.409Q10.317 8 9.5 8t-1.409.591Q7.5 9.183 7.5 10t.591 1.409Q8.683 12 9.5 12m7 2.385q-1.001 0-1.693-.692T14.116 12t.691-1.693t1.693-.691t1.693.691t.691 1.693t-.691 1.693t-1.693.692M12 12" />
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
                <!-- Add Customer Button -->
                <div class="relative group">
                    <a href=""
                        class="sidebar-item flex items-center justify-center w-8 h-8 bg-[#46647F] hover:bg-[#3a5469] text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:scale-110 p-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </a>
                    <div
                        class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
                        Add Customer
                    </div>
                </div>

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

                <!-- Upcoming Feature Button -->
                <div class="relative group">
                    <a href="{{ route("inventory.brandcategory") }}"
                        class="sidebar-item flex items-center justify-center w-8 h-8 bg-[#46647F] hover:bg-[#3a5469] text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:scale-110 p-2">
                        <svg class="w-4 h-4 text-white-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h4l2 2h12v11H3z" />
                        </svg>
                        {{-- <!-- ICON SAVE -->
                        <x-far-save /> --}}
                    </a>
                    <div
                        class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-2 py-1 bg-gray-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap z-10">
                        Coming Soon
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