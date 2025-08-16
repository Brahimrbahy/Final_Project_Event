<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment Successful') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Success Message -->
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-green-600 mb-2">🎉 Payment Successful!</h1>
                        <p class="text-lg text-gray-600 dark:text-gray-300">
                            Your tickets have been confirmed and sent to your email.
                        </p>
                    </div>

                    <!-- Ticket Information -->
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
                                    <p>🎫 {{ $ticket->quantity }} ticket(s)</p>
                                    <p>🎟️ Ticket Code: <span class="font-mono font-bold text-blue-600">{{ $ticket->ticket_code }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Summary</h3>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Tickets ({{ $ticket->quantity }}x):</span>
                                <span class="font-semibold">${{ number_format($ticket->total_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-300">Platform fee (15%):</span>
                                <span class="font-semibold">${{ number_format($ticket->total_price * 0.15, 2) }}</span>
                            </div>
                            <hr class="border-gray-300 dark:border-gray-500">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total Paid:</span>
                                <span class="text-green-600">${{ number_format($ticket->total_price * 1.15, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">📧 What's Next?</h3>
                        <div class="space-y-3 text-blue-800 dark:text-blue-200">
                            <div class="flex items-start space-x-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                                <p>Check your email for ticket confirmation and QR codes</p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                                <p>Save your ticket code: <span class="font-mono font-bold">{{ $ticket->ticket_code }}</span></p>
                            </div>
                            <div class="flex items-start space-x-3">
                                <span class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                                <p>Arrive at the event venue on time with your ticket</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('client.dashboard') }}" 
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium text-center transition duration-200">
                            📊 View My Tickets
                        </a>
                        <a href="{{ route('events.index') }}" 
                           class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-medium text-center transition duration-200">
                            🎫 Browse More Events
                        </a>
                    </div>

                    <!-- Support Information -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Need help? Contact us at 
                            <a href="mailto:support@eventmanagement.com" class="text-blue-600 hover:text-blue-500">
                                support@eventmanagement.com
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-redirect script (optional) -->
    <script>
        // Optional: Auto-redirect to dashboard after 30 seconds
        // setTimeout(() => {
        //     window.location.href = '{{ route("client.dashboard") }}';
        // }, 30000);

        // Print ticket function
        function printTicket() {
            window.print();
        }

        // Share success function
        function shareSuccess() {
            if (navigator.share) {
                navigator.share({
                    title: 'I just got tickets for {{ $ticket->event->title }}!',
                    text: 'Check out this amazing event',
                    url: '{{ route("events.show", $ticket->event) }}'
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const url = '{{ route("events.show", $ticket->event) }}';
                navigator.clipboard.writeText(url).then(() => {
                    alert('Event link copied to clipboard!');
                });
            }
        }
    </script>
</x-app-layout>
