<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center space-x-6 mb-6">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">
                                {{ substr($client->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $client->name }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                {{ $client->email }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Member since {{ $client->created_at->format('F Y') }}
                            </p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Edit Profile Form -->
                    <form action="{{ route('client.update-profile') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Full Name
                                </label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $client->name) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address
                                </label>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $client->email) }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                                💾 Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                        📊 Account Statistics
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <!-- Total Tickets -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl">🎫</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $client->tickets()->count() }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                Total Tickets
                            </div>
                        </div>

                        <!-- Upcoming Events -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl">🔮</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $client->tickets()->whereHas('event', function($query) { $query->where('start_date', '>', now()); })->count() }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                Upcoming Events
                            </div>
                        </div>

                        <!-- Events Attended -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl">🏁</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $client->tickets()->whereHas('event', function($query) { $query->where('end_date', '<', now()); })->count() }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                Events Attended
                            </div>
                        </div>

                        <!-- Total Spent -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="text-2xl">💰</span>
                            </div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white">
                                ${{ number_format($client->tickets()->where('payment_status', 'paid')->sum('total_price'), 2) }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                Total Spent
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                        🕒 Recent Activity
                    </h3>

                    @php
                        $recentTickets = $client->tickets()->with('event')->latest()->take(5)->get();
                    @endphp

                    @if($recentTickets->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentTickets as $ticket)
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold">
                                            {{ substr($ticket->event->title, 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 dark:text-white">
                                            {{ $ticket->event->title }}
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            Booked {{ $ticket->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $ticket->quantity }} ticket(s)
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-300">
                                            @if($ticket->total_price > 0)
                                                ${{ number_format($ticket->total_price, 2) }}
                                            @else
                                                Free
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 text-center">
                            <a href="{{ route('client.tickets') }}" 
                               class="text-blue-600 hover:text-blue-500 font-medium">
                                View All Tickets →
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-2xl">🎫</span>
                            </div>
                            <p class="text-gray-600 dark:text-gray-300">
                                No recent activity. Start booking events!
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                        ⚡ Quick Actions
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('events.index') }}" 
                           class="flex items-center space-x-3 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition duration-200">
                            <span class="text-2xl">🎪</span>
                            <div>
                                <div class="font-semibold text-blue-900 dark:text-blue-100">Browse Events</div>
                                <div class="text-sm text-blue-700 dark:text-blue-300">Find new events to attend</div>
                            </div>
                        </a>

                        <a href="{{ route('client.tickets') }}" 
                           class="flex items-center space-x-3 p-4 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition duration-200">
                            <span class="text-2xl">🎫</span>
                            <div>
                                <div class="font-semibold text-green-900 dark:text-green-100">My Tickets</div>
                                <div class="text-sm text-green-700 dark:text-green-300">View your tickets</div>
                            </div>
                        </a>

                        <a href="{{ route('client.recommendations') }}" 
                           class="flex items-center space-x-3 p-4 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition duration-200">
                            <span class="text-2xl">💡</span>
                            <div>
                                <div class="font-semibold text-purple-900 dark:text-purple-100">Recommendations</div>
                                <div class="text-sm text-purple-700 dark:text-purple-300">Events you might like</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
