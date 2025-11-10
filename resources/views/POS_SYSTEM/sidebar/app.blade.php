<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('name', 'Default Title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<style>
    .scrollbar-hide {
        overflow-y: auto;
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        /* Firefox */
    }

    .scrollbar-hide ::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }
</style>

<body class="bg-gray-50 h-screen flex">

    <!-- Sidebar -->
    <div class="w-40 bg-white shadow-md h-full flex flex-col items-center p-5">

        <!-- Back Button -->
        <a href="{{ route('inventory') }}"
            class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </a>

        <ul class="navbar w-full space-y-4 mt-10 overflow-y-auto scrollbar-hide">
            <!-- All -->
            <li>
                <button
                    class="w-full flex flex-col items-center p-4 border-2 border-orange-400 rounded-lg shadow-md text-orange-500"
                    aria-current="true">
                    <!-- View Grid Icon (4 squares) -->
                    <svg class="w-7 h-7 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-sm font-semibold">All</span>
                </button>
            </li>

            <!-- CPU -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Microchip with pins (more CPU-like) -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">CPU</span>
                </button>
            </li>

            <!-- GPU -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Cube/3D icon (represents graphics/rendering) -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">GPU</span>
                </button>
            </li>

            <!-- RAM -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Circuit board pattern (RAM stick) -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        <circle cx="12" cy="12" r="2" stroke-width="2" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">RAM</span>
                </button>
            </li>

            <!-- STORAGE -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Hard Drive/SSD Icon -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 7v10c0 2 1.5 3 4 3h8c2.5 0 4-1 4-3V7c0-2-1.5-3-4-3H8C5.5 4 4 5 4 7z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 12H4M6 16h.01M10 16h.01" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">STORAGE</span>
                </button>
            </li>

            <!-- CPU COOLER -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Refresh/Fan Spinning Icon -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">CPU COOLER</span>
                </button>
            </li>

            <!-- PSU -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Battery/Power Icon -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v1m6-1v1m-8 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2zM7 12h10v5H7v-5z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">PSU</span>
                </button>
            </li>

            <!-- PC CASE -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Tower/Server Case Icon -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h8M8 11h8M8 15h8" />
                        <circle cx="9" cy="7" r="1" fill="currentColor" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">PC CASE</span>
                </button>
            </li>

            <!-- MONITOR -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Desktop Monitor with Stand -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">MONITOR</span>
                </button>
            </li>

            <!-- Laptops -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Laptop/Computer Icon -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Laptops</span>
                </button>
            </li>

            <!-- Peripherals -->
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <!-- Mouse/Cursor Icon (better for peripherals) -->
                    <svg class="w-7 h-7 mb-2 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">PERIPHERAL</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 p-10 overflow-auto">
        @yield('content_items')
    </main>
</body>

</html>