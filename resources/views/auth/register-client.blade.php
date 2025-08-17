<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background-color: #0B1623;">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-3xl font-normal text-white mb-2">Join as a Client</h2>
                <p class="text-gray-400">Create your account to discover and book amazing events</p>
            </div>

           

            <!-- Registration Form -->
            <div class="bg-gray-800 bg-opacity-50 rounded-xl p-6 border border-gray-700">
                <form method="POST" action="{{ route('register.client') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="role" value="client">

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-white mb-2">
                            Full Name
                        </label>
                        <input id="name" name="name" type="text" autocomplete="name" required
                               class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                               placeholder="Enter your full name"
                               value="{{ old('name') }}">
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-2">
                            Email Address
                        </label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                               placeholder="Enter your email address"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-2">
                            Password
                        </label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                               placeholder="Create a secure password">
                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                            Confirm Password
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                               class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-200"
                               placeholder="Confirm your password">
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <input id="terms" name="terms" type="checkbox" required
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-600 bg-transparent rounded mt-1">
                        <label for="terms" class="ml-3 block text-sm text-gray-300">
                            I agree to the <a href="#" class="text-blue-400 hover:text-blue-300 underline">Terms of Service</a> and
                            <a href="#" class="text-blue-400 hover:text-blue-300 underline">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full bg-white text-black font-medium py-3 px-4 rounded-xl hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition duration-200">
                            Create Client Account
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Links -->
            <div class="text-center space-y-4 mt-8">
                <p class="text-gray-400 mb-4">Already have an account?</p>
                <a href="{{ route('login') }}"
                   class="inline-block border-2 border-blue-500 text-blue-400 font-medium py-2 px-6 rounded-xl hover:bg-blue-500 hover:text-white transition duration-200 mb-4">
                    Sign in here
                </a>

                <div class="space-y-2">
                    <p class="text-sm text-gray-400">
                        Want to organize events?
                        <a href="{{ route('register.organizer') }}" class="text-purple-400 hover:text-purple-300 underline">
                            Register as Organizer
                        </a>
                    </p>
                    <p class="text-sm text-gray-500">
                        <a href="{{ route('welcome') }}" class="hover:text-gray-300">
                            ← Back to Home
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
