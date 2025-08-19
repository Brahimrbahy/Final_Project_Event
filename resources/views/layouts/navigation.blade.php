<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::check() ? route('dashboard') : route('welcome') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @auth
                        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                            {{ __('Home') }}
                        </x-nav-link>
                    @endauth

                    

                    @auth
                        @if(Auth::user() && Auth::user()->isAdmin())
                            <!-- Admin Navigation -->
                            <x-nav-link :href="route('admin.pending-organizers')" :active="request()->routeIs('admin.pending-organizers')">
                                {{ __('Pending Organizers') }}
                                @if(App\Models\User::where('role', 'organizer')->where('is_approved', false)->count() > 0)
                                    <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                        {{ App\Models\User::where('role', 'organizer')->where('is_approved', false)->count() }}
                                    </span>
                                @endif
                            </x-nav-link>

                            <x-nav-link :href="route('admin.pending-events')" :active="request()->routeIs('admin.pending-events')">
                                {{ __('Pending Events') }}
                                @if(App\Models\Event::where('approved', false)->count() > 0)
                                    <span class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                        {{ App\Models\Event::where('approved', false)->count() }}
                                    </span>
                                @endif
                            </x-nav-link>

                            <x-nav-link :href="route('admin.revenue')" :active="request()->routeIs('admin.revenue')">
                                {{ __('Revenue') }}
                            </x-nav-link>

                        @elseif(Auth::user() && Auth::user()->isApprovedOrganizer())
                            <!-- Organizer Navigation -->
                            <x-nav-link :href="route('organizer.events')" :active="request()->routeIs('organizer.events*')">
                                {{ __('My Events') }}
                            </x-nav-link>

                            <x-nav-link :href="route('organizer.bookings')" :active="request()->routeIs('organizer.bookings')">
                                {{ __('Bookings') }}
                            </x-nav-link>

                            <x-nav-link :href="route('organizer.revenue')" :active="request()->routeIs('organizer.revenue')">
                                {{ __('Revenue') }}
                            </x-nav-link>

                        @elseif(Auth::user() && Auth::user()->isClient())
                            <!-- Client Navigation -->
                            <x-nav-link :href="route('client.tickets')" :active="request()->routeIs('client.tickets')">
                                {{ __('My Tickets') }}
                            </x-nav-link>

                            <x-nav-link :href="route('client.recommendations')" :active="request()->routeIs('client.recommendations')">
                                {{ __('Recommendations') }}
                            </x-nav-link>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown / Auth Links -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Guest Navigation -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}"
                           class="flex items-center text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2 rounded-md text-sm font-medium transition duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </a>
                        <a href="{{ route('register.client') }}"
                           class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Register
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
            @endauth

            <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                {{ __('Events') }}
            </x-responsive-nav-link>

            @auth
                @if(Auth::user() && Auth::user()->isAdmin())
                    <x-responsive-nav-link :href="route('admin.pending-organizers')" :active="request()->routeIs('admin.pending-organizers')">
                        {{ __('Pending Organizers') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.pending-events')" :active="request()->routeIs('admin.pending-events')">
                        {{ __('Pending Events') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.revenue')" :active="request()->routeIs('admin.revenue')">
                        {{ __('Revenue') }}
                    </x-responsive-nav-link>
                @elseif(Auth::user() && Auth::user()->isApprovedOrganizer())
                    <x-responsive-nav-link :href="route('organizer.events')" :active="request()->routeIs('organizer.events*')">
                        {{ __('My Events') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('organizer.bookings')" :active="request()->routeIs('organizer.bookings')">
                        {{ __('Bookings') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('organizer.revenue')" :active="request()->routeIs('organizer.revenue')">
                        {{ __('Revenue') }}
                    </x-responsive-nav-link>
                @elseif(Auth::user() && Auth::user()->isClient())
                    <x-responsive-nav-link :href="route('client.tickets')" :active="request()->routeIs('client.tickets')">
                        {{ __('My Tickets') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('client.recommendations')" :active="request()->routeIs('client.recommendations')">
                        {{ __('Recommendations') }}
                    </x-responsive-nav-link>
                @endif
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <!-- Guest Mobile Navigation -->
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200 mb-4">Welcome, Guest!</div>
                    <div class="space-y-2">
                        <a href="{{ route('login') }}"
                           class="flex items-center justify-center w-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Login
                        </a>
                        <a href="{{ route('register.client') }}"
                           class="flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Register as Client
                        </a>
                        <a href="{{ route('register.organizer') }}"
                           class="flex items-center justify-center w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Register as Organizer
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>
