@props(['title'])

<!-- Top Header -->
<div class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- User Profile Section -->
            <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                    <span class="text-gray-700 font-semibold text-sm">
                        {{ substr(Auth::user()->name, 0, 2) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500">Event enthusiast</p>
                </div>
            </div>
            
            <!-- Right side actions -->
            <div class="flex items-center space-x-4">
                <!-- Notifications -->
                <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM10.07 2.82l-.9.9a2 2 0 000 2.83L15 12.48V19a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6.48l5.83-5.83a2 2 0 012.83 0l.9.9a2 2 0 010 2.83L15.17 8.83z"></path>
                    </svg>
                </button>

                <!-- Messages -->
                <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </button>

                <!-- Language Selector -->
                <div class="flex items-center text-sm text-gray-600">
                    <span class="mr-1">English</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
