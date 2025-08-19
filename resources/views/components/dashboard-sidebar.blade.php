@props(['role', 'currentRoute' => ''])

<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0" id="sidebar">
    <!-- Logo Section -->
    <div class="flex items-center h-16 px-6 border-b border-gray-200">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-[#48ff91] rounded-lg flex items-center justify-center mr-3">
                <span class="text-[#052cff] font-bold text-lg">M</span>
            </div>
            <h2 class="text-lg font-bold text-gray-900">MyGuichet</h2>
        </div>
        <!-- Mobile close button -->
        <button type="button" class="lg:hidden text-gray-400 hover:text-gray-600 ml-auto" onclick="toggleSidebar()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <!-- User Profile Section -->
    <div class="p-4 border-b border-gray-200">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                <span class="text-gray-700 font-semibold text-sm">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-4 space-y-1">
        @if($role === 'admin')
            <!-- Admin Navigation -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentRoute === 'admin.dashboard' ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                 <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Dashboard
            </a>
             <a href="{{ route('admin.organizers') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'admin.organizers') ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                Organizers
            </a>
            <a href="{{ route('admin.events') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'admin.events') ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                Events
            </a>

            <a href="{{ route('admin.pending-events') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'admin.pending-events') ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Pending Events
            </a>

            <a href="{{ route('admin.pending-organizers') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'admin.pending-organizers') ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2m-2 0v4a2 2 0 002 2h2a2 2 0 002-2v-4m0 0V9a2 2 0 00-2-2H9z"></path>
                </svg>
                Pending organizers
            </a>

            

            <a href="{{ route('admin.revenue') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'admin.revenue') ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Revenue
            </a>
        @elseif($role === 'organizer')
            <!-- Organizer Navigation -->
            <a href="{{ route('organizer.dashboard') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentRoute === 'organizer.dashboard' ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Dashboard
            </a>

           

            <a href="{{ route('organizer.events') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'organizer.events') && !str_contains($currentRoute, 'create') ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                My Events
            </a>

            <a href="{{ route('organizer.events.create') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'organizer.events.create') ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2m-2 0v4a2 2 0 002 2h2a2 2 0 002-2v-4m0 0V9a2 2 0 00-2-2H9z"></path>
                </svg>
                My Applications
            </a>

            <a href="{{ route('organizer.bookings') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'organizer.bookings') ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Bookings
            </a>

            <a href="{{ route('organizer.revenue') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'organizer.revenue') ? 'bg-yellow-100 text-yellow-800 border-r-2 border-yellow-400' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                </svg>
                Revenue
            </a>
        @else
            <!-- Client Navigation -->
            <a href="{{ route('client.dashboard') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ $currentRoute === 'client.dashboard' ? 'bg-yellow-100 text-yellow-800' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('client.tickets') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'client.tickets') ? 'bg-yellow-100 text-yellow-800' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
                My Tickets
            </a>

            <a href="{{ route('events.index') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'events.index') ? 'bg-yellow-100 text-yellow-800' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Browse Events
            </a>

            <a href="{{ route('client.booking-history') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'client.booking-history') ? 'bg-yellow-100 text-yellow-800' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0h2m-2 0v4a2 2 0 002 2h2a2 2 0 002-2v-4m0 0V9a2 2 0 00-2-2H9z"></path>
                </svg>
                Booking History
            </a>

            <a href="{{ route('client.recommendations') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'client.recommendations') ? 'bg-yellow-100 text-yellow-800' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                Recommendations
            </a>

            <a href="{{ route('client.profile') }}"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 {{ str_contains($currentRoute, 'client.profile') ? 'bg-yellow-100 text-yellow-800' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Profile
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-100">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Support
            </a>

            <a href="#"
               class="flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-100">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Settings
            </a>
        @endif
    </nav>

    <!-- Bottom section -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
        <div class="space-y-2">
            <a href="#"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-100">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Help
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>
    </nav>
</div>

<!-- Mobile sidebar overlay -->
<div class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden hidden" id="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Mobile sidebar toggle button -->
<button type="button" class="fixed top-4 left-4 z-50 lg:hidden bg-white shadow-lg text-gray-700 p-2 rounded-md border border-gray-200" onclick="toggleSidebar()">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    
    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}
</script>
