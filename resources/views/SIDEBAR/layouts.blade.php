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

    {{-- HTMX --}}
    <script src="https://unpkg.com/htmx.org"></script>
    <style>
        .sidebar {
            transition: all 0.3s ease;
            background-color: #151F28;
            /* Custom dark background color */
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 16rem;
            z-index: 50;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* For mobile screens */
        @media (max-width: 1024px) {
            .sidebar {
                left: -100%;
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
            margin: 0 1rem;
            color: #e5e7eb;
            /* Light text for dark background */
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background-color 0.2s, color 0.2s;
            width: calc(100% - 2rem);
            min-height: 2.75rem;
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
            padding: 0.75rem 1rem;
            margin: 0 1rem;
            color: #e5e7eb;
            /* Light text */
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background-color 0.2s, color 0.2s;
            cursor: pointer;
            width: calc(100% - 2rem);
            min-height: 2.75rem;
            background: none;
            border: none;
        }

        .dropdown-toggle:hover {
            background-color: #374151;
            color: #ffffff;
        }

        .dropdown-content {
            margin-left: 0;
            margin-top: 0.25rem;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0 1rem;
            color: #d1d5db;
            /* Slightly lighter for sub-items */
            text-decoration: none;
            border-radius: 0.375rem;
            transition: background-color 0.2s, color 0.2s;
            width: calc(100% - 2rem);
            min-height: 2.5rem;
        }

        .dropdown-item:hover {
            background-color: #4b5563;
            color: #ffffff;
        }

        /* Sidebar nav container */
        .sidebar nav {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0;
        }

        .sidebar nav::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar nav::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {

            .sidebar-item,
            .dropdown-toggle,
            .dropdown-item {
                padding: 0.65rem 0.75rem;
                min-height: 2.5rem;
            }

            .sidebar-item,
            .dropdown-toggle {
                margin: 0 0.25rem;
                width: calc(100% - 0.5rem);
            }

            .dropdown-item {
                margin: 0 0.25rem;
                width: calc(100% - 0.5rem);
            }

            .icon {
                width: 1.1rem;
                height: 1.1rem;
                margin-right: 0.5rem;
            }
        }

        @media (max-width: 768px) {

            .sidebar-item,
            .dropdown-toggle,
            .dropdown-item {
                padding: 0.65rem 0.75rem;
                min-height: 2.4rem;
                font-size: 0.95rem;
            }

            .icon {
                width: 1rem;
                height: 1rem;
                margin-right: 0.5rem;
            }
        }

        .icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.75rem;
            /* Icon color to match text */
        }

        /* Welcome Card Styles */
        .welcome-card {
            background-color: #374151;
            border-radius: 0.5rem;
            padding: 1rem;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            align-items: center;
            flex-shrink: 0;
            margin: 1rem;
            margin-top: auto;
        }

        .welcome-card-icon {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            flex-shrink: 0;
        }

        .welcome-card-icon svg {
            width: 2rem;
            height: 2rem;
        }

        .welcome-card-content {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            min-width: 0;
        }

        .welcome-card-name {
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .welcome-card-role {
            font-size: 0.8rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Navbar styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.5rem;
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
            overflow-y: auto;
            margin: 0;
            padding: 0;
        }

        /* Hide scrollbar while allowing scroll */
        body::-webkit-scrollbar {
            display: none;
        }

        body {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Topbar specific styles */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 16rem;
            right: 0;
            padding: 0.75rem 1.5rem;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            box-sizing: border-box;
            z-index: 40;
            height: 4rem;
        }

        @media (max-width: 1024px) {
            .topbar {
                left: 0;
            }
        }

        @media (max-width: 768px) {
            .topbar {
                flex-direction: row;
                align-items: center;
                gap: 0.5rem;
                height: 4rem;
                padding: 0.75rem 1rem;
            }

            .topbar-nav {
                width: auto;
                justify-content: flex-end;
                flex-wrap: nowrap;
                flex-shrink: 0;
            }

            .topbar-title {
                margin-left: 0.5rem;
            }
        }

        .topbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-left: 1rem;
            white-space: nowrap;
        }

        .topbar-nav {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: nowrap;
            overflow-x: auto;
            padding: 0.25rem 0;
            min-width: 0;
            flex-shrink: 0;
        }

        .topbar-nav::-webkit-scrollbar {
            display: none;
        }

        .topbar-nav {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Content area adjustment */
        .content-area {
            margin-left: 16rem;
            margin-top: 4rem;
            overflow-y: auto;
        }

        /* Hide scrollbar for content area */
        .content-area::-webkit-scrollbar {
            display: none;
        }

        .content-area {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @media (max-width: 1024px) {
            .content-area {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .topbar {
                gap: 0.5rem;
            }

            .topbar-nav {
                flex-wrap: nowrap;
                gap: 0.5rem;
            }

            .topbar-nav>div {
                flex-shrink: 0;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50">

    <div id="sidebar" class="sidebar border-r border-gray-200">

        <!-- VANTECH -->
        <div class="image-container p-4 flex-shrink-0">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" class="w-32 mx-auto">
        </div>

        <nav class="space-y-4 px-2">
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
                <button onclick="checkAdminAccess('{{ route('Sales') }}')" class="sidebar-item">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Sales
                </button>
            @elseif(Auth::user() && Auth::user()->role === 'staff')
                <button onclick="showAdminVerificationModal('{{ route('Sales') }}')" class="sidebar-item relative group">
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
                    <a href="{{ route('inventory')}}" class="dropdown-item">Inventory
                        Manage</a>
                    <a href="{{ route('inventory.list') }}" class="dropdown-item">Inventory List</a>
                    <a href="{{ route('inventory.stock-out') }}" class="dropdown-item">Stock-Out</a>
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
                        <a href="{{ route('suppliers') }}" class="dropdown-item">Supplier
                            Manage</a>
                        <a href="{{ route('suppliers.list') }}" class="dropdown-item">Purchase Orders</a>
                    </div>
                </details>
            @elseif(Auth::user() && Auth::user()->role === 'staff')
                <button onclick="showAdminVerificationModal('{{ route('suppliers') }}')"
                    class="sidebar-item relative group">
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
                    class="sidebar-item relative group">
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


            <a href="{{ route('customer.records') }}" class="sidebar-item">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 2h6l4 4v14a2 2 0 01-2 2H7a2 2 0 01-2-2V4a2 2 0 012-2h2zm3 8a3 3 0 110 6 3 3 0 010-6zm0 6c2.21 0 4 1.79 4 4H8c0-2.21 1.79-4 4-4z" />
                </svg>
                Customer Records
            </a>

            <!-- Service Records - Admin Only -->
            @if(Auth::user() && Auth::user()->role === 'admin')
                <a href="{{ route('service.records') }}" class="sidebar-item">
                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    Service Records
                </a>
            @elseif(Auth::user() && Auth::user()->role === 'staff')
                <button onclick="showAdminVerificationModal('{{ route('service.records') }}')"
                    class="sidebar-item relative group">
                    <svg class="icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    Service Records
                    <span
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 text-xs bg-red-500 text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">Admin
                        Only</span>
                </button>
            @endif

            <!-- Logout -->
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <button id="logout-btn" class="sidebar-item">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                    </path>
                </svg>
                Logout
            </button>
        </nav>
        <!-- Welcome Card Footer -->
        @if(Auth::check())
            <div class="welcome-card">
                <div class="welcome-card-icon">
                    @if(Auth::user()->role === 'admin')
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    @else
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    @endif
                </div>
                <div class="welcome-card-content">
                    <div class="welcome-card-name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                    <div class="welcome-card-role">{{ Auth::user()->role }}</div>
                </div>
            </div>
        @endif
    </div>

    <div id="overlay" class="overlay hidden"></div>
    <div class="topbar">
        <div class="flex items-center">
            <button id="menu-toggle" class="menu-toggle">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
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
                    class="flex items-center justify-center w-10 h-10 bg-[#46647F] hover:bg-[#3a5469] text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
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
                    class="flex items-center justify-center w-10 h-10 bg-[#46647F] hover:bg-[#3a5469] text-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 ease-in-out transform hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <main class="content-area p-4 lg:p-6 overflow-auto">
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