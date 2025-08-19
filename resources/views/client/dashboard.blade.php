@extends('layouts.dashbord')

@section('content')
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="client" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 lg:ml-0">
            <x-client-header />

            <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
                
                <!-- Welcome Section with enhanced design -->
                <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 border border-blue-100 shadow-lg rounded-2xl mb-8 overflow-hidden">
                    <div class="p-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-3xl font-bold text-gray-900 mb-3">
                                    Welcome back, {{ Auth::user()->name }}! 👋
                                </h3>
                                <p class="text-gray-600 text-lg">
                                    Manage your tickets and discover amazing events
                                </p>
                            </div>
                            <div class="hidden md:block">
                                <a href="{{ route('events.index') }}" 
                                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg flex items-center space-x-2">
                                    <span>🎫</span>
                                    <span>Browse Events</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats with modern card design -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                    <!-- Total Tickets -->
                    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Tickets</p>
                                    <p class="text-4xl font-bold text-gray-900 mb-1">
                                        {{ Auth::user()->tickets ? Auth::user()->tickets()->sum('quantity') : 0 }}
                                    </p>
                                    <p class="text-sm text-gray-500">All time</p>
                                </div>
                                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Upcoming Events</p>
                                    <p class="text-4xl font-bold text-gray-900 mb-1">
                                        {{ Auth::user()->tickets ? Auth::user()->tickets()->whereHas('event', function($q) { $q->where('start_date', '>', now()); })->count() : 0 }}
                                    </p>
                                    <p class="text-sm text-gray-500">Events ahead</p>
                                </div>
                                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Spent -->
                    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300">
                        <div class="p-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">Total Spent</p>
                                    <p class="text-4xl font-bold text-gray-900 mb-1">
                                        ${{ number_format((Auth::user()->tickets ? Auth::user()->tickets()->where('payment_status', 'paid')->sum('total_price') : 0) * 1.15, 2) }}
                                    </p>
                                    <p class="text-sm text-gray-500">Including fees</p>
                                </div>
                                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Tickets Section with enhanced design -->
                <div class="bg-white shadow-lg rounded-2xl border border-gray-100 mb-10">
                    <div class="p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center space-x-3">
                                <span>🎫</span>
                                <span>My Tickets</span>
                            </h3>
                            <a href="{{ route('events.index') }}" 
                               class="text-blue-600 hover:text-blue-700 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors">
                                Browse more events →
                            </a>
                        </div>

                        @php
                            $tickets = Auth::user()->tickets ? Auth::user()->tickets()->with(['event', 'event.organizer'])->orderBy('created_at', 'desc')->get() : collect();
                        @endphp

                        @if($tickets->count() > 0)
                            <div class="space-y-6">
                                @foreach($tickets->take(5) as $ticket)
                                    <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-blue-200 transition-all duration-300 bg-gray-50">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-start space-x-6">
                                                    @if($ticket->event->image_path)
                                                        <img src="{{ Storage::url($ticket->event->image_path) }}" 
                                                             alt="{{ $ticket->event->title }}" 
                                                             class="w-20 h-20 object-cover rounded-xl shadow-md">
                                                    @else
                                                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center shadow-md">
                                                            <span class="text-white text-xl font-bold">
                                                                {{ substr($ticket->event->title, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="flex-1">
                                                        <h4 class="text-xl font-bold text-gray-900 mb-3">
                                                            {{ $ticket->event->title }}
                                                        </h4>
                                                        <div class="space-y-2 mb-4">
                                                            <p class="text-gray-600 text-sm flex items-center space-x-2">
                                                                <span>📅</span>
                                                                <span>{{ $ticket->event->start_date->format('F j, Y \a\t g:i A') }}</span>
                                                            </p>
                                                            <p class="text-gray-600 text-sm flex items-center space-x-2">
                                                                <span>📍</span>
                                                                <span>{{ $ticket->event->location }}</span>
                                                            </p>
                                                        </div>
                                                        <div class="flex items-center space-x-4">
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                                                🎟️ {{ $ticket->quantity }} ticket(s)
                                                            </span>
                                                            <span class="text-sm font-mono text-blue-600 bg-blue-50 px-3 py-1 rounded-lg border border-blue-200">
                                                                {{ $ticket->ticket_code }}
                                                            </span>
                                                            @if($ticket->payment_status === 'paid')
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800 border border-green-200">
                                                                    ✅ Confirmed
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                                    ⏳ Pending
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex flex-col items-end space-y-3 ml-6">
                                                @if($ticket->event->type === 'free')
                                                    <span class="text-2xl font-bold text-green-600 bg-green-50 px-4 py-2 rounded-xl border border-green-200">
                                                        FREE
                                                    </span>
                                                @else
                                                    <span class="text-2xl font-bold text-purple-600 bg-purple-50 px-4 py-2 rounded-xl border border-purple-200">
                                                        ${{ number_format($ticket->total_price * 1.15, 2) }}
                                                    </span>
                                                @endif
                                                
                                                <a href="{{ route('events.show', $ticket->event) }}" 
                                                   class="text-blue-600 hover:text-blue-700 text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-50 border border-blue-200 transition-colors">
                                                    View Event
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if($tickets->count() > 5)
                                <div class="mt-8 text-center">
                                    <a href="#" class="inline-flex items-center px-6 py-3 text-blue-600 hover:text-blue-700 font-semibold bg-blue-50 hover:bg-blue-100 rounded-xl border border-blue-200 transition-colors">
                                        View all {{ $tickets->count() }} tickets →
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-16">
                                <div class="w-32 h-32 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">No tickets yet</h3>
                                <p class="text-gray-600 mb-8 text-lg">
                                    Start exploring events and get your first tickets!
                                </p>
                                <a href="{{ route('events.index') }}" 
                                   class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg inline-flex items-center space-x-2">
                                    <span>🎫</span>
                                    <span>Browse Events</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions with enhanced gradient design -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Browse Events -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 via-blue-600 to-purple-600 rounded-2xl p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="relative z-10">
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="text-3xl">🎭</span>
                                <h3 class="text-2xl font-bold">Discover Events</h3>
                            </div>
                            <p class="text-blue-100 mb-6 text-lg">Find amazing events happening near you</p>
                            <a href="{{ route('events.index') }}" 
                               class="bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 inline-flex items-center space-x-2 shadow-lg">
                                <span>Browse All Events</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                        <!-- Decorative elements -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -mr-16 -mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white bg-opacity-10 rounded-full -ml-12 -mb-12"></div>
                    </div>

                    <!-- Account Settings -->
                    <div class="relative overflow-hidden bg-gradient-to-br from-green-500 via-green-600 to-teal-600 rounded-2xl p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                        <div class="relative z-10">
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="text-3xl">⚙️</span>
                                <h3 class="text-2xl font-bold">Account Settings</h3>
                            </div>
                            <p class="text-green-100 mb-6 text-lg">Manage your profile and preferences</p>
                            <a href="{{ route('profile.edit') }}" 
                               class="bg-white text-green-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 inline-flex items-center space-x-2 shadow-lg">
                                <span>Edit Profile</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                        <!-- Decorative elements -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -mr-16 -mt-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white bg-opacity-10 rounded-full -ml-12 -mb-12"></div>
                    </div>
                </div>

                <!-- Mobile Browse Events Button -->
                <div class="md:hidden mt-8">
                    <a href="{{ route('events.index') }}" 
                       class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center space-x-2 shadow-lg">
                        <span>🎫</span>
                        <span>Browse Events</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection