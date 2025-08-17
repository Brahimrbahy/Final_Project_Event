<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $event->title }} - {{ config('app.name', 'Event Management') }}</title>

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

        /* Mobile menu toggle */
        .mobile-menu-hidden {
            display: none;
        }

        /* Event details styling */
        .event-detail-card {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 16px;
            overflow: hidden;
        }

        .event-image-container {
            position: relative;
            overflow: hidden;
        }

        .event-image-container::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
        }

        .organizer-badge {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .buy-now-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            transition: all 0.3s ease;
        }

        .buy-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#0f172a]">
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
    <main class="min-h-screen bg-[#0f172a] text-white">
        <!-- Back Button -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <button onclick="history.back()" class="flex items-center space-x-2 text-gray-300 hover:text-white transition-colors mb-6">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>Retour</span>
            </button>
        </div>

        <!-- Event Details Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Event Image and Details -->
                <div class="lg:col-span-2">
                    <div class="event-detail-card">
                        <!-- Event Image -->
                        @if($event->image_path)
                            <div class="event-image-container h-96 relative">
                                <img src="{{ Storage::url($event->image_path) }}"
                                     alt="{{ $event->title }}"
                                     class="w-full h-full object-cover">

                                <!-- Organizer Badge Overlay -->
                                <div class="absolute top-4 left-4">
                                    <div class="organizer-badge rounded-full px-4 py-2 flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                            <span class="text-white text-sm font-bold">{{ substr($event->organizer->name, 0, 1) }}</span>
                                        </div>
                                        <span class="text-white text-sm font-medium">{{ $event->organizer->name }}</span>
                                    </div>
                                </div>

                                <!-- Share and Favorite Icons -->
                                <div class="absolute top-4 right-4 flex space-x-2">
                                    <button class="w-10 h-10 bg-black bg-opacity-50 rounded-full flex items-center justify-center text-white hover:bg-opacity-75 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                        </svg>
                                    </button>
                                    <button class="w-10 h-10 bg-black bg-opacity-50 rounded-full flex items-center justify-center text-white hover:bg-opacity-75 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Event Content -->
                        <div class="p-8">
                            <!-- Event Title and Basic Info -->
                            <div class="mb-6">
                                <h1 class="text-4xl font-bold text-white mb-4">{{ $event->title }}</h1>

                                <!-- Location -->
                                <div class="flex items-center space-x-2 text-gray-300 mb-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $event->location }}</span>
                                </div>

                                <!-- Date -->
                                <div class="flex items-center space-x-2 text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Du {{ $event->start_date->format('j F Y') }} au {{ $event->end_date ? $event->end_date->format('j F Y') : $event->start_date->format('j F Y') }}</span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-8">
                                <p class="text-gray-300 leading-relaxed text-lg">{{ $event->description }}</p>
                            </div>

                            <!-- Event Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <!-- Category -->
                                <div class="bg-slate-800 rounded-lg p-4">
                                    <h3 class="text-white font-semibold mb-2">Catégorie</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $event->category }}
                                    </span>
                                </div>

                                <!-- Price -->
                                <div class="bg-slate-800 rounded-lg p-4">
                                    <h3 class="text-white font-semibold mb-2">Prix</h3>
                                    @if($event->type === 'free')
                                        <span class="text-green-400 font-bold text-lg">Gratuit</span>
                                    @else
                                        <span class="text-white font-bold text-lg">${{ number_format($event->price, 2) }}</span>
                                    @endif
                                </div>

                                <!-- Availability -->
                                @if($event->max_tickets)
                                <div class="bg-slate-800 rounded-lg p-4">
                                    <h3 class="text-white font-semibold mb-2">Disponibilité</h3>
                                    <span class="text-gray-300">{{ $event->max_tickets - $event->tickets_sold }} / {{ $event->max_tickets }} places</span>
                                </div>
                                @endif

                                <!-- Status -->
                                <div class="bg-slate-800 rounded-lg p-4">
                                    <h3 class="text-white font-semibold mb-2">Statut</h3>
                                    @if($event->start_date > now())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            À venir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            Terminé
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            @if($event->terms_conditions)
                                <div class="bg-slate-800 rounded-lg p-6">
                                    <h3 class="text-white font-semibold mb-4">Conditions générales</h3>
                                    <p class="text-gray-300 text-sm leading-relaxed">{{ $event->terms_conditions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Buy Now Section -->
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <!-- Buy Now Card -->
                        @if($event->start_date > now())
                            <div class="bg-white rounded-2xl p-8 shadow-2xl">
                                <!-- Price Display -->
                                <div class="text-center mb-6">
                                    @if($event->type === 'free')
                                        <div class="text-3xl font-bold text-green-600 mb-2">Gratuit</div>
                                        <div class="text-sm text-gray-500">Événement gratuit</div>
                                    @else
                                        <div class="text-3xl font-bold text-gray-900 mb-2">${{ number_format($event->price, 2) }}</div>
                                        <div class="text-sm text-gray-500">par billet</div>
                                    @endif
                                </div>

                                <!-- Availability Check -->
                                @if($event->max_tickets && $event->tickets_sold >= $event->max_tickets)
                                    <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-lg mb-6 text-center">
                                        <strong>Complet!</strong><br>
                                        <span class="text-sm">Aucun billet disponible</span>
                                    </div>
                                @else
                                    <!-- Buy Button -->
                                    @auth
                                        @if(Auth::user()->isClient())
                                            <a href="{{ route('tickets.select-quantity', $event) }}"
                                               class="buy-now-btn block w-full text-white px-8 py-4 rounded-xl font-semibold text-center text-lg mb-4">
                                                Buy now
                                            </a>
                                            <p class="text-xs text-gray-500 text-center mb-6">
                                                {{ $event->type === 'free' ? 'Réservation gratuite' : 'Sélectionnez la quantité et payez' }}
                                            </p>
                                        @else
                                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-6 py-4 rounded-lg mb-6 text-center">
                                                <span class="text-sm">Seuls les clients peuvent acheter des billets</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="space-y-3 mb-6">
                                            <p class="text-sm text-gray-600 text-center">Connectez-vous pour acheter des billets</p>
                                            <div class="flex flex-col space-y-2">
                                                <a href="{{ route('login') }}"
                                                   class="w-full bg-orange-500 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium text-center transition-colors">
                                                    Se connecter
                                                </a>
                                                <a href="{{ route('register.client') }}"
                                                   class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-medium text-center transition-colors">
                                                    Créer un compte
                                                </a>
                                            </div>
                                        </div>
                                    @endauth
                                @endif

                                <!-- Ticket Counter -->
                                @if($event->max_tickets)
                                    <div class="text-center text-sm text-gray-500">
                                        {{ $event->max_tickets - $event->tickets_sold }} billets restants
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Event Ended -->
                            <div class="bg-gray-100 rounded-2xl p-8 text-center">
                                <div class="text-gray-600 mb-4">
                                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Événement terminé</h3>
                                <p class="text-gray-600">Cet événement s'est terminé le {{ $event->start_date->format('j F Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript for Mobile Menu -->
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }
    </script>
</body>
</html>

