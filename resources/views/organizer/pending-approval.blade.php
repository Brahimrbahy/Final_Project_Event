@extends('layouts.dashbord')
@section('content')

    <div class="flex h-screen bg-gray-100">
            <x-dashboard-sidebar role="organizer" :current-route="request()->route()->getName()" />
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="text-center">
                        <!-- Pending Icon -->
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-6">
                            <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>

                        <!-- Main Message -->
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            🎯 Your Organizer Account is Pending Approval
                        </h3>

                        <p class="text-lg text-gray-600 mb-4">
                            Thank you for registering as an event organizer! Your application is currently being reviewed by our admin team.
                        </p>

                        <div class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-yellow-300 rounded-full text-yellow-800 text-sm font-medium mb-8">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Status: Under Review
                        </div>

                        <!-- Status Information -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 text-left">
                                    <h4 class="text-sm font-medium text-yellow-800">What happens next?</h4>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Our admin team will review your organizer profile and information</li>
                                            <li>This process typically takes 1-3 business days</li>
                                            <li>You'll receive an email notification once your account is approved</li>
                                            <li>After approval, you'll be able to create and manage events</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Your Information -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-8">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Your Submitted Information</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-left">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Name</p>
                                    <p class="text-sm text-gray-900">{{ Auth::user() ? Auth::user()->name : 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Email</p>
                                    <p class="text-sm text-gray-900">{{ Auth::user() ? Auth::user()->email : 'N/A' }}</p>
                                </div>
                                @if(Auth::user() && Auth::user()->organizerProfile)
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Company Name</p>
                                        <p class="text-sm text-gray-900">{{ Auth::user() && Auth::user()->organizerProfile ? Auth::user()->organizerProfile->company_name : 'Not provided' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Contact Info</p>
                                        <p class="text-sm text-gray-900">{{ Auth::user() && Auth::user()->organizerProfile ? Auth::user()->organizerProfile->contact_info : 'Not provided' }}</p>
                                    </div>
                                    @if(Auth::user()->organizerProfile->bio)
                                        <div class="md:col-span-2">
                                            <p class="text-sm font-medium text-gray-500">Bio</p>
                                            <p class="text-sm text-gray-900">{{ Auth::user()->organizerProfile->bio }}</p>
                                        </div>
                                    @endif
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Registration Date</p>
                                    <p class="text-sm text-gray-900">{{ Auth::user()->created_at->format('F d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- What You Can Do -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 text-left">
                                    <h4 class="text-sm font-medium text-blue-800">While you wait</h4>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Browse existing events on our platform</li>
                                            <li>Plan your first event and gather all necessary information</li>
                                            <li>Prepare event images and descriptions</li>
                                            <li>Review our event creation guidelines</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('events.index') }}" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                </svg>
                                Browse Events
                            </a>
                            
                            <a href="{{ route('profile.edit') }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Edit Profile
                            </a>
                        </div>

                        <!-- Auto-refresh Notice -->
                        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm text-blue-800">
                                        <strong>Auto-refresh:</strong> This page will automatically check for approval status every 30 seconds.
                                        Once approved, you'll be redirected to your organizer dashboard.
                                    </p>
                                </div>
                                <div class="text-xs text-blue-600 status-time">
                                    Checking...
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <p class="text-sm text-gray-500 text-center">
                                Have questions about your application?
                                <a href="mailto:support@eventmanagement.com" class="text-blue-600 hover:text-blue-500 font-medium">
                                    Contact our support team
                                </a>
                            </p>
                        </div>

                        <!-- Refresh Button -->
                        <div class="mt-4 text-center">
                            <button onclick="manualCheckStatus()" id="manual-check-btn"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition duration-200 disabled:opacity-50">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span id="check-btn-text">Check Status Now</span>
                            </button>
                        </div>

                        <!-- Debug Info (remove in production) -->
                        <div class="mt-4 text-center">
                            <div id="debug-info" class="text-xs text-gray-500 bg-gray-100 p-2 rounded hidden">
                                Debug info will appear here
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-refresh Script -->
    <script>
        // Auto-refresh every 10 seconds to check approval status (faster for testing)
        let checkInterval = setInterval(checkApprovalStatus, 10000);

        function checkApprovalStatus() {
            console.log('Checking approval status...');

            fetch('{{ route("organizer.check-approval-status") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Approval status response:', data);

                if (data.approved === true) {
                    console.log('User is approved! Redirecting...');

                    // Clear the interval to stop checking
                    clearInterval(checkInterval);

                    // Show success message and redirect
                    showApprovalSuccess();

                    // Redirect after 2 seconds
                    setTimeout(() => {
                        const redirectUrl = data.redirect_url || '{{ route("organizer.dashboard") }}';
                        console.log('Redirecting to:', redirectUrl);
                        window.location.href = redirectUrl;
                    }, 2000);
                } else {
                    console.log('Still pending approval');
                }
                updateLastCheckTime();
            })
            .catch(error => {
                console.error('Status check failed:', error);
                updateLastCheckTime();
            });
        }

        function showApprovalSuccess() {
            // Create success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300';
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <div>
                        <p class="font-semibold">🎉 Approved!</p>
                        <p class="text-sm">Redirecting to your dashboard...</p>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
        }

        // Manual check function
        function manualCheckStatus() {
            const btn = document.getElementById('manual-check-btn');
            const btnText = document.getElementById('check-btn-text');

            btn.disabled = true;
            btnText.textContent = 'Checking...';

            checkApprovalStatus().finally(() => {
                btn.disabled = false;
                btnText.textContent = 'Check Status Now';
            });
        }

        // Show last check time
        function updateLastCheckTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            const statusElement = document.querySelector('.status-time');
            if (statusElement) {
                statusElement.textContent = `Last checked: ${timeString}`;
            }

            // Update debug info
            const debugElement = document.getElementById('debug-info');
            if (debugElement) {
                debugElement.textContent = `Last check: ${timeString} - URL: {{ route("organizer.check-approval-status") }}`;
                debugElement.classList.remove('hidden');
            }
        }

        // Initial check
        updateLastCheckTime();

        // Run initial check after 2 seconds
        setTimeout(() => {
            console.log('Running initial approval check...');
            checkApprovalStatus();
        }, 2000);
    </script>
@endsection
