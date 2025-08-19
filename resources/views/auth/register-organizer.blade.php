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
        <div class="max-w-2xl w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-3xl font-normal text-white mb-2">Become an Event Organizer</h2>
                <p class="text-gray-400">Join our platform and start creating amazing events today</p>
            </div>

            <!-- Benefits Section -->
           

            <!-- Registration Form -->
            <div class="bg-gray-800 bg-opacity-50 rounded-xl p-6 border border-gray-700">
                <form method="POST" action="{{ route('register.organizer') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="role" value="organizer">

                    <!-- Personal Information -->
                    <div class="border-b border-gray-600 pb-6">
                        <h3 class="text-lg font-medium text-white mb-4">Personal Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-white mb-2">
                                    Full Name *
                                </label>
                                <input id="name" name="name" type="text" autocomplete="name" required
                                       class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition duration-200"
                                       placeholder="Your full name"
                                       value="{{ old('name') }}">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-white mb-2">
                                    Email Address *
                                </label>
                                <input id="email" name="email" type="email" autocomplete="email" required
                                       class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition duration-200"
                                       placeholder="your@email.com"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-white mb-2">
                                    Password *
                                </label>
                                <input id="password" name="password" type="password" autocomplete="new-password" required
                                       class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition duration-200"
                                       placeholder="Create a secure password">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                                    Confirm Password *
                                </label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                       class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition duration-200"
                                       placeholder="Confirm your password">
                                @error('password_confirmation')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div>
                        <h3 class="text-lg font-medium text-white mb-4">Company Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Company Name -->
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-white mb-2">
                                    Company Name *
                                </label>
                                <input id="company_name" name="company_name" type="text" required
                                       class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition duration-200"
                                       placeholder="Your company or organization"
                                       value="{{ old('company_name') }}">
                                @error('company_name')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-white mb-2">
                                    Contact Phone *
                                </label>
                                <input id="contact_phone" name="contact_phone" type="tel" required
                                       class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition duration-200"
                                       placeholder="+1 (555) 123-4567"
                                       value="{{ old('contact_phone') }}">
                                @error('contact_phone')
                                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Company Bio -->
                        <div class="mt-4">
                            <label for="company_bio" class="block text-sm font-medium text-white mb-2">
                                Company Bio *
                            </label>
                            <textarea id="company_bio" name="company_bio" rows="3" required
                                      class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition duration-200 resize-none"
                                      placeholder="Tell us about your company and event organizing experience...">{{ old('company_bio') }}</textarea>
                            @error('company_bio')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Website (Optional) -->
                        <div class="mt-4">
                            <label for="website" class="block text-sm font-medium text-white mb-2">
                                Website <span class="text-gray-400">(Optional)</span>
                            </label>
                            <input id="website" name="website" type="url"
                                   class="w-full px-4 py-3 bg-transparent border border-gray-600 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition duration-200"
                                   placeholder="https://yourcompany.com"
                                   value="{{ old('website') }}">
                            @error('website')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="border-t border-gray-600 pt-6">
                        <div class="flex items-start">
                            <input id="terms" name="terms" type="checkbox" required
                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-600 bg-transparent rounded mt-1">
                            <label for="terms" class="ml-3 block text-sm text-gray-300">
                                I agree to the <a href="#" class="text-purple-400 hover:text-purple-300 underline">Terms of Service</a>,
                                <a href="#" class="text-purple-400 hover:text-purple-300 underline">Privacy Policy</a>, and understand that:
                                <ul class="mt-2 ml-4 text-xs text-gray-400 list-disc">
                                    <li>My account requires admin approval before I can create events</li>
                                    <li>A 15% platform fee applies to all ticket sales</li>
                                    <li>I will receive 85% of the revenue from my events</li>
                                    <li>All events must comply with platform guidelines</li>
                                </ul>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full bg-white rounded-[50px] text-black font-medium py-3 px-4  hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-900 transition duration-200">
                            Submit Organizer Application
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Links -->
            <div class="text-center space-y-4 mt-8">
                <p class="text-gray-400 mb-4">Already have an account?</p>
                <a href="{{ route('login') }}"
                   class="inline-block border-2 rounded-[50px] border-purple-500 text-purple-400 font-medium py-2 px-6  hover:bg-purple-500 hover:text-white transition duration-200 mb-4">
                    Sign in here
                </a>

                <div class="space-y-2">
                    <p class="text-sm text-gray-400">
                        Just want to attend events?
                        <a href="{{ route('register.client') }}" class="text-blue-400 hover:text-blue-300 underline">
                            Register as Client
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
