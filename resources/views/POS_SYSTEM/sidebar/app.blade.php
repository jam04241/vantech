<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('name', 'Default Title')</title>
    {{-- Tailwind & Vite --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')    
    
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

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

        <ul class="w-full space-y-4 mt-10">
            <li>
                <button
                    class="w-full flex flex-col items-center p-4 border-2 border-orange-400 rounded-lg shadow-md text-orange-500"
                    aria-current="true">
                    <img src="{{ asset('images/all.png') }}" alt="All" class="w-7 h-7 mb-2" />
                    <span class="text-sm font-semibold">All</span>
                </button>
            </li>
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <img src="{{ asset('images/headset.png') }}" alt="Headset" class="w-7 h-7 mb-2" />
                    <span class="text-sm font-medium text-gray-700">Headset</span>
                </button>
            </li>
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <img src="{{ asset('images/cpu.png') }}" alt="CPU" class="w-7 h-7 mb-2" />
                    <span class="text-sm font-medium text-gray-700">CPU</span>
                </button>
            </li>
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <img src="{{ asset('images/pc_case.jpg') }}" alt="PC CASE" class="w-7 h-7 mb-2" />
                    <span class="text-sm font-medium text-gray-700">PC CASE</span>
                </button>
            </li>
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <img src="{{ asset('images/ram.png') }}" alt="RAM" class="w-7 h-7 mb-2" />
                    <span class="text-sm font-medium text-gray-700">RAM</span>
                </button>
            </li>
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <img src="{{ asset('images/laptop.png') }}" alt="Laptops" class="w-7 h-7 mb-2" />
                    <span class="text-sm font-medium text-gray-700">Laptops</span>
                </button>
            </li>
            <li>
                <button class="w-full flex flex-col items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md">
                    <img src="{{ asset('images/anything.png') }}" alt="Peripherals" class="w-7 h-7 mb-2" />
                    <span class="text-sm font-medium text-gray-700">Peripherals</span>
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