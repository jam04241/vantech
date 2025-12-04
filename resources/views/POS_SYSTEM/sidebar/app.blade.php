<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('name', 'Default Title')</title>

    {{-- Tailwind & Vite --}}
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    {{-- HTMX --}}
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

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
        <button onclick="window.location='{{ route('inventory') }}'"
            class="inline-flex items-center gap-2 bg-[#2F3B49] text-white px-4 py-2 rounded-lg shadow hover:bg-[#3B4A5A] focus:ring-2 focus:ring-[#3B4A5A] transition duration-150 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </button>

        <ul class="navbar w-full space-y-4 mt-10 overflow-y-auto scrollbar-hide">
            <li value="ALL">
                <button type="button" onclick="clearAllCategories()"
                    class="category-btn w-full flex flex-col items-center p-4 border-2 border-[#3B4A5A] rounded-lg shadow-md text-white bg-[#3B4A5A] hover:bg-[#3B4A5A] hover:text-white transition-all duration-150 ease-in-out"
                    aria-current="{{ request('category') == '' ? 'true' : 'false' }}">
                    <!-- ALL Icon (Grid 2x2) -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="text-sm font-semibold"> ALL </span>
                </button>
            </li>
            @isset($categories)
                @if($categories->count() > 0)
                    @foreach ($categories as $category)
                        @if(!empty($category->category_name))
                            @php
                                $categoryName = strtoupper(trim($category->category_name));
                                $icon = '';

                                // Determine icon based on category name
                                if ($categoryName === 'CPU') {
                                    $icon = '<i class="fas fa-microchip text-2xl mb-2"></i>';
                                } elseif ($categoryName === 'MOBO' || $categoryName === 'MOTHERBOARD') {
                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24"
                                                                                                    stroke="currentColor">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                                                                                </svg>';
                                } elseif ($categoryName === 'GPU' || $categoryName === 'GRAPHICS CARD') {
                                    $icon = '<i class="bi bi-gpu-card text-2xl mb-2"></i>';
                                } elseif ($categoryName === 'RAM' || $categoryName === 'MEMORY') {
                                    $icon = '<i class="fas fa-memory text-2xl mb-2"></i>';
                                } elseif ($categoryName === 'CPU COOLER' || $categoryName === 'COOLER') {
                                    $icon = '<i class="fas fa-fan text-2xl mb-2"></i>';
                                } elseif ($categoryName === 'MONITOR' || $categoryName === 'DISPLAY') {
                                    $icon = '<i class="fas fa-desktop text-2xl mb-2"></i>';
                                } elseif ($categoryName === 'PC CASE' || $categoryName === 'CASE') {
                                    $icon = '<i class="fas fa-server text-2xl mb-2"></i>';
                                } elseif ($categoryName === 'STORAGE' || $categoryName === 'SSD' || $categoryName === 'HDD') {
                                    $icon = '<i class="fas fa-hdd text-2xl mb-2"></i>';
                                } elseif ($categoryName === 'PSU' || $categoryName === 'POWER SUPPLY') {
                                    $icon = '<i class="fas fa-plug text-2xl mb-2"></i>';
                                } elseif (str_contains($categoryName, 'PC BUILD')) {
                                    $icon = '<i class="fa-solid fa-computer text-2xl mb-2"></i>';
                                } elseif (str_contains($categoryName, 'PERIPHERAL')) {
                                    $icon = '<i class="fas fa-keyboard text-2xl mb-2"></i>';
                                } else {
                                    // Others/Default icon
                                    $icon = '<i class="fas fa-keyboard text-2xl mb-2"></i>';
                                }
                            @endphp

                            <li value="{{ $category->id }}">
                                <button type="button" data-category-id="{{ $category->id }}"
                                    onclick="toggleCategory({{ $category->id }}, this)"
                                    class="category-btn w-full flex flex-col items-center p-4 border-2 border-[#3B4A5A] rounded-lg shadow-md text-[#3B4A5A] bg-white hover:bg-[#3B4A5A] hover:text-white transition-all duration-150 ease-in-out {{ request('category') == $category->id ? 'bg-[#3B4A5A] text-white' : '' }}"
                                    aria-current="{{ request('category') == $category->id ? 'true' : 'false' }}">
                                    {!! $icon !!}
                                    <span class="text-sm font-semibold">{{ $category->category_name }}</span>
                                </button>
                            </li>
                        @endif
                    @endforeach
                @else
                    <li class="w-full p-4 text-center">
                        <p class="text-sm text-gray-500">No categories available</p>
                    </li>
                @endif
            @else
                <li class="w-full p-4 text-center">
                    <p class="text-sm text-gray-500">Categories not loaded</p>
                </li>
            @endisset
        </ul>


    </div>

    <!-- Main Content Area -->
    <main class="flex-1 p-10 overflow-auto">
        @yield('content_items')
    </main>

    <script>
        // Multi-category selection with HTMX
        let selectedCategories = [];

        // Initialize with any pre-selected category from server
        document.addEventListener('DOMContentLoaded', function () {
            const currentCategory = '{{ request("category") }}';
            if (currentCategory) {
                selectedCategories = [currentCategory];
            }
            console.log('Category sidebar loaded with multi-selection functionality');
        });

        // Toggle individual category
        function toggleCategory(categoryId, buttonElement) {
            const index = selectedCategories.indexOf(categoryId.toString());

            if (index > -1) {
                // Remove category
                selectedCategories.splice(index, 1);
                buttonElement.classList.remove('bg-[#3B4A5A]', 'text-white');
                buttonElement.classList.add('bg-white', 'text-[#3B4A5A]');
                buttonElement.setAttribute('aria-current', 'false');
            } else {
                // Add category
                selectedCategories.push(categoryId.toString());
                buttonElement.classList.remove('bg-white', 'text-[#3B4A5A]');
                buttonElement.classList.add('bg-[#3B4A5A]', 'text-white');
                buttonElement.setAttribute('aria-current', 'true');
            }

            // Update products display
            updateProductsDisplay();
        }

        // Clear all categories
        function clearAllCategories() {
            selectedCategories = [];

            // Reset all category buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                if (btn.getAttribute('data-category-id')) {
                    btn.classList.remove('bg-[#3B4A5A]', 'text-white');
                    btn.classList.add('bg-white', 'text-[#3B4A5A]');
                    btn.setAttribute('aria-current', 'false');
                }
            });

            // Update ALL button state
            const allButton = document.querySelector('li[value="ALL"] button');
            allButton.classList.remove('bg-white', 'text-[#3B4A5A]');
            allButton.classList.add('bg-[#3B4A5A]', 'text-white');
            allButton.setAttribute('aria-current', 'true');

            // Update products display
            updateProductsDisplay();
        }

        // Update products display via HTMX
        function updateProductsDisplay() {
            const params = new URLSearchParams();

            // Add search filter
            const searchInput = document.getElementById('productSearch');
            if (searchInput && searchInput.value) {
                params.append('search', searchInput.value);
            }

            // Add brand filter
            const brandFilter = document.getElementById('brandFilter');
            if (brandFilter && brandFilter.value) {
                params.append('brand', brandFilter.value);
            }

            // Add condition filter
            const conditionFilter = document.getElementById('conditionFilter');
            if (conditionFilter && conditionFilter.value) {
                params.append('condition', conditionFilter.value);
            }

            // Add selected categories
            if (selectedCategories.length > 0) {
                selectedCategories.forEach(cat => {
                    params.append('categories[]', cat);
                });
            }

            // Make HTMX request
            const url = '{{ route("pos.itemlist") }}' + (params.toString() ? '?' + params.toString() : '');

            htmx.ajax('GET', url, {
                target: '#products-display',
                swap: 'innerHTML'
            });
        }
    </script>
</body>

</html>