@extends('layouts.dashbord')
@section('content')
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="organizer" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 lg:ml-0">
            <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
                
                <!-- Header Section -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Overview</h1>
                    <p class="text-gray-600">Manage your events and track your performance</p>
                </div>

                <!-- Statistics Cards with improved design -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    <!-- Total Events -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Events</p>
                                    <p class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['total_events'] }}</p>
                                    <p class="text-sm text-gray-500">This month</p>
                                </div>
                                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('organizer.events') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                    Manage events →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Approved Events -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Approved Events</p>
                                    <p class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['approved_events'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $stats['pending_events'] }} pending</p>
                                </div>
                                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tickets Sold -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Tickets Sold</p>
                                    <p class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['total_tickets_sold'] }}</p>
                                    <p class="text-sm text-gray-500">From last month</p>
                                </div>
                                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('organizer.bookings') }}" class="text-sm font-semibold text-purple-600 hover:text-purple-700 transition-colors">
                                    View bookings →
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Total Revenue -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Revenue</p>
                                    <p class="text-4xl font-bold text-gray-900 mb-1">${{ number_format($stats['total_revenue'], 2) }}</p>
                                    <p class="text-sm text-gray-500">From last event</p>
                                </div>
                                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('organizer.revenue') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                                    View details →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Buttons with improved styling -->
                <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100 mb-10">
                    <div class="p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Quick Actions</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Create Event Button -->
                            <a href="{{ route('organizer.events.create') }}"
                               class="group relative overflow-hidden bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-lg">Create New Event</div>
                                        <div class="text-sm opacity-90">All categories available</div>
                                    </div>
                                </div>
                            </a>

                            <!-- Manage Events Button -->
                            <a href="{{ route('organizer.events') }}"
                               class="group relative overflow-hidden bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-lg">Manage Events</div>
                                        <div class="text-sm opacity-90">Edit, delete, view</div>
                                    </div>
                                </div>
                            </a>

                            <!-- View Bookings Button -->
                            <a href="{{ route('organizer.bookings') }}"
                               class="group relative overflow-hidden bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-xl p-6 transition-all duration-300 transform hover:scale-105 hover:shadow-lg">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-lg">View Bookings</div>
                                        <div class="text-sm opacity-90">Client tickets</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Event Categories Info with modern design -->
               

                <!-- Recent Activity with table-like design -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                    <!-- Recent Events -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Recent Events</h3>
                                <div class="flex items-center space-x-3">
                                    @if($stats['pending_events'] > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                            {{ $stats['pending_events'] }} Pending
                                        </span>
                                    @endif
                                    <a href="{{ route('organizer.events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Create Event
                                    </a>
                                </div>
                            </div>
                            @if($recent_events->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recent_events as $event)
                                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-xl border border-gray-100 hover:shadow-md transition-all duration-200">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900 text-lg mb-2">{{ $event->title }}</p>
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <span class="text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-lg">{{ $event->category }}</span>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                                        {{ $event->approved ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                                        {{ $event->approved ? 'Approved' : 'Pending' }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-500">{{ $event->start_date->format('M d, Y') }}</p>
                                            </div>
                                            <div class="flex space-x-3 ml-4">
                                                <a href="{{ route('organizer.events.edit', $event) }}" class="text-blue-600 hover:text-blue-700 font-semibold text-sm px-3 py-2 rounded-lg hover:bg-blue-50 transition-colors">
                                                    Edit
                                                </a>
                                                @if($event->approved)
                                                    <a href="{{ route('events.show', $event) }}" class="text-green-600 hover:text-green-700 font-semibold text-sm px-3 py-2 rounded-lg hover:bg-green-50 transition-colors">
                                                        View
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No events yet</h3>
                                    <p class="text-gray-500 mb-6">Get started by creating your first event.</p>
                                    <a href="{{ route('organizer.events.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                        Create Event
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Bookings -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-100">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Recent Bookings</h3>
                                <a href="{{ route('organizer.bookings') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                                    View all →
                                </a>
                            </div>
                            @if($recent_bookings->count() > 0)
                                <div class="space-y-4">
                                    @foreach($recent_bookings as $booking)
                                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-xl border border-gray-100 hover:shadow-md transition-all duration-200">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-900 text-lg mb-1">{{ $booking->event->title }}</p>
                                                <p class="text-gray-600 mb-1">{{ $booking->client->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                                            </div>
                                            <div class="text-right ml-4">
                                                <p class="font-semibold text-gray-900 text-lg">{{ $booking->quantity }} tickets</p>
                                                <p class="text-gray-600">${{ number_format($booking->total_price, 2) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No bookings yet</h3>
                                    <p class="text-gray-500">Bookings will appear here once people start buying tickets.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Admin Approval Process Info with improved design -->
                @if($stats['pending_events'] > 0)
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-2xl p-8 shadow-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                                    <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h4 class="text-xl font-bold text-amber-900 mb-4">Events Pending Admin Approval</h4>
                                <p class="text-amber-800 mb-6">
                                    You have <strong class="font-semibold">{{ $stats['pending_events'] }}</strong> event(s) waiting for admin approval.
                                </p>
                                <div class="bg-white bg-opacity-70 rounded-xl p-6 mb-6 border border-amber-100">
                                    <h5 class="font-bold text-amber-900 mb-4">Approval Process:</h5>
                                    <ul class="list-disc list-inside space-y-2 text-amber-800">
                                        <li>Admin reviews event details, content, and compliance</li>
                                        <li>Approval typically takes 1-3 business days</li>
                                        <li>Once approved, events become visible to the public</li>
                                        <li>You'll receive email notification when status changes</li>
                                        <li>You can edit events while they're pending (may require re-approval)</li>
                                    </ul>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('organizer.events') }}"
                                       class="inline-flex items-center px-6 py-3 border border-amber-300 text-sm font-semibold rounded-xl text-amber-700 bg-amber-100 hover:bg-amber-200 transition-colors">
                                        View Pending Events
                                    </a>
                                    <a href="{{ route('organizer.events.create') }}"
                                       class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-amber-600 hover:bg-amber-700 transition-colors">
                                        Create Another Event
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 rounded-2xl p-8 shadow-lg">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                    <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h4 class="text-xl font-bold text-emerald-900 mb-4">All Events Approved!</h4>
                                <p class="text-emerald-800 mb-6">
                                    Great! All your events have been approved by the admin and are visible to the public.
                                </p>
                                <a href="{{ route('organizer.events.create') }}"
                                   class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 transition-colors">
                                    <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Create New Event
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection