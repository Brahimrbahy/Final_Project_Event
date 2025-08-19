@extends('layouts.dashbord')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="client" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
            <x-client-header />

            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                💡 Recommended for You
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mt-1">
                                Events curated based on your interests and booking history
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <a href="{{ route('events.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                🎪 Browse All Events
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommended Events -->
            @if($recommendedEvents->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recommendedEvents as $event)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                            <!-- Event Image -->
                            <div class="relative h-48">
                                @if($event->image_path)
                                    <img src="{{ Storage::url($event->image_path) }}" 
                                         alt="{{ $event->title }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-white text-4xl font-bold">
                                            {{ substr($event->title, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Event Category Badge -->
                                @if($event->category)
                                    <div class="absolute top-3 left-3">
                                        <span class="bg-black bg-opacity-70 text-white px-3 py-1 rounded-full text-sm font-medium">
                                            {{ ucfirst($event->category) }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Price Badge -->
                                <div class="absolute top-3 right-3">
                                    @if($event->isFree())
                                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                            FREE
                                        </span>
                                    @else
                                        <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                            ${{ number_format($event->price, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Event Details -->
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                    {{ $event->title }}
                                </h3>
                                
                                <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                                    {{ $event->description }}
                                </p>

                                <!-- Event Info -->
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <span class="mr-2">📅</span>
                                        {{ $event->start_date->format('F j, Y') }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <span class="mr-2">⏰</span>
                                        {{ $event->start_date->format('g:i A') }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <span class="mr-2">📍</span>
                                        {{ $event->location }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                        <span class="mr-2">👥</span>
                                        {{ $event->organizer->name }}
                                    </div>
                                </div>

                                <!-- Availability -->
                                <div class="mb-4">
                                    @if($event->hasAvailableTickets())
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-green-600 dark:text-green-400 font-medium">
                                                ✅ {{ $event->available_tickets }} tickets available
                                            </span>
                                            <span class="text-gray-500 dark:text-gray-400">
                                                {{ $event->tickets_sold }} sold
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mt-2">
                                            <div class="bg-green-500 h-2 rounded-full" 
                                                 style="width: {{ ($event->tickets_sold / $event->max_attendees) * 100 }}%"></div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            🚫 Sold Out
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('events.show', $event) }}" 
                                       class="flex-1 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition duration-200">
                                        📋 View Details
                                    </a>
                                    
                                    @if($event->hasAvailableTickets())
                                        <a href="{{ route('client.booking-form', $event) }}" 
                                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium text-center transition duration-200">
                                            🎫 Book Now
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- More Recommendations -->
                <div class="mt-12 text-center">
                    <a href="{{ route('events.index') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition duration-200">
                        🔍 Explore More Events
                    </a>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-4xl">💡</span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            No Recommendations Yet
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-8 max-w-md mx-auto">
                            Start booking events to get personalized recommendations based on your interests and preferences.
                        </p>
                        
                        <!-- Popular Events -->
                        <div class="mb-8">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                🔥 Popular Events Right Now
                            </h4>
                            
                            @php
                                $popularEvents = \App\Models\Event::approved()
                                    ->upcoming()
                                    ->orderBy('tickets_sold', 'desc')
                                    ->with('organizer')
                                    ->take(3)
                                    ->get();
                            @endphp

                            @if($popularEvents->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                    @foreach($popularEvents as $event)
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                            <h5 class="font-semibold text-gray-900 dark:text-white mb-2">
                                                {{ $event->title }}
                                            </h5>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">
                                                📅 {{ $event->start_date->format('M j, Y') }}
                                            </p>
                                            <a href="{{ route('events.show', $event) }}" 
                                               class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                                View Event →
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <a href="{{ route('events.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-medium transition duration-200 inline-block">
                                🎪 Browse All Events
                            </a>
                            <div>
                                <a href="{{ route('client.dashboard') }}" 
                                   class="text-gray-600 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
                                    ← Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
@endsection
