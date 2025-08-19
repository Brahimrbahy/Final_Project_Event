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
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        .mobile-menu-hidden {
            display: none;
        }

        /* Event details styling inspired by the image */
        .main-container {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
        }

        .event-card-modern {
            background: linear-gradient(135deg, #1a365d 0%, #2d3748 100%);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .event-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            margin: 20px;
            height: 400px;
        }

        .event-image-container img {
            border-radius: 20px;
        }

        .event-image-container::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60%;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            border-radius: 0 0 20px 20px;
        }

        .event-info-overlay {
            position: absolute;
            bottom: 30px;
            left: 30px;
            right: 30px;
            z-index: 10;
        }

        .organizer-badge {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
        }

        .buy-now-btn {
            background: linear-gradient(135deg, #48ff91 0%, #00d665 100%);
            color: #0f172a;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .buy-now-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(72, 255, 145, 0.4);
        }

        .info-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
        }

        .category-badge {
            background: linear-gradient(135deg, #48ff91 0%, #00d665 100%);
            color: #0f172a;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .action-button {
            background: linear-gradient(135deg, #48ff91 0%, #00d665 100%);
            color: #0f172a;
            border-radius: 50px;
            padding: 16px 32px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(72, 255, 145, 0.4);
        }

        .date-highlight {
            color: #48ff91;
            font-weight: 600;
        }

        .geometric-pattern {
            position: absolute;
            right: 20px;
            top: 20px;
            width: 100px;
            height: 100px;
            opacity: 0.6;
            background: linear-gradient(45deg, #48ff91, #00d665);
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
        }
    </style>
</head>

<body class="font-sans antialiased">
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
                                    class="text-white hover:text-[#48ff91]px-3 py-2 text-sm font-medium transition-colors">Admin
                                    Dashboard</a>
                            @elseif(Auth::user()->isOrganizer())
                                <a href="{{ route('organizer.dashboard') }}"
                                    class="text-white hover:text-[#48ff91] px-3 py-2 text-sm font-medium transition-colors">My
                                    Dashboard</a>
                            @else
                                <a href="{{ route('client.dashboard') }}"
                                    class="text-white hover:text-[#48ff91] px-3 py-2 text-sm font-medium transition-colors">My
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
                                class="text-[#48ff91]  px-4 py-2 text-sm font-medium transition-colors border rounded-[50px] border-[#48ff91]  hover:border-[#052cff] hover:text-[#052cff]">Login</a>
                            <a href="{{ route('register.client') }}"
                                class="bg-[#052cff] text-white px-4 py-2  text-sm rounded-[50px] font-medium hover:bg-[#48ff91] transition-colors">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-container">
        <!-- Back Button -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <button onclick="history.back()"
                class="flex items-center space-x-2 text-gray-300 hover:text-[#48ff91] transition-colors mb-8">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span class="text-lg">Retour</span>
            </button>
        </div>

        <!-- Event Details Container -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
            <div class="grid grid-cols-1 xl:grid-cols-5 gap-12">
                <!-- Event Image and Details -->
                <div class="xl:col-span-3">
                    <div class="event-card-modern">
                        <!-- Event Image with Overlays -->
                        @if ($event->image_path)
                            <div class="event-image-container relative">
                                <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}"
                                    class="w-full h-full object-cover">
                                
                                <!-- Geometric Pattern -->
                                <div class="geometric-pattern"></div>

                                <!-- Organizer Badge Overlay -->
                                <div class="absolute top-6 left-6">
                                    <div class="organizer-badge px-4 py-2 flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-r from-[#48ff91] to-[#00d665] rounded-full flex items-center justify-center">
                                            <span class="text-[#0f172a] text-sm font-bold">{{ substr($event->organizer->name, 0, 1) }}</span>
                                        </div>
                                        <span class="text-white text-sm font-medium">{{ $event->organizer->name }}</span>
                                    </div>
                                </div>

                                <!-- Share and Favorite Icons -->
                                <div class="absolute top-6 right-6 flex space-x-3">
                                    <button class="w-12 h-12 bg-black bg-opacity-30 backdrop-filter backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-opacity-50 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                            </path>
                                        </svg>
                                    </button>
                                    <button class="w-12 h-12 bg-black bg-opacity-30 backdrop-filter backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-opacity-50 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Event Info Overlay -->
                                <div class="event-info-overlay">
                                    <div class="flex items-center space-x-3 mb-3">
                                        <span class="category-badge px-4 py-1 text-sm">{{ $event->category }}</span>
                                        @if ($event->start_date > now())
                                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">À venir</span>
                                        @else
                                            <span class="bg-gray-500 text-white px-3 py-1 rounded-full text-xs font-medium">Terminé</span>
                                        @endif
                                    </div>
                                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ $event->title }}</h1>
                                    
                                    <!-- Location and Date -->
                                    <div class="space-y-2">
                                        <div class="flex items-center space-x-3 text-white">
                                            <svg class="w-5 h-5 text-[#48ff91]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="text-lg">{{ $event->location }}</span>
                                        </div>
                                        <div class="flex items-center space-x-3 text-white">
                                            <svg class="w-5 h-5 text-[#48ff91]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-lg">
                                                <span class="date-highlight">{{ $event->start_date->format('j F Y') }}</span>
                                                @if($event->end_date && $event->end_date != $event->start_date)
                                                    - <span class="date-highlight">{{ $event->end_date->format('j F Y') }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Event Content -->
                        <div class="p-8">
                            <!-- Description -->
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-white mb-4">À propos de l'événement</h2>
                                <p class="text-gray-300 leading-relaxed text-lg">{{ $event->description }}</p>
                            </div>

                            <!-- Event Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <!-- Price -->
                                <div class="info-card">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <svg class="w-6 h-6 text-[#48ff91]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        <h3 class="text-white font-semibold text-lg">Prix</h3>
                                    </div>
                                    @if ($event->type === 'free')
                                        <span class="text-[#48ff91] font-bold text-2xl">Gratuit</span>
                                    @else
                                        <span class="text-white font-bold text-2xl">${{ number_format($event->price, 2) }}</span>
                                    @endif
                                </div>

                                <!-- Availability -->
                                @if ($event->max_tickets)
                                    <div class="info-card">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <svg class="w-6 h-6 text-[#48ff91]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                            </svg>
                                            <h3 class="text-white font-semibold text-lg">Disponibilité</h3>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-[#48ff91] font-bold text-xl">{{ $event->max_tickets - $event->tickets_sold }}</span>
                                            <span class="text-gray-300">sur {{ $event->max_tickets }} places</span>
                                        </div>
                                        <!-- Progress Bar -->
                                        <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                                            <div class="bg-gradient-to-r from-[#48ff91] to-[#00d665] h-2 rounded-full" 
                                                 style="width: {{ (($event->max_tickets - $event->tickets_sold) / $event->max_tickets) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Terms and Conditions -->
                            @if ($event->terms_conditions)
                                <div class="info-card">
                                    <h3 class="text-white font-semibold mb-4 text-lg flex items-center">
                                        <svg class="w-5 h-5 text-[#48ff91] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Conditions générales
                                    </h3>
                                    <p class="text-gray-300 leading-relaxed">{{ $event->terms_conditions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Buy Now Section -->
                <div class="xl:col-span-2">
                    <div class="sticky top-24">
                        <!-- Buy Now Card -->
                        @if ($event->start_date > now())
                            <div class="sidebar-card p-8">
                                <!-- Price Display -->
                                <div class="text-center mb-8">
                                    @if ($event->type === 'free')
                                        <div class="text-4xl font-bold text-[#48ff91] mb-3">Gratuit</div>
                                        <div class="text-sm text-gray-600">Événement gratuit</div>
                                    @else
                                        <div class="text-4xl font-bold text-gray-900 mb-3">
                                            ${{ number_format($event->price, 2) }}</div>
                                        <div class="text-sm text-gray-600">par billet</div>
                                    @endif
                                </div>

                                <!-- Availability Check -->
                                @if ($event->max_tickets && $event->tickets_sold >= $event->max_tickets)
                                    <div class="bg-red-50 border-2 border-red-200 text-red-700 px-6 py-6 rounded-2xl mb-8 text-center">
                                        <div class="text-2xl font-bold mb-2">Complet !</div>
                                        <span class="text-sm">Aucun billet disponible</span>
                                    </div>
                                @else
                                    <!-- Buy Button -->
                                    @auth
                                        @if (Auth::user()->isClient())
                                            <a href="{{ route('tickets.select-quantity', $event) }}"
                                                class="action-button block w-full text-center text-lg mb-6 no-underline">
                                                Acheter maintenant
                                            </a>
                                            <p class="text-xs text-gray-500 text-center mb-8">
                                                {{ $event->type === 'free' ? 'Réservation gratuite' : 'Sélectionnez la quantité et payez' }}
                                            </p>
                                        @else
                                            <div class="bg-yellow-50 border-2 border-yellow-200 text-yellow-800 px-6 py-6 rounded-2xl mb-8 text-center">
                                                <div class="font-semibold mb-1">Accès restreint</div>
                                                <span class="text-sm">Seuls les clients peuvent acheter des billets</span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="space-y-4 mb-8">
                                            <p class="text-gray-700 text-center font-medium">Connectez-vous pour acheter des billets</p>
                                            <div class="flex flex-col space-y-3">
                                                <a href="{{ route('login') }}"
                                                    class="w-full bg-[#052cff] hover:bg-[#48ff91] hover:text-[#052cff] rounded-[50px] text-white px-8 py-4 font-semibold text-center transition-colors">
                                                    Se connecter
                                                </a>
                                                <a href="{{ route('register.client') }}"
                                                    class="w-full bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-4 rounded-[50px] font-semibold text-center transition-colors">
                                                    Créer un compte
                                                </a>
                                            </div>
                                        </div>
                                    @endauth
                                @endif

                                <!-- Event Info Summary -->
                                <div class="space-y-4 text-sm text-gray-600">
                                    <!-- Date -->
                                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                        <span class="font-medium">Date</span>
                                        <span class="text-right">
                                            {{ $event->start_date->format('j M Y') }}
                                            @if($event->end_date && $event->end_date != $event->start_date)
                                                - {{ $event->end_date->format('j M Y') }}
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <!-- Location -->
                                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                        <span class="font-medium">Lieu</span>
                                        <span class="text-right">{{ $event->location }}</span>
                                    </div>
                                    
                                    <!-- Category -->
                                    <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                        <span class="font-medium">Catégorie</span>
                                        <span class="text-right">{{ $event->category }}</span>
                                    </div>

                                    <!-- Organizer -->
                                    <div class="flex items-center justify-between py-3">
                                        <span class="font-medium">Organisateur</span>
                                        <span class="text-right">{{ $event->organizer->name }}</span>
                                    </div>
                                </div>

                                <!-- Ticket Counter -->
                                @if ($event->max_tickets)
                                    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                                        <div class="text-2xl font-bold text-gray-900 mb-1">{{ $event->max_tickets - $event->tickets_sold }}</div>
                                        <div class="text-sm text-gray-600">billets restants</div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Event Ended -->
                            <div class="sidebar-card p-8 text-center">
                                <div class="text-gray-400 mb-6">
                                    <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">Événement terminé</h3>
                                <p class="text-gray-600">Cet événement s'est terminé le
                                    {{ $event->start_date->format('j F Y') }}</p>
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