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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
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
                                <span class="text-[#48ff91]">My</span>
                                <span class="text-white">Guichet</span>
                            </h1>
                        </a>
                    </div>

                    <!-- Authentication Section -->
                    <div class="flex items-center space-x-3">
                        @auth
                            @if (Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="text-white hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">Admin
                                    Dashboard</a>
                            @elseif(Auth::user()->isOrganizer())
                                <a href="{{ route('organizer.dashboard') }}"
                                    class="text-white hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">My
                                    Dashboard</a>
                            @else
                                <a href="{{ route('client.dashboard') }}"
                                    class="text-white hover:text-blue-400 px-3 py-2 text-sm font-medium transition-colors">My
                                    Tickets</a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                    class="text-white hover:text-red-400 px-3 py-2 text-sm font-medium transition-colors">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-[#48ff91]  px-4 py-2 text-sm font-medium transition-colors border rounded-[50px] border-[#48ff91]  hover:border-[#052cff] hover:text-[#052cff]">Login</a>
                            <a href="{{ route('register.client') }}"
                                class="bg-[#052cff] text-white px-4 py-2  text-sm rounded-[50px] font-medium hover:bg-[#48ff91] transition-colors">Register</a>
                        @endauth

                        <!-- Mobile menu button -->
                        <div class="lg:hidden">
                            <button type="button"
                                class="text-white hover:text-blue-400 focus:outline-none focus:text-blue-400 ml-4"
                                onclick="toggleMobileMenu()">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
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
                                <select name="category"
                                    class="bg-slate-700 text-white border border-[#48ff91] rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-[#48ff91] focus:outline-none">
                                    <option value="">Toutes catégories</option>
                                    <option value="Concerts"
                                        {{ request('category') === 'Concerts' ? 'selected' : '' }}> Concerts</option>
                                    <option value="Festivals"
                                        {{ request('category') === 'Festivals' ? 'selected' : '' }}> Festivals</option>
                                    <option value="Theatre" {{ request('category') === 'Theatre' ? 'selected' : '' }}>
                                        Théâtre</option>
                                    <option value="Sports" {{ request('category') === 'Sports' ? 'selected' : '' }}>
                                        Sports</option>
                                    <option value="Cinema" {{ request('category') === 'Cinema' ? 'selected' : '' }}>
                                        Cinéma</option>
                                    <option value="Business"
                                        {{ request('category') === 'Business' ? 'selected' : '' }}>Business</option>
                                    <option value="Technology"
                                        {{ request('category') === 'Technology' ? 'selected' : '' }}> Technology
                                    </option>
                                    <option value="Arts & Culture"
                                        {{ request('category') === 'Arts & Culture' ? 'selected' : '' }}> Arts &
                                        Culture</option>
                                    <option value="Education"
                                        {{ request('category') === 'Education' ? 'selected' : '' }}> Education</option>
                                    <option value="Food & Drink"
                                        {{ request('category') === 'Food & Drink' ? 'selected' : '' }}> Food & Drink
                                    </option>
                                    <option value="Health & Wellness"
                                        {{ request('category') === 'Health & Wellness' ? 'selected' : '' }}> Health &
                                        Wellness</option>
                                </select>
                            </div>

                            <!-- Search Input -->
                            <div class="relative flex-1">
                                <input type="text" name="search" placeholder="Cherchez ce que vous voulez"
                                    class="w-full pl-4 pr-12 py-3 bg-slate-700 text-white placeholder-gray-300 border border-[#48ff91] rounded-lg focus:ring-2 focus:ring-[#48ff91] focus:outline-none text-sm"
                                    value="{{ request('search') }}">
                                <button type="submit" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <svg class="w-5 h-5 text-gray-300 hover:text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
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
                    <a href="{{ route('events.index', ['category' => 'Concerts']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Concerts' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-music-note-beamed" viewBox="0 0 16 16">
                                <path
                                    d="M6 13c0 1.105-1.12 2-2.5 2S1 14.105 1 13s1.12-2 2.5-2 2.5.896 2.5 2m9-2c0 1.105-1.12 2-2.5 2s-2.5-.895-2.5-2 1.12-2 2.5-2 2.5.895 2.5 2" />
                                <path fill-rule="evenodd" d="M14 11V2h1v9zM6 3v10H5V3z" />
                                <path d="M5 2.905a1 1 0 0 1 .9-.995l8-.8a1 1 0 0 1 1.1.995V3L5 4z" />
                            </svg>
                        </span>
                        <span>Concerts</span>
                    </a>

                    <a href="{{ route('events.index', ['category' => 'Festivals']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Festivals' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="M80-80q29-74 38.5-152.5T130-390q-39-15-64.5-50T40-520v-80q115-38 234.5-116T480-880q86 86 205.5 164T920-600v80q0 45-25.5 80T830-390q2 79 11.5 157.5T880-80H80Zm156-520h488q-78-44-140.5-90.5T480-772q-41 35-103.5 81.5T236-600Zm344 140q25 0 42.5-17.5T640-520H520q0 25 17.5 42.5T580-460Zm-200 0q25 0 42.5-17.5T440-520H320q0 25 17.5 42.5T380-460Zm-200 0q25 0 42.5-17.5T240-520H120q0 25 17.5 42.5T180-460Zm6 300h107q9-60 14-119t8-119q-9-5-18-10.5T280-422q-15 15-32.5 24.5T210-383q-2 57-7 112.5T186-160Zm188 0h212q-8-55-12.5-110T566-381q-26-2-47.5-12.5T480-421q-17 17-39.5 27.5T394-381q-3 56-7.5 111T374-160Zm293 0h107q-12-55-17-110.5T750-383q-20-5-38-14.5T680-422q-8 8-17 13.5T645-398q3 60 8.5 119T667-160Zm113-300q25 0 42.5-17.5T840-520H720q0 25 17.5 42.5T780-460Z" />
                            </svg>
                        </span>
                        <span>Festivals</span>
                    </a>
                    <a href="{{ route('events.index', ['category' => 'Theatre']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Theatre' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="M760-660q17 0 28.5-11.5T800-700q0-17-11.5-28.5T760-740q-17 0-28.5 11.5T720-700q0 17 11.5 28.5T760-660Zm-160 0q17 0 28.5-11.5T640-700q0-17-11.5-28.5T600-740q-17 0-28.5 11.5T560-700q0 17 11.5 28.5T600-660Zm-20 136h200q0-35-30.5-55.5T680-600q-39 0-69.5 20.5T580-524ZM280-80q-100 0-170-70T40-320v-280h480v280q0 100-70 170T280-80Zm0-80q66 0 113-47t47-113v-200H120v200q0 66 47 113t113 47Zm400-200q-26 0-51.5-5.5T580-382v-94q22 17 47.5 26.5T680-440q66 0 113-47t47-113v-200H520v140h-80v-220h480v280q0 100-70 170t-170 70Zm-480-20q17 0 28.5-11.5T240-420q0-17-11.5-28.5T200-460q-17 0-28.5 11.5T160-420q0 17 11.5 28.5T200-380Zm160 0q17 0 28.5-11.5T400-420q0-17-11.5-28.5T360-460q-17 0-28.5 11.5T320-420q0 17 11.5 28.5T360-380Zm-80 136q39 0 69.5-20.5T380-320H180q0 35 30.5 55.5T280-244Zm0-96Zm400-280Z" />
                            </svg>
                        </span>
                        <span>Théâtre</span>
                    </a>

                    <a href="{{ route('events.index', ['category' => 'Sports']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Sports' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="M162-520h114q-6-38-23-71t-43-59q-18 29-30.5 61.5T162-520Zm522 0h114q-5-36-17.5-68.5T750-650q-26 26-43 59t-23 71ZM210-310q26-26 43-59t23-71H162q5 36 17.5 68.5T210-310Zm540 0q18-29 30.5-61.5T798-440H684q6 38 23 71t43 59ZM358-520h82v-278q-53 8-98.5 29.5T260-712q39 38 64.5 86.5T358-520Zm162 0h82q8-57 33.5-105.5T700-712q-36-35-81.5-56.5T520-798v278Zm-80 358v-278h-82q-8 57-33.5 105.5T260-248q36 35 81.5 56.5T440-162Zm80 0q53-8 98.5-29.5T700-248q-39-38-64.5-86.5T602-440h-82v278Zm-40-318Zm0 400q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
                            </svg>
                        </span>
                        <span>Sports</span>
                    </a>

                    <a href="{{ route('events.index', ['category' => 'Cinema']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Cinema' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="m160-800 80 160h120l-80-160h80l80 160h120l-80-160h80l80 160h120l-80-160h120q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800Zm0 240v320h640v-320H160Zm0 0v320-320Z" />
                            </svg>
                        </span>
                        <span>Cinema</span>
                    </a>

                    <a href="{{ route('events.index', ['category' => 'Business']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Business' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="M160-120q-33 0-56.5-23.5T80-200v-440q0-33 23.5-56.5T160-720h160v-80q0-33 23.5-56.5T400-880h160q33 0 56.5 23.5T640-800v80h160q33 0 56.5 23.5T880-640v440q0 33-23.5 56.5T800-120H160Zm240-600h160v-80H400v80Zm400 360H600v80H360v-80H160v160h640v-160Zm-360 0h80v-80h-80v80Zm-280-80h200v-80h240v80h200v-200H160v200Zm320 40Z" />
                            </svg>
                        </span>
                        <span>Business</span>
                    </a>

                    <a href="{{ route('events.index', ['category' => 'Technology']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Technology' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="M40-120v-80h880v80H40Zm120-120q-33 0-56.5-23.5T80-320v-440q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v440q0 33-23.5 56.5T800-240H160Zm0-80h640v-440H160v440Zm0 0v-440 440Z" />
                            </svg>
                        </span>
                        <span>Technology</span>
                    </a>

                    <a href="{{ route('events.index', ['category' => 'Arts & Culture']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Arts & Culture' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-brush" viewBox="0 0 16 16">
                                <path
                                    d="M15.825.12a.5.5 0 0 1 .132.584c-1.53 3.43-4.743 8.17-7.095 10.64a6.1 6.1 0 0 1-2.373 1.534c-.018.227-.06.538-.16.868-.201.659-.667 1.479-1.708 1.74a8.1 8.1 0 0 1-3.078.132 4 4 0 0 1-.562-.135 1.4 1.4 0 0 1-.466-.247.7.7 0 0 1-.204-.288.62.62 0 0 1 .004-.443c.095-.245.316-.38.461-.452.394-.197.625-.453.867-.826.095-.144.184-.297.287-.472l.117-.198c.151-.255.326-.54.546-.848.528-.739 1.201-.925 1.746-.896q.19.012.348.048c.062-.172.142-.38.238-.608.261-.619.658-1.419 1.187-2.069 2.176-2.67 6.18-6.206 9.117-8.104a.5.5 0 0 1 .596.04M4.705 11.912a1.2 1.2 0 0 0-.419-.1c-.246-.013-.573.05-.879.479-.197.275-.355.532-.5.777l-.105.177c-.106.181-.213.362-.32.528a3.4 3.4 0 0 1-.76.861c.69.112 1.736.111 2.657-.12.559-.139.843-.569.993-1.06a3 3 0 0 0 .126-.75zm1.44.026c.12-.04.277-.1.458-.183a5.1 5.1 0 0 0 1.535-1.1c1.9-1.996 4.412-5.57 6.052-8.631-2.59 1.927-5.566 4.66-7.302 6.792-.442.543-.795 1.243-1.042 1.826-.121.288-.214.54-.275.72v.001l.575.575zm-4.973 3.04.007-.005zm3.582-3.043.002.001h-.002z" />
                            </svg>
                        </span>
                        <span>Arts & Culture</span>
                    </a>

                    <a href="{{ route('events.index', ['category' => 'Education']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Education' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="M300-80q-58 0-99-41t-41-99v-520q0-58 41-99t99-41h500v600q-25 0-42.5 17.5T740-220q0 25 17.5 42.5T800-160v80H300Zm-60-267q14-7 29-10t31-3h20v-440h-20q-25 0-42.5 17.5T240-740v393Zm160-13h320v-440H400v440Zm-160 13v-453 453Zm60 187h373q-6-14-9.5-28.5T660-220q0-16 3-31t10-29H300q-26 0-43 17.5T240-220q0 26 17 43t43 17Z" />
                            </svg>
                        </span>
                        <span>Education</span>
                    </a>

                    <a href="{{ route('events.index', ['category' => 'Food & Drink']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Food & Drink' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="M400-240h40v-160q25 0 42.5-17.5T500-460v-120h-40v120h-20v-120h-40v120h-20v-120h-40v120q0 25 17.5 42.5T400-400v160Zm160 0h40v-340q-33 0-56.5 23.5T520-500v120h40v140ZM160-120v-480l320-240 320 240v480H160Zm80-80h480v-360L480-740 240-560v360Zm240-270Z" />
                            </svg>
                        </span>
                        <span>Food & Drink</span>
                    </a>

                    <a href="{{ route('events.index', ['category' => 'Health & Wellness']) }}"
                        class="flex items-center space-x-2 text-white hover:text-[#48ff91] text-sm font-medium whitespace-nowrap transition-colors {{ request('category') === 'Health & Wellness' ? 'text-[#48ff91]' : '' }}">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16"
                                viewBox="0 -960 960 960" fill="currentColor">
                                <path
                                    d="M320-120v-200H120v-320h200v-200h320v200h200v320H640v200H320Zm80-80h160v-200h200v-160H560v-200H400v200H200v160h200v200Zm80-280Z" />
                            </svg>
                        </span>
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
                    <select name="category"
                        class="w-full bg-slate-700 text-white border border-slate-600 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes catégories</option>
                        <option value="Concerts" {{ request('category') === 'Concerts' ? 'selected' : '' }}>🎵
                            Concerts</option>
                        <option value="Festivals" {{ request('category') === 'Festivals' ? 'selected' : '' }}>🎪
                            Festivals</option>
                        <option value="Theatre" {{ request('category') === 'Theatre' ? 'selected' : '' }}>🎭 Théâtre
                        </option>
                        <option value="Sports" {{ request('category') === 'Sports' ? 'selected' : '' }}>⚽ Sports
                        </option>
                        <option value="Cinema" {{ request('category') === 'Cinema' ? 'selected' : '' }}>🎬 Cinéma
                        </option>
                        <option value="Business" {{ request('category') === 'Business' ? 'selected' : '' }}>💼
                            Business</option>
                        <option value="Technology" {{ request('category') === 'Technology' ? 'selected' : '' }}>💻
                            Technology</option>
                        <option value="Arts & Culture"
                            {{ request('category') === 'Arts & Culture' ? 'selected' : '' }}>🎨 Arts & Culture</option>
                        <option value="Education" {{ request('category') === 'Education' ? 'selected' : '' }}>📚
                            Education</option>
                        <option value="Food & Drink" {{ request('category') === 'Food & Drink' ? 'selected' : '' }}>
                            🍽️ Food & Drink</option>
                        <option value="Health & Wellness"
                            {{ request('category') === 'Health & Wellness' ? 'selected' : '' }}>🏥 Health & Wellness
                        </option>
                    </select>
                    <input type="text" name="search" placeholder="Cherchez ce que vous voulez..."
                        class="w-full px-3 py-2 bg-slate-700 text-white placeholder-gray-300 border border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500"
                        value="{{ request('search') }}">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700">Rechercher</button>
                </form>

                <!-- Mobile Auth Links -->
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-3 py-2 text-white hover:text-blue-400 hover:bg-slate-700 rounded-lg">Admin
                            Dashboard</a>
                    @elseif(Auth::user()->isOrganizer())
                        <a href="{{ route('organizer.dashboard') }}"
                            class="block px-3 py-2 text-white hover:text-blue-400 hover:bg-slate-700 rounded-lg">My
                            Dashboard</a>
                    @else
                        <a href="{{ route('client.dashboard') }}"
                            class="block px-3 py-2 text-white hover:text-blue-400 hover:bg-slate-700 rounded-lg">My
                            Tickets</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-3 py-2 text-white hover:text-red-400 hover:bg-slate-700 rounded-lg">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 text-white hover:text-blue-400 hover:bg-slate-700 rounded-lg">Login</a>
                    <a href="{{ route('register.client') }}"
                        class="block px-3 py-2 bg-blue-600 text-white rounded-lg text-center hover:bg-blue-700">Register</a>
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
                @if ($events->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($events as $event)
                            <!-- Event Card with iOS Glassmorphism Style -->
                            <div class="backdrop-blur-xl bg-white/10 border border-[#48ff91] rounded-2xl overflow-hidden shadow-2xl hover:shadow-[0_32px_64px_rgba(0,0,0,0.3)] hover:bg-white/15 transition-all duration-500 transform hover:-translate-y-2 event-card relative before:absolute before:inset-0 before:rounded-2xl before:p-px before:bg-gradient-to-b before:from-white/20 before:to-transparent before:mask-composite-[subtract] before:mask-[linear-gradient(#fff_0_0)] group">
                                <!-- Event Image -->
                                <div class="relative">
                                    @if ($event->image_path)
                                        <img src="{{ Storage::url($event->image_path) }}"
                                             alt="{{ $event->title }}"
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gradient-to-br from-blue-500/80 via-purple-600/80 to-pink-500/80 backdrop-blur-sm flex items-center justify-center relative">
                                            <!-- Subtle glass overlay -->
                                            <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                                            <svg class="w-16 h-16 text-white/70 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-md bg-blue-500/80 text-white border border-blue-400/30 shadow-lg">
                                            {{ $event->category }}
                                        </span>
                                    </div>

                                    <!-- Price Badge -->
                                    <div class="absolute top-3 right-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold backdrop-blur-md border shadow-lg
                                            {{ $event->type === 'free' ? 'bg-green-500/80 text-white border-green-400/30' : 'bg-purple-500/80 text-white border-purple-400/30' }}">
                                            {{ $event->type === 'free' ? 'Gratuit' : number_format($event->price, 2) . ' €' }}
                                        </span>
                                    </div>

                                    <!-- Sold Out Overlay -->
                                    @if ($event->max_tickets && $event->tickets_sold >= $event->max_tickets)
                                        <div class="absolute inset-0 backdrop-blur-sm bg-black/40 flex items-center justify-center">
                                            <span class="bg-red-500/90 backdrop-blur-md text-white px-6 py-3 rounded-2xl font-bold text-lg border border-red-400/30 shadow-2xl">
                                                Sold Out
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Event Content -->
                                <div class="p-6 relative">
                                    <!-- Subtle inner glow -->
                                    <div class="absolute inset-0 bg-gradient-to-b from-white/5 to-transparent rounded-b-2xl pointer-events-none"></div>

                                    <div class="relative z-10">
                                        <!-- Event Title -->
                                        <h3 class="text-lg font-semibold text-white/95 mb-2 line-clamp-2 drop-shadow-sm">
                                            <a href="{{ route('events.show', $event) }}" class="hover:text-blue-300 transition-colors duration-300">
                                                {{ $event->title }}
                                            </a>
                                        </h3>

                                        <!-- Event Description -->
                                        <p class="text-white/70 text-sm mb-4 line-clamp-2 drop-shadow-sm">
                                            {{ Str::limit($event->description, 100) }}
                                        </p>

                                        <!-- Event Details -->
                                        <div class="space-y-2 mb-4">
                                            <!-- Date -->
                                            <div class="flex items-center text-sm text-white/80">
                                                <svg class="w-4 h-4 mr-2 text-blue-300/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $event->start_date->format('d M Y à H:i') }}
                                            </div>

                                            <!-- Location -->
                                            <div class="flex items-center text-sm text-white/80">
                                                <svg class="w-4 h-4 mr-2 text-blue-300/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                {{ Str::limit($event->location, 30) }}
                                            </div>

                                            <!-- Organizer -->
                                            <div class="flex items-center text-sm text-white/80">
                                                <svg class="w-4 h-4 mr-2 text-blue-300/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                par {{ $event->organizer->name }}
                                            </div>

                                            <!-- Tickets Available -->
                                            @if ($event->max_tickets)
                                                <div class="flex items-center text-sm text-white/80">
                                                    <svg class="w-4 h-4 mr-2 text-blue-300/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a1 1 0 001 1h1a1 1 0 001-1V7a2 2 0 00-2-2H5zM5 21a2 2 0 01-2-2v-3a1 1 0 011-1h1a1 1 0 011 1v3a2 2 0 01-2 2H5z"></path>
                                                    </svg>
                                                    {{ $event->max_tickets - $event->tickets_sold }} places disponibles
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Action Button -->
                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('events.show', $event) }}"
                                               class="backdrop-blur-md bg-[#052cff]/80 hover:bg-[#48ff91] text-white px-6 py-2.5 rounded-[50px] text-sm font-semibold transition-all duration-300 flex items-center space-x-2 border border-blue-400/30 shadow-lg hover:shadow-xl hover:scale-105 group-hover:bg-blue-400/80">
                                                <span>Voir Détails</span>
                                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>

                                            @if ($event->start_date > now())
                                                <span class="text-xs text-green-300/90 font-semibold backdrop-blur-sm bg-green-500/20 px-2 py-1 rounded-lg border border-green-400/20">À venir</span>
                                            @else
                                                <span class="text-xs text-gray-300/70 font-semibold backdrop-blur-sm bg-gray-500/20 px-2 py-1 rounded-lg border border-gray-400/20">Terminé</span>
                                            @endif
                                        </div>
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
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-xl font-medium text-white mb-2">Aucun événement trouvé</h3>
                        <p class="text-gray-300 mb-6">
                            @if (request()->hasAny(['search', 'category', 'type']))
                                Essayez d'ajuster vos critères de recherche ou
                                <a href="{{ route('events.index') }}"
                                    class="text-blue-400 hover:text-blue-300 underline">Retour à l’accueil</a>.
                            @else
                                Il n'y a aucun événement disponible pour le moment. Revenez plus tard !
                            @endif
                        </p>

                        @auth
                            @if (Auth::user()->isOrganizer() && Auth::user()->is_approved)
                                <div class="mt-6">
                                    <a href="{{ route('organizer.events.create') }}"
                                        class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
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
                        <span class="text-[#48ff91]">My</span>
                        <span class="text-white">Guichet</span>
                        <p class="text-gray-300 mb-4">
                            Votre plateforme de billetterie de confiance pour découvrir et réserver des événements
                            exceptionnels.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-300 hover:text-[#48ff91] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                    class="bi bi-facebook" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-[#48ff91] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                    class="bi bi-instagram" viewBox="0 0 16 16">
                                    <path
                                        d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                                </svg>
                            </a>
                            <a href="#" class="text-gray-300 hover:text-[#48ff91] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor"
                                    class="bi bi-twitter-x" viewBox="0 0 16 16">
                                    <path
                                        d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z" />
                                </svg>
                            </a>
                        </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Liens Rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('welcome') }}"
                                class="text-gray-300 hover:text-[#48ff91] transition-colors">Accueil</a></li>
                        <li><a href="{{ route('events.index') }}"
                                class="text-gray-300 hover:text-[#48ff91] transition-colors">Événements</a></li>
                        <li><a href="{{ route('register.organizer') }}"
                                class="text-gray-300 hover:text-[#48ff91] transition-colors">Devenir Organisateur</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-[#48ff91] transition-colors">À
                                Propos</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-[#48ff91] transition-colors">Centre
                                d'Aide</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-[#48ff91] transition-colors">Contact</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-[#48ff91] transition-colors">Conditions
                                d'Utilisation</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-[#48ff91] transition-colors">Politique
                                de Confidentialité</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">&copy; {{ date('Y') }} MyGuichet. Tous droits réservés.</p>
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
            anchor.addEventListener('click', function(e) {
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
