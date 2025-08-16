<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ticket Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Ticket Header -->
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">🎫 Your Ticket</h1>
                        <p class="text-lg text-gray-600 dark:text-gray-300">
                            Ticket confirmed and ready to use
                        </p>
                    </div>

                    <!-- Ticket Code -->
                    <div class="bg-gray-900 text-white p-6 rounded-lg text-center mb-8">
                        <p class="text-sm text-gray-300 mb-2">Ticket Code</p>
                        <p class="text-3xl font-mono font-bold tracking-wider">{{ $ticket->ticket_code }}</p>
                        <p class="text-sm text-gray-300 mt-2">Show this code at the event entrance</p>
                    </div>

                    <!-- Event Information -->
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900 dark:to-purple-900 rounded-lg p-6 mb-8">
                        <div class="flex items-start space-x-4">
                            @if($ticket->event->image_path)
                                <img src="{{ Storage::url($ticket->event->image_path) }}" 
                                     alt="{{ $ticket->event->title }}" 
                                     class="w-24 h-24 object-cover rounded-lg">
                            @else
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white text-2xl font-bold">
                                        {{ substr($ticket->event->title, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $ticket->event->title }}
                                </h2>
                                <div class="space-y-1 text-gray-600 dark:text-gray-300">
                                    <p>📅 {{ $ticket->event->start_date->format('F j, Y \a\t g:i A') }}</p>
                                    <p>📍 {{ $ticket->event->location }}</p>
                                    @if($ticket->event->address)
                                        <p>🏠 {{ $ticket->event->address }}</p>
                                    @endif
                                    <p>👤 Organized by {{ $ticket->event->organizer->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Ticket Information</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Quantity:</span>
                                    <span class="font-semibold">{{ $ticket->quantity }} ticket(s)</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Status:</span>
                                    @if($ticket->payment_status === 'paid')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            ✅ Confirmed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ⏳ Pending Payment
                                        </span>
                                    @endif
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-300">Purchase Date:</span>
                                    <span class="font-semibold">{{ $ticket->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Payment Information</h3>
                            <div class="space-y-2">
                                @if($ticket->event->type === 'free')
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-300">Price:</span>
                                        <span class="font-semibold text-green-600">FREE</span>
                                    </div>
                                @else
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-300">Subtotal:</span>
                                        <span class="font-semibold">${{ number_format($ticket->total_price, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-300">Platform fee:</span>
                                        <span class="font-semibold">${{ number_format($ticket->total_price * 0.15, 2) }}</span>
                                    </div>
                                    <hr class="border-gray-300 dark:border-gray-500">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span>Total Paid:</span>
                                        <span class="text-green-600">${{ number_format($ticket->total_price * 1.15, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Important Information -->
                    @if($ticket->event->terms_conditions)
                        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 mb-8">
                            <h3 class="font-semibold text-yellow-900 dark:text-yellow-100 mb-2">⚠️ Important Information</h3>
                            <p class="text-yellow-800 dark:text-yellow-200 text-sm">
                                {{ $ticket->event->terms_conditions }}
                            </p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('client.dashboard') }}" 
                           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-medium text-center transition duration-200">
                            ← Back to Dashboard
                        </a>
                        
                        <a href="{{ route('events.show', $ticket->event) }}" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium text-center transition duration-200">
                            📋 View Event Details
                        </a>
                        
                        <button onclick="window.print()" 
                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium text-center transition duration-200">
                            🖨️ Print Ticket
                        </button>
                    </div>

                    <!-- QR Code Placeholder -->
                    <div class="mt-8 text-center">
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-8">
                            <div class="w-32 h-32 bg-white border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <span class="text-gray-500 text-sm">QR Code</span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                QR code for quick entry (can be implemented with a QR code library)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
