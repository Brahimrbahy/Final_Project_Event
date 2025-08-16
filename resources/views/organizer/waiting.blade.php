<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center mb-6">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Application Under Review</h2>
                <p class="text-gray-600">Your organizer application is being reviewed by our admin team</p>
            </div>

            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pending Approval</h3>
                    <p class="text-gray-600 mb-4">
                        Thank you for applying to become an event organizer! Our admin team will review your application and get back to you soon.
                    </p>
                    
                    <!-- Application Details -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-4">
                        <h4 class="font-medium text-gray-900 mb-2">Application Details:</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            <p><strong>Company:</strong> {{ Auth::user()->company_name }}</p>
                            <p><strong>Phone:</strong> {{ Auth::user()->contact_phone }}</p>
                            <p><strong>Submitted:</strong> {{ Auth::user()->created_at->format('M j, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- What Happens Next -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">What happens next?</h3>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-blue-600 text-sm font-semibold">1</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Admin Review</p>
                            <p class="text-xs text-gray-600">Our team will review your application and company information</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-blue-600 text-sm font-semibold">2</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Email Notification</p>
                            <p class="text-xs text-gray-600">You'll receive an email once your application is approved</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                            <span class="text-blue-600 text-sm font-semibold">3</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Start Creating Events</p>
                            <p class="text-xs text-gray-600">Access your organizer dashboard and create your first event</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expected Timeline -->
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg p-6 text-white">
                <div class="text-center">
                    <h3 class="text-lg font-semibold mb-2">⏰ Expected Timeline</h3>
                    <p class="text-purple-100 mb-2">
                        Most applications are reviewed within <strong>24-48 hours</strong>
                    </p>
                    <p class="text-sm text-purple-200">
                        We'll email you at <strong>{{ Auth::user()->email }}</strong> once approved
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <a href="{{ route('events.index') }}" 
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 text-center block">
                    Browse Events While You Wait
                </a>
                
                <div class="flex space-x-3">
                    <a href="{{ route('profile.edit') }}" 
                       class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200 text-center">
                        Edit Profile
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Questions about your application? 
                    <a href="mailto:support@eventhub.com" class="text-purple-600 hover:text-purple-500 font-medium">
                        Contact Support
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
