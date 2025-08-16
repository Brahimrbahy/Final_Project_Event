<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('events.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search Events</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       placeholder="Search by title, description, or location..."
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category" id="category" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Type Filter -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Event Type</label>
                                <select name="type" id="type" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Types</option>
                                    <option value="free" {{ request('type') == 'free' ? 'selected' : '' }}>Free Events</option>
                                    <option value="paid" {{ request('type') == 'paid' ? 'selected' : '' }}>Paid Events</option>
                                </select>
                            </div>

                            <!-- Sort -->
                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                                <select name="sort" id="sort" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="start_date" {{ request('sort') == 'start_date' ? 'selected' : '' }}>Date</option>
                                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title</option>
                                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Recently Added</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex space-x-2">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                    Apply Filters
                                </button>
                                <a href="{{ route('events.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                                    Clear Filters
                                </a>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $events->total() }} events found
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Events Grid -->
            @if($events->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($events as $event)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow duration-200">
                            <!-- Event Image -->
                            <div class="aspect-w-16 aspect-h-9">
                                @if($event->image_path)
                                    <img src="{{ Storage::url($event->image_path) }}" 
                                         alt="{{ $event->title }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Event Content -->
                            <div class="p-6">
                                <!-- Category Badge -->
                                <div class="flex items-center justify-between mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $event->category }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $event->type === 'free' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $event->type === 'free' ? 'Free' : '$' . number_format($event->price, 2) }}
                                    </span>
                                </div>

                                <!-- Event Title -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('events.show', $event) }}" class="hover:text-blue-600">
                                        {{ $event->title }}
                                    </a>
                                </h3>

                                <!-- Event Description -->
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ Str::limit($event->description, 100) }}
                                </p>

                                <!-- Event Details -->
                                <div class="space-y-2 mb-4">
                                    <!-- Date -->
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $event->start_date->format('M d, Y \a\t g:i A') }}
                                    </div>

                                    <!-- Location -->
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $event->location }}
                                    </div>

                                    <!-- Organizer -->
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        by {{ $event->organizer->name }}
                                    </div>

                                    <!-- Tickets Available -->
                                    @if($event->max_tickets)
                                        <div class="flex items-center text-sm text-gray-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z"></path>
                                            </svg>
                                            {{ $event->max_tickets - $event->tickets_sold }} tickets available
                                        </div>
                                    @endif
                                </div>

                                <!-- Action Button -->
                                <div class="flex items-center justify-between">
                                    <a href="{{ route('events.show', $event) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                        View Details
                                    </a>
                                    
                                    @if($event->start_date > now())
                                        <span class="text-xs text-green-600 font-medium">Upcoming</span>
                                    @else
                                        <span class="text-xs text-gray-500 font-medium">Completed</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $events->withQueryString()->links() }}
                </div>
            @else
                <!-- No Events Found -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No events found</h3>
                        <p class="mt-2 text-gray-500">
                            @if(request()->hasAny(['search', 'category', 'type']))
                                Try adjusting your search criteria or 
                                <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-500">clear all filters</a>.
                            @else
                                There are no events available at the moment. Check back later!
                            @endif
                        </p>
                        
                        @auth
                            @if(Auth::user()->isOrganizer() && Auth::user()->is_approved)
                                <div class="mt-6">
                                    <a href="{{ route('organizer.events.create') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create Your First Event
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
