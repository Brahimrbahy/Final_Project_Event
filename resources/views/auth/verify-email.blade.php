<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Check Your Email</h2>
                <p class="text-gray-600">We've sent a verification link to your email address</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                A new verification link has been sent to your email address.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Verify Your Email Address</h3>
                    <p class="text-gray-600 mb-4">
                        Before you can start booking events, please verify your email address by clicking the link we sent to:
                    </p>
                    
                    <div class="bg-gray-50 rounded-lg p-3 mb-4">
                        <p class="font-medium text-gray-900">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <p class="text-sm text-gray-600 mb-6">
                        The verification link will expire in 60 minutes for security reasons.
                    </p>
                </div>

                <!-- Instructions -->
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="font-medium text-blue-900 mb-2">📧 What to do next:</h4>
                    <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                        <li>Check your email inbox (and spam folder)</li>
                        <li>Look for an email from EventHub</li>
                        <li>Click the "Verify Email Address" button</li>
                        <li>You'll be redirected back to your dashboard</li>
                    </ol>
                </div>

                <!-- Resend Email Form -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Resend Verification Email
                    </button>
                </form>

                <!-- Help Text -->
                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-2">
                        Didn't receive the email? Check your spam folder or click the button above to resend.
                    </p>
                </div>
            </div>

            <!-- Alternative Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Need Help?</h3>
                
                <div class="space-y-3">
                    <!-- Change Email -->
                    <a href="{{ route('profile.edit') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200 text-center block">
                        📝 Update Email Address
                    </a>
                    
                    <!-- Browse Events -->
                    <a href="{{ route('events.index') }}" 
                       class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200 text-center block">
                        👀 Browse Events (View Only)
                    </a>
                    
                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Still having trouble? 
                    <a href="mailto:support@eventhub.com" class="text-blue-600 hover:text-blue-500 font-medium">
                        Contact Support
                    </a>
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    <a href="{{ route('welcome') }}" class="hover:text-gray-700">
                        ← Back to Home
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
