<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vantech Computers - Employee Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Montserrat Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen font-sans">
    <!-- Full Page Background Image with Blur -->
    <div class="fixed inset-0 bg-cover bg-center bg-no-repeat z-0"
        style="background-image: url('{{ asset('images/vantechBG.svg') }}'); filter: blur(8px); transform: scale(1.1);">
    </div>

    <!-- Dark Overlay for Better Readability -->
    <div class="fixed inset-0 bg-black bg-opacity-40 z-10"></div>

    <!-- Main Content -->
    <div class="relative z-20 min-h-screen flex items-center justify-center p-4">
        <div
            class="w-full max-w-7xl bg-gray-800/60 rounded-2xl shadow-2xl overflow-hidden border border-gray-600/50 backdrop-blur-md">

            <div class="grid grid-cols-1 lg:grid-cols-2">
                <!-- Left Side - Brand Section -->
                <div
                    class="relative min-h-[600px] bg-gradient-to-br from-blue-900/50 to-purple-900/40 backdrop-blur-lg">
                    <!-- Content Container -->
                    <div class="p-8 lg:p-12 h-full flex flex-col justify-center text-white">
                        <!-- Company Header -->
                        <div class="text-center mb-12">
                            <h1 class="text-4xl lg:text-5xl font-bold mb-3 tracking-wide drop-shadow-lg font-sans">
                                VANTECH COMPUTERS
                            </h1>
                            <h2 class="text-xl lg:text-2xl text-blue-200 font-semibold drop-shadow font-sans">
                                SALES AND SERVICES
                            </h2>
                            <div class="w-24 h-1 bg-blue-400 mx-auto mt-4 rounded-full"></div>
                        </div>

                        <!-- Services Section -->
                        <div class="mb-12">
                            <h3
                                class="text-2xl font-bold mb-6 text-center border-b border-blue-600/50 pb-3 drop-shadow font-sans">
                                Our Services
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <ul class="space-y-4">
                                    <li class="flex items-center font-sans">
                                        <span
                                            class="w-3 h-3 bg-blue-400 rounded-full mr-4 drop-shadow flex-shrink-0"></span>
                                        <span class="text-lg drop-shadow font-medium">COMPUTER SET</span>
                                    </li>
                                    <li class="flex items-center font-sans">
                                        <span
                                            class="w-3 h-3 bg-blue-400 rounded-full mr-4 drop-shadow flex-shrink-0"></span>
                                        <span class="text-lg drop-shadow font-medium">UPGRADE</span>
                                    </li>
                                    <li class="flex items-center font-sans">
                                        <span
                                            class="w-3 h-3 bg-blue-400 rounded-full mr-4 drop-shadow flex-shrink-0"></span>
                                        <span class="text-lg drop-shadow font-medium">CLEANING</span>
                                    </li>
                                    <li class="flex items-center font-sans">
                                        <span
                                            class="w-3 h-3 bg-blue-400 rounded-full mr-4 drop-shadow flex-shrink-0"></span>
                                        <span class="text-lg drop-shadow font-medium">REPAIR</span>
                                    </li>
                                </ul>
                                <ul class="space-y-4">
                                    <li class="flex items-center font-sans">
                                        <span
                                            class="w-3 h-3 bg-blue-400 rounded-full mr-4 drop-shadow flex-shrink-0"></span>
                                        <span class="text-lg drop-shadow font-medium">ACCESSORIES</span>
                                    </li>
                                    <li class="flex items-center font-sans">
                                        <span
                                            class="w-3 h-3 bg-blue-400 rounded-full mr-4 drop-shadow flex-shrink-0"></span>
                                        <span class="text-lg drop-shadow font-medium">REFORMAT</span>
                                    </li>
                                    <li class="flex items-center font-sans">
                                        <span
                                            class="w-3 h-3 bg-blue-400 rounded-full mr-4 drop-shadow flex-shrink-0"></span>
                                        <span class="text-lg drop-shadow font-medium">CUSTOM BUILD</span>
                                    </li>
                                    <li class="flex items-center font-sans">
                                        <span
                                            class="w-3 h-3 bg-blue-400 rounded-full mr-4 drop-shadow flex-shrink-0"></span>
                                        <span class="text-md drop-shadow font-medium">OS/SOFTWARE INSTALLATION</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div
                            class="bg-blue-900/40 backdrop-blur-lg rounded-xl p-6 border border-blue-500/30 mx-auto w-full max-w-md">
                            <h4 class="text-xl font-bold mb-6 text-center drop-shadow font-sans">Contact Us</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-start font-sans">
                                    <svg class="w-6 h-6 text-blue-200 mr-3 drop-shadow flex-shrink-0"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                    <span class="text-blue-100 drop-shadow font-medium">facebook.com/vantechtips</span>
                                </div>
                                <div class="flex items-center justify-start font-sans">
                                    <svg class="w-6 h-6 text-blue-200 mr-3 drop-shadow flex-shrink-0"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M20 22.621l-3.521-6.795c-.008.004-1.974.97-2.064 1.011-2.24 1.086-6.799-7.82-4.609-8.994l2.083-1.026-3.493-6.817-2.106 1.039c-7.202 3.755 4.233 25.982 11.6 22.615.121-.055 2.102-1.029 2.11-1.033z" />
                                    </svg>
                                    <span class="text-blue-100 drop-shadow font-medium">0995 525 4038</span>
                                </div>
                                <div class="flex items-start justify-start font-sans">
                                    <svg class="w-6 h-6 text-blue-200 mr-3 drop-shadow flex-shrink-0 mt-1"
                                        fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z" />
                                    </svg>
                                    <span class="text-blue-100 text-sm drop-shadow font-medium leading-relaxed">
                                        758 F Purok 3, Brgy. Mintal, Davao City, 8000 Davao del Sur
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div
                    class="bg-gradient-to-br from-gray-900/60 via-gray-800/50 to-gray-900/60 p-8 lg:p-12 border-l border-gray-600/30 backdrop-blur-lg">
                    <div class="max-w-md mx-auto">
                        <!-- Form Header -->
                        <div class="text-center mb-8">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 bg-blue-600/80 rounded-2xl mb-4 backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl lg:text-3xl font-bold text-white mb-2 drop-shadow font-sans">
                                Employee Access
                            </h2>
                            <p class="text-gray-300 drop-shadow font-sans">Enter your credentials to continue</p>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            <!-- Username Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2 drop-shadow font-sans">
                                    Username
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="username" placeholder="Enter your username" required
                                        autocomplete="off" class="w-full pl-10 pr-4 py-3 bg-gray-800/40 border border-gray-600/50 rounded-lg 
                                               text-white placeholder-gray-300 focus:outline-none focus:ring-2 
                                               focus:ring-blue-400 focus:border-transparent transition duration-200
                                               backdrop-blur-sm drop-shadow font-sans">
                                </div>
                                @error('username')
                                    <p class="mt-1 text-sm text-red-300 drop-shadow font-sans">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2 drop-shadow font-sans">
                                    Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input type="password" name="password" placeholder="Enter your password" required
                                        autocomplete="off" class="w-full pl-10 pr-4 py-3 bg-gray-800/40 border border-gray-600/50 rounded-lg 
                                               text-white placeholder-gray-300 focus:outline-none focus:ring-2 
                                               focus:ring-blue-400 focus:border-transparent transition duration-200
                                               backdrop-blur-sm drop-shadow font-sans">
                                </div>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-300 drop-shadow font-sans">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600/80 to-blue-700/80 hover:from-blue-700/90 
                                       hover:to-blue-800/90 text-white font-semibold rounded-lg shadow-lg 
                                       transform hover:scale-[1.02] transition duration-200 focus:outline-none 
                                       focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 focus:ring-offset-gray-800/50
                                       shadow-blue-500/30 backdrop-blur-sm font-sans">
                                Login to System
                            </button>

                            <!-- Error Message -->
                            @if(session('error'))
                                <div
                                    class="p-3 bg-red-500/30 border border-red-500/50 rounded-lg text-red-300 text-sm text-center backdrop-blur-sm drop-shadow font-sans">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </form>

                        <!-- Footer Note -->
                        <div class="mt-8 text-center">
                            <p class="text-sm text-gray-400 drop-shadow font-sans">
                                Secure employee access portal • Vantech Computers
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>