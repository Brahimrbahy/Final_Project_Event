<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Event Management') }} - Discover Amazing Events</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Styles -->
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .category-card:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        .search-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Three-Line Navigation Structure -->
    <nav class="sticky top-0 z-50" style="background-color: #1a2332;">
        <!-- Line 1: Top Navigation (Logo + Auth) -->
        <div class="border-b border-slate-600">
            <div class="max-w-full px-6">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo Section -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('welcome') }}" class="flex items-center">
                            <h1 class="text-3xl font-bold text-white tracking-wide">
                                <span class="text-red-500">G</span><span class="text-yellow-400">u</span><span class="text-green-500">i</span><span class="text-blue-500">c</span><span class="text-white">het</span>
                            </h1>
                        </a>
                    </div>

                    <!-- Authentication Section -->
                    <div class="flex items-center space-x-3">
                        @auth
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">Admin Dashboard</a>
                            @elseif(Auth::user()->isOrganizer())
                                <a href="{{ route('organizer.dashboard') }}" class="text-white hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">My Dashboard</a>
                            @else
                                <a href="{{ route('client.dashboard') }}" class="text-white hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">My Tickets</a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-white hover:text-red-400 px-3 py-2 text-sm font-medium transition-colors">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-white hover:text-blue-400 px-4 py-2 text-sm font-medium transition-colors border border-slate-600 rounded-lg hover:border-blue-400">Login</a>
                            <a href="{{ route('register.client') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">Register</a>
                        @endauth

                        <!-- Mobile menu button -->
                        <div class="lg:hidden">
                            <button type="button" class="text-white hover:text-blue-400 focus:outline-none focus:text-blue-400 ml-4" onclick="toggleMobileMenu()">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Line 2: Search Bar with Category Filter -->
        <div class="border-b border-slate-600">
            <div class="max-w-full px-6">
                <div class="flex justify-center items-center h-16">
                    <div class="w-full max-w-4xl">
                        <form action="{{ route('events.index') }}" method="GET" class="flex items-center space-x-3">
                            <!-- Category Filter Dropdown -->
                            <div class="relative">
                                <select name="category" class="bg-slate-700 text-white border border-slate-600 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                    <option value="">Toutes catégories</option>
                                    <option value="Concerts" {{ request('category') === 'Concerts' ? 'selected' : '' }}>🎵 Concerts</option>
                                    <option value="Festivals" {{ request('category') === 'Festivals' ? 'selected' : '' }}>🎪 Festivals</option>
                                    <option value="Theatre" {{ request('category') === 'Theatre' ? 'selected' : '' }}>🎭 Théâtre</option>
                                    <option value="Sports" {{ request('category') === 'Sports' ? 'selected' : '' }}>⚽ Sports</option>
                                    <option value="Cinema" {{ request('category') === 'Cinema' ? 'selected' : '' }}>🎬 Cinéma</option>
                                    <option value="Business" {{ request('category') === 'Business' ? 'selected' : '' }}>💼 Business</option>
                                    <option value="Technology" {{ request('category') === 'Technology' ? 'selected' : '' }}>💻 Technology</option>
                                    <option value="Arts & Culture" {{ request('category') === 'Arts & Culture' ? 'selected' : '' }}>🎨 Arts & Culture</option>
                                    <option value="Education" {{ request('category') === 'Education' ? 'selected' : '' }}>📚 Education</option>
                                    <option value="Food & Drink" {{ request('category') === 'Food & Drink' ? 'selected' : '' }}>🍽️ Food & Drink</option>
                                    <option value="Health & Wellness" {{ request('category') === 'Health & Wellness' ? 'selected' : '' }}>🏥 Health & Wellness</option>
                                </select>
                            </div>

                            <!-- Search Input -->
                            <div class="relative flex-1">
                                <input type="text"
                                       name="search"
                                       placeholder="Cherchez ce que vous voulez"
                                       class="w-full pl-4 pr-12 py-3 bg-slate-700 text-white placeholder-gray-300 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm"
                                       value="{{ request('search') }}">
                                <button type="submit" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <svg class="w-5 h-5 text-gray-300 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Line 3: Category Navigation Links -->
        <div>
            <div class="max-w-full px-6">
                <div class="flex items-center justify-center space-x-6 h-14 overflow-x-auto">
                    <a href="{{ route('events.index', ['category' => 'Concerts']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Concerts' ? 'text-blue-400' : '' }}">
                        <span>🎵</span>
                        <span>Concerts</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Festivals']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Festivals' ? 'text-blue-400' : '' }}">
                        <span>🎪</span>
                        <span>Festivals</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Theatre']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Theatre' ? 'text-blue-400' : '' }}">
                        <span>🎭</span>
                        <span>Théâtre</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Sports']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Sports' ? 'text-blue-400' : '' }}">
                        <span>⚽</span>
                        <span>Sports</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Cinema']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Cinema' ? 'text-blue-400' : '' }}">
                        <span>🎬</span>
                        <span>Cinéma</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Business']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Business' ? 'text-blue-400' : '' }}">
                        <span>💼</span>
                        <span>Business</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Technology']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Technology' ? 'text-blue-400' : '' }}">
                        <span>💻</span>
                        <span>Technology</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Arts & Culture']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Arts & Culture' ? 'text-blue-400' : '' }}">
                        <span>🎨</span>
                        <span>Arts & Culture</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Education']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Education' ? 'text-blue-400' : '' }}">
                        <span>📚</span>
                        <span>Education</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Food & Drink']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Food & Drink' ? 'text-blue-400' : '' }}">
                        <span>🍽️</span>
                        <span>Food & Drink</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Health & Wellness']) }}" class="flex items-center space-x-2 text-white hover:text-blue-400 text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Health & Wellness' ? 'text-blue-400' : '' }}">
                        <span>🏥</span>
                        <span>Health & Wellness</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="lg:hidden hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3" style="background-color: #1e293b;">
                <!-- Mobile Search with Category -->
                <form action="{{ route('events.index') }}" method="GET" class="mb-3 space-y-3">
                    <select name="category" class="w-full bg-slate-700 text-white border border-slate-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes catégories</option>
                        <option value="Concerts" {{ request('category') === 'Concerts' ? 'selected' : '' }}>🎵 Concerts</option>
                        <option value="Festivals" {{ request('category') === 'Festivals' ? 'selected' : '' }}>🎪 Festivals</option>
                        <option value="Theatre" {{ request('category') === 'Theatre' ? 'selected' : '' }}>🎭 Théâtre</option>
                        <option value="Sports" {{ request('category') === 'Sports' ? 'selected' : '' }}>⚽ Sports</option>
                        <option value="Cinema" {{ request('category') === 'Cinema' ? 'selected' : '' }}>🎬 Cinéma</option>
                        <option value="Business" {{ request('category') === 'Business' ? 'selected' : '' }}>💼 Business</option>
                        <option value="Technology" {{ request('category') === 'Technology' ? 'selected' : '' }}>💻 Technology</option>
                        <option value="Arts & Culture" {{ request('category') === 'Arts & Culture' ? 'selected' : '' }}>🎨 Arts & Culture</option>
                        <option value="Education" {{ request('category') === 'Education' ? 'selected' : '' }}>📚 Education</option>
                        <option value="Food & Drink" {{ request('category') === 'Food & Drink' ? 'selected' : '' }}>🍽️ Food & Drink</option>
                        <option value="Health & Wellness" {{ request('category') === 'Health & Wellness' ? 'selected' : '' }}>🏥 Health & Wellness</option>
                    </select>
                    <input type="text" name="search" placeholder="Cherchez ce que vous voulez..."
                           class="w-full px-3 py-2 bg-slate-700 text-white placeholder-gray-300 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                           value="{{ request('search') }}">
                    <button type="submit" class="w-full bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700">Rechercher</button>
                </form>

                <!-- Mobile Auth Links -->
                @auth
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-white hover:text-blue-400 hover:bg-slate-700 rounded-lg">Admin Dashboard</a>
                    @elseif(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}" class="block px-3 py-2 text-white hover:text-blue-400 hover:bg-slate-700 rounded-lg">My Dashboard</a>
                    @else
                        <a href="{{ route('client.dashboard') }}" class="block px-3 py-2 text-white hover:text-blue-400 hover:bg-slate-700 rounded-lg">My Tickets</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-white hover:text-red-400 hover:bg-slate-700 rounded-lg">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-white hover:text-blue-400 hover:bg-slate-700 rounded-lg">Login</a>
                    <a href="{{ route('register.client') }}" class="block px-3 py-2 bg-blue-600 text-white rounded-lg text-center hover:bg-blue-700">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Discover Amazing Events
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                Find and book tickets for concerts, conferences, workshops, and more
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('events.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                    🎫 Browse Events
                </a>
                <a href="{{ route('register.organizer') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-200">
                    🚀 Become an Organizer
                </a>
            </div>
        </div>
    </section>

    <!-- Category Navigation -->
    <section class="bg-white py-8 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-4">
                @php
                    $categories = ['All', 'Music', 'Business', 'Technology', 'Education', 'Sports', 'Arts', 'Food', 'Health'];
                @endphp
                
                @foreach($categories as $category)
                    <a href="{{ route('events.index', ['category' => $category === 'All' ? '' : $category]) }}" 
                       class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition duration-200 {{ request('category') === $category || (request('category') === null && $category === 'All') ? 'bg-blue-600 text-white border-blue-600' : '' }}">
                        {{ $category }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main>
        <!-- Category Carousel -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Explore by Category</h2>
                    <p class="text-lg text-gray-600">Find events that match your interests</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @php
                        $featuredCategories = [
                            ['name' => 'Music', 'icon' => '🎵', 'color' => 'from-pink-500 to-rose-500', 'description' => 'Concerts & Festivals'],
                            ['name' => 'Business', 'icon' => '💼', 'color' => 'from-blue-500 to-indigo-500', 'description' => 'Conferences & Networking'],
                            ['name' => 'Technology', 'icon' => '💻', 'color' => 'from-green-500 to-teal-500', 'description' => 'Tech Talks & Workshops'],
                            ['name' => 'Education', 'icon' => '📚', 'color' => 'from-purple-500 to-violet-500', 'description' => 'Learning & Development']
                        ];
                    @endphp
                    
                    @foreach($featuredCategories as $category)
                        <a href="{{ route('events.index', ['category' => $category['name']]) }}" 
                           class="category-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                            <div class="bg-gradient-to-br {{ $category['color'] }} h-32 flex items-center justify-center">
                                <span class="text-4xl">{{ $category['icon'] }}</span>
                            </div>
                            <div class="p-4 text-center">
                                <h3 class="font-semibold text-gray-900">{{ $category['name'] }}</h3>
                                <p class="text-sm text-gray-600">{{ $category['description'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Featured Events Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-12">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Featured Events</h2>
                        <p class="text-lg text-gray-600">Don't miss these amazing upcoming events</p>
                    </div>
                    <a href="{{ route('events.index') }}" class="hidden md:inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                        View All Events
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                
                @php
                    $featuredEvents = \App\Models\Event::approved()
                        ->upcoming()
                        ->with(['organizer'])
                        ->orderBy('created_at', 'desc')
                        ->take(8)
                        ->get();
                @endphp
                
                @if($featuredEvents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        @foreach($featuredEvents as $event)
                            <div class="event-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                                <!-- Event Image -->
                                <div class="relative h-48 bg-gradient-to-br from-blue-500 to-purple-600">
                                    @if($event->image_path)
                                        <img src="{{ Storage::url($event->image_path) }}" 
                                             alt="{{ $event->title }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="text-white text-4xl font-bold">
                                                {{ substr($event->title, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <!-- Sold Out Badge -->
                                    @if($event->max_tickets && $event->tickets_sold >= $event->max_tickets)
                                        <div class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            Sold Out
                                        </div>
                                    @endif
                                    
                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                                        {{ $event->category }}
                                    </div>
                                </div>
                                
                                <!-- Event Details -->
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $event->title }}</h3>
                                    
                                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $event->start_date->format('M j, Y') }}
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ Str::limit($event->location, 20) }}
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <div>
                                            @if($event->type === 'free')
                                                <span class="text-lg font-bold text-green-600">FREE</span>
                                            @else
                                                <span class="text-lg font-bold text-purple-600">${{ number_format($event->price, 2) }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('events.show', $event) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- See More Button -->
                    <div class="text-center">
                        <a href="{{ route('events.index') }}" 
                           class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-medium transition duration-200">
                            See More Events
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No events available</h3>
                        <p class="text-gray-600 mb-6">Be the first to create an amazing event!</p>
                        <a href="{{ route('register.organizer') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                            Become an Organizer
                        </a>
                    </div>
                @endif
            </div>
        </section>

        <!-- Become an Organizer Section -->
        <section id="become-organizer" class="py-16 bg-gradient-to-r from-purple-600 to-blue-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold mb-6">
                            Ready to Create Amazing Events?
                        </h2>
                        <p class="text-xl mb-8 text-purple-100">
                            Join thousands of organizers who trust our platform to manage their events.
                            From small workshops to large conferences, we've got you covered.
                        </p>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-lg">Easy event creation and management</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-lg">Secure payment processing with Stripe</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-lg">Real-time analytics and reporting</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-lg">85% revenue share (only 15% platform fee)</span>
                            </div>
                        </div>

                        <a href="{{ route('register.organizer') }}"
                           class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200 inline-block">
                            🚀 Start Creating Events
                        </a>
                    </div>

                    <div class="hidden lg:block">
                        <div class="bg-white bg-opacity-10 rounded-2xl p-8 backdrop-blur-sm">
                            <div class="space-y-6">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                        <span class="text-2xl">📊</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">Analytics Dashboard</h4>
                                        <p class="text-purple-100 text-sm">Track your event performance</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                        <span class="text-2xl">💳</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">Secure Payments</h4>
                                        <p class="text-purple-100 text-sm">Stripe integration for safe transactions</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                        <span class="text-2xl">📧</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold">Email Notifications</h4>
                                        <p class="text-purple-100 text-sm">Automated attendee communications</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Trusted by Event Organizers</h2>
                    <p class="text-lg text-gray-600">Join our growing community of successful event creators</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @php
                        $stats = [
                            ['number' => \App\Models\Event::count(), 'label' => 'Events Created', 'icon' => '🎪'],
                            ['number' => \App\Models\Ticket::sum('quantity') ?: 0, 'label' => 'Tickets Sold', 'icon' => '🎫'],
                            ['number' => \App\Models\User::where('role', 'organizer')->count(), 'label' => 'Organizers', 'icon' => '👥'],
                            ['number' => \App\Models\User::where('role', 'client')->count(), 'label' => 'Happy Clients', 'icon' => '😊']
                        ];
                    @endphp

                    @foreach($stats as $stat)
                        <div class="text-center">
                            <div class="text-4xl mb-2">{{ $stat['icon'] }}</div>
                            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stat['number']) }}+</div>
                            <div class="text-gray-600">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">E</span>
                        </div>
                        <span class="text-xl font-bold">EventHub</span>
                    </div>
                    <p class="text-gray-300 mb-4 max-w-md">
                        The ultimate platform for discovering and creating amazing events.
                        Connect with your community through unforgettable experiences.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('events.index') }}" class="text-gray-300 hover:text-white transition duration-200">Browse Events</a></li>
                        <li><a href="{{ route('register') }}" class="text-gray-300 hover:text-white transition duration-200">Create Account</a></li>
                        <li><a href="#become-organizer" class="text-gray-300 hover:text-white transition duration-200">Become Organizer</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Help Center</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Contact Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition duration-200">FAQ</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    © {{ date('Y') }} EventHub. All rights reserved. Made with ❤️ for amazing events.
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobile-menu');
            const button = event.target.closest('button');

            if (!menu.contains(event.target) && !button) {
                menu.classList.add('hidden');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
