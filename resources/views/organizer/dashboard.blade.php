@extends('layouts.dashbord')
@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="organizer" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
                <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Events</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_events'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('organizer.events') }}" class="text-sm text-blue-600 hover:text-blue-500">
                                Manage events →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Approved Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Approved Events</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_events'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <span class="text-sm text-gray-500">
                                {{ $stats['pending_events'] }} pending approval
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tickets Sold -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Tickets Sold</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_tickets_sold'] }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('organizer.bookings') }}" class="text-sm text-blue-600 hover:text-blue-500">
                                View bookings →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                                <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['total_revenue'], 2) }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('organizer.revenue') }}" class="text-sm text-blue-600 hover:text-blue-500">
                                View details →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Buttons -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Create Event Button -->
                        <a href="{{ route('organizer.events.create') }}"
                           class="flex items-center justify-center px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <div class="text-left">
                                <div class="font-semibold">Create New Event</div>
                                <div class="text-sm opacity-90">All categories available</div>
                            </div>
                        </a>

                        <!-- Manage Events Button -->
                        <a href="{{ route('organizer.events') }}"
                           class="flex items-center justify-center px-6 py-4 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                            </svg>
                            <div class="text-left">
                                <div class="font-semibold">Manage Events</div>
                                <div class="text-sm opacity-90">Edit, delete, view</div>
                            </div>
                        </a>

                        <!-- View Bookings Button -->
                        <a href="{{ route('organizer.bookings') }}"
                           class="flex items-center justify-center px-6 py-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z"></path>
                            </svg>
                            <div class="text-left">
                                <div class="font-semibold">View Bookings</div>
                                <div class="text-sm opacity-90">Client tickets</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Event Categories Info -->
            <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-lg font-medium text-blue-900">Available Event Categories</h4>
                        <div class="mt-2 text-sm text-blue-800">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">🎵 Concerts</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">🎪 Festivals</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">🎭 Theatre</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">⚽ Sports</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">🎬 Cinema</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">💼 Business</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">💻 Technology</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">🎨 Arts & Culture</span>
                            </div>
                            <p class="mt-3 text-blue-700">
                                <strong>Create events with:</strong> Custom titles, detailed descriptions, image uploads, pricing (free/paid),
                                date/time selection, location details, and ticket limits. All events require admin approval before going live.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Recent Events -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Events</h3>
                            <div class="flex space-x-2">
                                @if($stats['pending_events'] > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        {{ $stats['pending_events'] }} Pending Approval
                                    </span>
                                @endif
                                <a href="{{ route('organizer.events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                                    Create Event
                                </a>
                            </div>
                        </div>
                        @if($recent_events->count() > 0)
                            <div class="space-y-3">
                                @foreach($recent_events as $event)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $event->title }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ $event->category }} • 
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                    {{ $event->approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $event->approved ? 'Approved' : 'Pending' }}
                                                </span>
                                            </p>
                                            <p class="text-xs text-gray-400">{{ $event->start_date->format('M d, Y') }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('organizer.events.edit', $event) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                                Edit
                                            </a>
                                            @if($event->approved)
                                                <a href="{{ route('events.show', $event) }}" class="text-green-600 hover:text-green-900 text-sm">
                                                    View
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No events yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating your first event.</p>
                                <div class="mt-6">
                                    <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Create Event
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Bookings</h3>
                            <a href="{{ route('organizer.bookings') }}" class="text-sm text-blue-600 hover:text-blue-500">
                                View all
                            </a>
                        </div>
                        @if($recent_bookings->count() > 0)
                            <div class="space-y-3">
                                @foreach($recent_bookings as $booking)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $booking->event->title }}</p>
                                            <p class="text-sm text-gray-500">{{ $booking->client->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $booking->created_at->diffForHumans() }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">{{ $booking->quantity }} tickets</p>
                                            <p class="text-xs text-gray-500">${{ number_format($booking->total_price, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Bookings will appear here once people start buying tickets.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Admin Approval Process Info -->
            @if($stats['pending_events'] > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-yellow-900">Events Pending Admin Approval</h4>
                            <div class="mt-2 text-sm text-yellow-800">
                                <p class="mb-2">
                                    You have <strong>{{ $stats['pending_events'] }}</strong> event(s) waiting for admin approval.
                                </p>
                                <div class="bg-yellow-100 rounded-md p-3">
                                    <h5 class="font-medium text-yellow-900 mb-2">Approval Process:</h5>
                                    <ul class="list-disc list-inside space-y-1 text-yellow-800">
                                        <li>Admin reviews event details, content, and compliance</li>
                                        <li>Approval typically takes 1-3 business days</li>
                                        <li>Once approved, events become visible to the public</li>
                                        <li>You'll receive email notification when status changes</li>
                                        <li>You can edit events while they're pending (may require re-approval)</li>
                                    </ul>
                                </div>
                                <div class="mt-3 flex space-x-3">
                                    <a href="{{ route('organizer.events') }}"
                                       class="inline-flex items-center px-3 py-2 border border-yellow-300 text-sm leading-4 font-medium rounded-md text-yellow-700 bg-yellow-100 hover:bg-yellow-200">
                                        View Pending Events
                                    </a>
                                    <a href="{{ route('organizer.events.create') }}"
                                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                                        Create Another Event
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-medium text-green-900">All Events Approved!</h4>
                            <div class="mt-2 text-sm text-green-800">
                                <p class="mb-2">
                                    Great! All your events have been approved by the admin and are visible to the public.
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('organizer.events.create') }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create New Event
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
@endsection