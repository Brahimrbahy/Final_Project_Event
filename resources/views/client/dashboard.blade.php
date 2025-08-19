@extends('layouts.dashbord')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="client" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
            <x-client-header />

            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Welcome back, {{ Auth::user()->name }}! 👋
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">
                                Manage your tickets and discover amazing events
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <a href="{{ route('events.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                🎫 Browse Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Tickets -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tickets</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ Auth::user()->tickets ? Auth::user()->tickets()->sum('quantity') : 0 }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Events -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Upcoming Events</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ Auth::user()->tickets ? Auth::user()->tickets()->whereHas('event', function($q) { $q->where('start_date', '>', now()); })->count() : 0 }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Spent -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Spent</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    ${{ number_format((Auth::user()->tickets ? Auth::user()->tickets()->where('payment_status', 'paid')->sum('total_price') : 0) * 1.15, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Tickets Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">🎫 My Tickets</h3>
                        <a href="{{ route('events.index') }}" 
                           class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                            Browse more events →
                        </a>
                    </div>

                    @php
                        $tickets = Auth::user()->tickets ? Auth::user()->tickets()->with(['event', 'event.organizer'])->orderBy('created_at', 'desc')->get() : collect();
                    @endphp

                    @if($tickets->count() > 0)
                        <div class="space-y-4">
                            @foreach($tickets->take(5) as $ticket)
                                <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-start space-x-4">
                                                @if($ticket->event->image_path)
                                                    <img src="{{ Storage::url($ticket->event->image_path) }}" 
                                                         alt="{{ $ticket->event->title }}" 
                                                         class="w-16 h-16 object-cover rounded-lg">
                                                @else
                                                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                        <span class="text-white text-lg font-bold">
                                                            {{ substr($ticket->event->title, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @endif
                                                
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                        {{ $ticket->event->title }}
                                                    </h4>
                                                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                                                        📅 {{ $ticket->event->start_date->format('F j, Y \a\t g:i A') }}
                                                    </p>
                                                    <p class="text-gray-600 dark:text-gray-300 text-sm">
                                                        📍 {{ $ticket->event->location }}
                                                    </p>
                                                    <div class="flex items-center space-x-4 mt-2">
                                                        <span class="text-sm text-gray-500">
                                                            🎟️ {{ $ticket->quantity }} ticket(s)
                                                        </span>
                                                        <span class="text-sm font-mono text-blue-600">
                                                            {{ $ticket->ticket_code }}
                                                        </span>
                                                        @if($ticket->payment_status === 'paid')
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                ✅ Confirmed
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                ⏳ Pending
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col items-end space-y-2">
                                            @if($ticket->event->type === 'free')
                                                <span class="text-lg font-bold text-green-600">FREE</span>
                                            @else
                                                <span class="text-lg font-bold text-purple-600">
                                                    ${{ number_format($ticket->total_price * 1.15, 2) }}
                                                </span>
                                            @endif
                                            
                                            <a href="{{ route('events.show', $ticket->event) }}" 
                                               class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                                View Event
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($tickets->count() > 5)
                            <div class="mt-6 text-center">
                                <a href="#" class="text-blue-600 hover:text-blue-500 font-medium">
                                    View all {{ $tickets->count() }} tickets →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No tickets yet</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Start exploring events and get your first tickets!
                            </p>
                            <a href="{{ route('events.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                🎫 Browse Events
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Browse Events -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-6 text-white">
                    <h3 class="text-xl font-bold mb-2">🎭 Discover Events</h3>
                    <p class="text-blue-100 mb-4">Find amazing events happening near you</p>
                    <a href="{{ route('events.index') }}" 
                       class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition duration-200">
                        Browse All Events
                    </a>
                </div>

                <!-- Account Settings -->
                <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg p-6 text-white">
                    <h3 class="text-xl font-bold mb-2">⚙️ Account Settings</h3>
                    <p class="text-green-100 mb-4">Manage your profile and preferences</p>
                    <a href="{{ route('profile.edit') }}" 
                       class="bg-white text-green-600 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition duration-200">
                        Edit Profile
                    </a>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
