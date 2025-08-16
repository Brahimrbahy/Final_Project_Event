<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 to-pink-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center mb-4">
                    <span class="text-white font-bold text-2xl">E</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Become an Event Organizer</h2>
                <p class="text-gray-600">Join our platform and start creating amazing events today</p>
            </div>

            <!-- Benefits Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">🚀 Organizer Benefits:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 text-sm">💰</span>
                        </div>
                        <span class="text-sm text-gray-700">85% revenue share</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 text-sm">📊</span>
                        </div>
                        <span class="text-sm text-gray-700">Real-time analytics</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 text-sm">💳</span>
                        </div>
                        <span class="text-sm text-gray-700">Secure Stripe payments</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 text-sm">📧</span>
                        </div>
                        <span class="text-sm text-gray-700">Email notifications</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 text-sm">🎫</span>
                        </div>
                        <span class="text-sm text-gray-700">Unlimited events</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-purple-600 text-sm">📱</span>
                        </div>
                        <span class="text-sm text-gray-700">Mobile-friendly tools</span>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="POST" action="{{ route('register.organizer') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="role" value="organizer">

                    <!-- Personal Information -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Full Name *
                                </label>
                                <input id="name" name="name" type="text" autocomplete="name" required 
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                                       placeholder="Your full name"
                                       value="{{ old('name') }}">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email Address *
                                </label>
                                <input id="email" name="email" type="email" autocomplete="email" required 
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                                       placeholder="your@email.com"
                                       value="{{ old('email') }}">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password *
                                </label>
                                <input id="password" name="password" type="password" autocomplete="new-password" required 
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                                       placeholder="Create a secure password">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm Password *
                                </label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required 
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                                       placeholder="Confirm your password">
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Company Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Company Name -->
                            <div>
                                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    Company Name *
                                </label>
                                <input id="company_name" name="company_name" type="text" required 
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                                       placeholder="Your company or organization"
                                       value="{{ old('company_name') }}">
                                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                            </div>

                            <!-- Contact Phone -->
                            <div>
                                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                    Contact Phone *
                                </label>
                                <input id="contact_phone" name="contact_phone" type="tel" required 
                                       class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                                       placeholder="+1 (555) 123-4567"
                                       value="{{ old('contact_phone') }}">
                                <x-input-error :messages="$errors->get('contact_phone')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Company Bio -->
                        <div class="mt-4">
                            <label for="company_bio" class="block text-sm font-medium text-gray-700 mb-1">
                                Company Bio *
                            </label>
                            <textarea id="company_bio" name="company_bio" rows="3" required 
                                      class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                                      placeholder="Tell us about your company and event organizing experience...">{{ old('company_bio') }}</textarea>
                            <x-input-error :messages="$errors->get('company_bio')" class="mt-2" />
                        </div>

                        <!-- Website (Optional) -->
                        <div class="mt-4">
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-1">
                                Website <span class="text-gray-500">(Optional)</span>
                            </label>
                            <input id="website" name="website" type="url" 
                                   class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                                   placeholder="https://yourcompany.com"
                                   value="{{ old('website') }}">
                            <x-input-error :messages="$errors->get('website')" class="mt-2" />
                        </div>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-start">
                            <input id="terms" name="terms" type="checkbox" required
                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-1">
                            <label for="terms" class="ml-3 block text-sm text-gray-700">
                                I agree to the <a href="#" class="text-purple-600 hover:text-purple-500">Terms of Service</a>, 
                                <a href="#" class="text-purple-600 hover:text-purple-500">Privacy Policy</a>, and understand that:
                                <ul class="mt-2 ml-4 text-xs text-gray-600 list-disc">
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
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-purple-300 group-hover:text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            Submit Organizer Application
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Links -->
            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="font-medium text-purple-600 hover:text-purple-500">
                        Sign in here
                    </a>
                </p>
                <p class="text-sm text-gray-600">
                    Just want to attend events? 
                    <a href="{{ route('register.client') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Register as Client
                    </a>
                </p>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('welcome') }}" class="hover:text-gray-700">
                        ← Back to Home
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
