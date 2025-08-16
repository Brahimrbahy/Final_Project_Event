<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Event Image -->
                @if($event->image_path)
                    <div class="w-full h-64 md:h-80">
                        <img src="{{ Storage::url($event->image_path) }}" 
                             alt="{{ $event->title }}" 
                             class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6 md:p-8">
                    <!-- Event Header -->
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-6">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $event->category }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    {{ $event->type === 'free' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $event->type === 'free' ? 'Free Event' : '$' . number_format($event->price, 2) }}
                                </span>
                                @if($event->start_date > now())
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Upcoming
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        Completed
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $event->title }}</h1>
                            <p class="text-gray-600">Organized by {{ $event->organizer->name }}</p>
                        </div>

                        <!-- Ticket Purchase Section -->
                        @if($event->start_date > now())
                            <div class="mt-6 md:mt-0 md:ml-8">
                                <div class="bg-gray-50 rounded-lg p-6 min-w-[280px]">
                                    <div class="text-center">
                                        @if($event->type === 'free')
                                            <div class="text-2xl font-bold text-green-600 mb-2">Free</div>
                                        @else
                                            <div class="text-2xl font-bold text-purple-600 mb-2">${{ number_format($event->price, 2) }}</div>
                                            <div class="text-sm text-gray-500 mb-4">per ticket</div>
                                        @endif

                                        @if($event->max_tickets && $event->tickets_sold >= $event->max_tickets)
                                            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4">
                                                <strong>Sold Out!</strong> No tickets available.
                                            </div>
                                        @else
                                            @auth
                                                @if(Auth::user()->isClient())
                                                    <a href="{{ route('tickets.select-quantity', $event) }}"
                                                       class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium text-center transition duration-200">
                                                        {{ $event->type === 'free' ? '🎫 Get Now' : '🎫 Get Now' }}
                                                    </a>
                                                    <p class="text-xs text-gray-500 mt-2 text-center">
                                                        {{ $event->type === 'free' ? 'Free tickets' : 'Select quantity and pay' }}
                                                    </p>
                                                @else
                                                    <div class="bg-yellow-100 border border-yellow-300 text-yellow-700 px-4 py-3 rounded mb-4">
                                                        Only clients can purchase tickets.
                                                    </div>
                                                @endif
                                            @else
                                                <div class="space-y-3">
                                                    <p class="text-sm text-gray-600">Sign in to purchase tickets</p>
                                                    <div class="flex flex-col space-y-2">
                                                        <a href="{{ route('login') }}" 
                                                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium text-center">
                                                            Sign In
                                                        </a>
                                                        <a href="{{ route('register') }}" 
                                                           class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-md font-medium text-center">
                                                            Create Account
                                                        </a>
                                                    </div>
                                                </div>
                                            @endauth
                                        @endif

                                        @if($event->max_tickets)
                                            <div class="mt-4 text-sm text-gray-500">
                                                {{ $event->max_tickets - $event->tickets_sold }} of {{ $event->max_tickets }} tickets available
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Event Details -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="md:col-span-2">
                            <!-- Description -->
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">About This Event</h2>
                                <div class="prose prose-gray max-w-none">
                                    <p class="text-gray-700 leading-relaxed">{{ $event->description }}</p>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            @if($event->terms_conditions)
                                <div class="mb-8">
                                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Terms & Conditions</h2>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-700 text-sm">{{ $event->terms_conditions }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Event Info Sidebar -->
                        <div class="space-y-6">
                            <!-- Date & Time -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                    </svg>
                                    Date & Time
                                </h3>
                                <p class="text-gray-700">{{ $event->start_date->format('l, F j, Y') }}</p>
                                <p class="text-gray-700">{{ $event->start_date->format('g:i A') }}</p>
                            </div>

                            <!-- Location -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Location
                                </h3>
                                <p class="text-gray-700 font-medium">{{ $event->location }}</p>
                                @if($event->address)
                                    <p class="text-gray-600 text-sm mt-1">{{ $event->address }}</p>
                                @endif
                            </div>

                            <!-- Organizer -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Organizer
                                </h3>
                                <p class="text-gray-700 font-medium">{{ $event->organizer->name }}</p>
                                @if($event->organizer->organizerProfile && $event->organizer->organizerProfile->company_name)
                                    <p class="text-gray-600 text-sm">{{ $event->organizer->organizerProfile->company_name }}</p>
                                @endif
                            </div>

                            <!-- Share Event -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 mb-3">Share This Event</h3>
                                <div class="flex space-x-2">
                                    <button onclick="shareEvent()" 
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-sm">
                                        Share
                                    </button>
                                    <button onclick="copyEventLink()" 
                                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-2 rounded text-sm">
                                        Copy Link
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('events.index') }}" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function shareEvent() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $event->title }}',
                    text: '{{ Str::limit($event->description, 100) }}',
                    url: window.location.href
                });
            } else {
                copyEventLink();
            }
        }

        function copyEventLink() {
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Event link copied to clipboard!');
            });
        }
    </script>
</x-app-layout>
