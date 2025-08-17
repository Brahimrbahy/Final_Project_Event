<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Event Management') }} - Parcourir les Événements</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    <style>
        .event-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        .search-focus:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Line clamp utility for text truncation */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#1a2332;]">
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
                                    <option value="Concerts" {{ request('category') === 'Concerts' ? 'selected' : '' }}> Concerts</option>
                                    <option value="Festivals" {{ request('category') === 'Festivals' ? 'selected' : '' }}> Festivals</option>
                                    <option value="Theatre" {{ request('category') === 'Theatre' ? 'selected' : '' }}> Théâtre</option>
                                    <option value="Sports" {{ request('category') === 'Sports' ? 'selected' : '' }}> Sports</option>
                                    <option value="Cinema" {{ request('category') === 'Cinema' ? 'selected' : '' }}> Cinéma</option>
                                    <option value="Business" {{ request('category') === 'Business' ? 'selected' : '' }}>Business</option>
                                    <option value="Technology" {{ request('category') === 'Technology' ? 'selected' : '' }}> Technology</option>
                                    <option value="Arts & Culture" {{ request('category') === 'Arts & Culture' ? 'selected' : '' }}> Arts & Culture</option>
                                    <option value="Education" {{ request('category') === 'Education' ? 'selected' : '' }}> Education</option>
                                    <option value="Food & Drink" {{ request('category') === 'Food & Drink' ? 'selected' : '' }}> Food & Drink</option>
                                    <option value="Health & Wellness" {{ request('category') === 'Health & Wellness' ? 'selected' : '' }}> Health & Wellness</option>
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

    <!-- Main Content -->
    <main class="bg-[#1a2332] min-h-screen">
        <!-- Page Header -->
        <section class="py-8 bg-[#1a2332]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-white mb-4">Parcourir les Événements</h1>
                    <p class="text-lg text-gray-300">Découvrez tous les événements disponibles</p>
                </div>
            </div>
        </section>

   

        <!-- Events Grid Section -->
        <section class="py-8 bg-[#1a2332]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if($events->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($events as $event)
                            <div class="bg-slate-800 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 event-card">
                                <!-- Event Image -->
                                <div class="relative">
                                    @if($event->image_path)
                                        <img src="{{ Storage::url($event->image_path) }}"
                                             alt="{{ $event->title }}"
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600 text-white">
                                            {{ $event->category }}
                                        </span>
                                    </div>

                                    <!-- Price Badge -->
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $event->type === 'free' ? 'bg-green-600 text-white' : 'bg-purple-600 text-white' }}">
                                            {{ $event->type === 'free' ? 'Gratuit' : number_format($event->price, 2) . ' €' }}
                                        </span>
                                    </div>

                                    <!-- Sold Out Overlay -->
                                    @if($event->max_tickets && $event->tickets_sold >= $event->max_tickets)
                                        <div class="absolute inset-0 bg-black bg-opacity-60 flex items-center justify-center">
                                            <span class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold text-lg">
                                                COMPLET
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Event Content -->
                                <div class="p-6">
                                    <!-- Event Title -->
                                    <h3 class="text-lg font-semibold text-white mb-2 line-clamp-2">
                                        <a href="{{ route('events.show', $event) }}" class="hover:text-blue-400 transition-colors">
                                            {{ $event->title }}
                                        </a>
                                    </h3>

                                    <!-- Event Description -->
                                    <p class="text-gray-300 text-sm mb-4 line-clamp-2">
                                        {{ Str::limit($event->description, 100) }}
                                    </p>

                                    <!-- Event Details -->
                                    <div class="space-y-2 mb-4">
                                        <!-- Date -->
                                        <div class="flex items-center text-sm text-gray-300">
                                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ $event->start_date->format('d M Y à H:i') }}
                                        </div>

                                        <!-- Location -->
                                        <div class="flex items-center text-sm text-gray-300">
                                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ Str::limit($event->location, 30) }}
                                        </div>

                                        <!-- Organizer -->
                                        <div class="flex items-center text-sm text-gray-300">
                                            <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            par {{ $event->organizer->name }}
                                        </div>

                                        <!-- Tickets Available -->
                                        @if($event->max_tickets)
                                            <div class="flex items-center text-sm text-gray-300">
                                                <svg class="w-4 h-4 mr-2 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z"></path>
                                                </svg>
                                                {{ $event->max_tickets - $event->tickets_sold }} places disponibles
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('events.show', $event) }}"
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2">
                                            <span>Voir Détails</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>

                                        @if($event->start_date > now())
                                            <span class="text-xs text-green-400 font-medium">À venir</span>
                                        @else
                                            <span class="text-xs text-gray-500 font-medium">Terminé</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8 flex justify-center">
                        <div class="bg-slate-800 rounded-lg p-4">
                            {{ $events->withQueryString()->links('pagination::tailwind') }}
                        </div>
                    </div>
                @else
                    <!-- No Events Found -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-xl font-medium text-white mb-2">Aucun événement trouvé</h3>
                        <p class="text-gray-300 mb-6">
                            @if(request()->hasAny(['search', 'category', 'type']))
                                Essayez d'ajuster vos critères de recherche ou
                                <a href="{{ route('events.index') }}" class="text-blue-400 hover:text-blue-300 underline">effacez tous les filtres</a>.
                            @else
                                Il n'y a aucun événement disponible pour le moment. Revenez plus tard !
                            @endif
                        </p>

                        @auth
                            @if(Auth::user()->isOrganizer() && Auth::user()->is_approved)
                                <div class="mt-6">
                                    <a href="{{ route('organizer.events.create') }}"
                                       class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Créer Votre Premier Événement
                                    </a>
                                </div>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold mb-4">
                        <span class="text-red-500">G</span><span class="text-yellow-400">u</span><span class="text-green-500">i</span><span class="text-blue-500">c</span><span class="text-white">het</span>
                    </h3>
                    <p class="text-gray-300 mb-4">
                        Votre plateforme de billetterie de confiance pour découvrir et réserver des événements exceptionnels.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Liens Rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('welcome') }}" class="text-gray-300 hover:text-white transition-colors">Accueil</a></li>
                        <li><a href="{{ route('events.index') }}" class="text-gray-300 hover:text-white transition-colors">Événements</a></li>
                        <li><a href="{{ route('register.organizer') }}" class="text-gray-300 hover:text-white transition-colors">Devenir Organisateur</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">À Propos</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Centre d'Aide</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Contact</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Conditions d'Utilisation</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Politique de Confidentialité</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">&copy; {{ date('Y') }} Guichet. Tous droits réservés.</p>
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

        // Auto-submit form when filters change (optional)
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelects = document.querySelectorAll('#type, #sort, #direction, #start_date');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    // Uncomment the line below to auto-submit on filter change
                    // this.form.submit();
                });
            });
        });
    </script>
</body>
</html>
