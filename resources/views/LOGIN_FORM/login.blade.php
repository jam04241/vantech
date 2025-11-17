<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vantech Computers - Employee Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-6xl">
        <!-- Main Container with fixed height -->
        <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl shadow-2xl overflow-hidden">
            <!-- Hide image on mobile (< 1024px), show on desktop with equal heights -->
            <div class="grid grid-cols-1 lg:grid-cols-2 lg:h-[600px]">
                <!-- Left Side - Background Image (Hidden on mobile) -->
                <!-- Recommended size: 820x600px for perfect fit -->
                <div class="hidden lg:block relative overflow-hidden bg-gray-900">
                    <img src="{{ asset('images/vantechBG.svg') }}" alt="Vantech Computers"
                        class="absolute inset-0 w-full h-full object-contain">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent to-gray-900/30"></div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="flex items-center justify-center p-8 lg:p-12 min-h-[500px] lg:h-full">
                    <div class="w-full max-w-md">
                        <!-- Welcome Text -->
                        <div class="mb-8 text-center lg:text-left">
                            <h1 class="text-3xl lg:text-4xl font-bold text-white mb-2">
                                WELCOME EMPLOYEES
                            </h1>
                            <div class="w-16 h-1 bg-blue-500 mx-auto lg:mx-0"></div>
                        </div>

                        <!-- Login Form -->
                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            <!-- Username Input -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input type="text" name="username" placeholder="Username" required
                                    class="w-full pl-12 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                @error('username')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Input -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                                <input type="password" name="password" placeholder="Password" required
                                    class="w-full pl-12 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center text-sm">
                                <label class="flex items-center text-gray-300 cursor-pointer">
                                    <input type="checkbox" name="remember"
                                        class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-blue-500 focus:ring-blue-500 focus:ring-offset-gray-800">
                                    <span class="ml-2">Remember me</span>
                                </label>
                            </div>

                            <!-- Login Button -->
                            <button type="submit"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                                Login
                            </button>

                            <!-- Error Message -->
                            @if(session('error'))
                                <div
                                    class="p-3 bg-red-500/20 border border-red-500 rounded-lg text-red-400 text-sm text-center">
                                    {{ session('error') }}
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>