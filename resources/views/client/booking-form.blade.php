@extends('layouts.dashbord')

@section('content')
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <x-dashboard-sidebar role="client" :current-route="request()->route()->getName()" />

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
            <x-client-header />

            <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Event Header -->
                    <div class="flex flex-col lg:flex-row lg:items-start lg:space-x-6 mb-8">
                        <!-- Event Image -->
                        <div class="w-full lg:w-1/3 mb-6 lg:mb-0">
                            @if($event->image_path)
                                <img src="{{ Storage::url($event->image_path) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-full h-64 object-cover rounded-lg">
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <span class="text-white text-6xl font-bold">
                                        {{ substr($event->title, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Event Details -->
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                                {{ $event->title }}
                            </h1>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                    <span class="mr-3 text-lg">📅</span>
                                    <div>
                                        <div class="font-medium">{{ $event->start_date->format('F j, Y') }}</div>
                                        <div class="text-sm">{{ $event->start_date->format('l') }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                    <span class="mr-3 text-lg">⏰</span>
                                    <div>
                                        <div class="font-medium">{{ $event->start_date->format('g:i A') }}</div>
                                        <div class="text-sm">{{ $event->duration ?? 'Duration TBD' }}</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                    <span class="mr-3 text-lg">📍</span>
                                    <div>
                                        <div class="font-medium">{{ $event->location }}</div>
                                        <div class="text-sm">Venue</div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center text-gray-600 dark:text-gray-300">
                                    <span class="mr-3 text-lg">👥</span>
                                    <div>
                                        <div class="font-medium">{{ $event->organizer->name }}</div>
                                        <div class="text-sm">Organizer</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="mb-6">
                                @if($event->isFree())
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        🎉 FREE EVENT
                                    </span>
                                @else
                                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                        ${{ number_format($event->price, 2) }}
                                        <span class="text-lg text-gray-600 dark:text-gray-300 font-normal">per ticket</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Availability -->
                            <div class="mb-6">
                                @if($event->hasAvailableTickets())
                                    <div class="flex items-center justify-between text-sm mb-2">
                                        <span class="text-green-600 dark:text-green-400 font-medium">
                                            ✅ {{ $event->available_tickets }} tickets available
                                        </span>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ $event->tickets_sold }} / {{ $event->max_attendees }} sold
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                        <div class="bg-green-500 h-3 rounded-full transition-all duration-300" 
                                             style="width: {{ ($event->tickets_sold / $event->max_attendees) * 100 }}%"></div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        🚫 SOLD OUT
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($event->hasAvailableTickets())
                        <!-- Booking Form -->
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-8">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                                🎫 Book Your Tickets
                            </h2>

                            <form action="{{ route('client.process-booking', $event) }}" method="POST" class="space-y-6">
                                @csrf

                                <!-- Quantity Selection -->
                                <div>
                                    <label for="quantity" class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">
                                        Number of Tickets
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <button type="button" 
                                                onclick="decreaseQuantity()" 
                                                class="w-12 h-12 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 rounded-full flex items-center justify-center text-xl font-bold transition duration-200">
                                            −
                                        </button>
                                        
                                        <input type="number" 
                                               id="quantity" 
                                               name="quantity" 
                                               value="1" 
                                               min="1" 
                                               max="{{ min(10, $event->available_tickets) }}"
                                               class="w-20 h-12 text-center text-xl font-bold border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                               onchange="updateTotal()"
                                               required>
                                        
                                        <button type="button" 
                                                onclick="increaseQuantity()" 
                                                class="w-12 h-12 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 rounded-full flex items-center justify-center text-xl font-bold transition duration-200">
                                            +
                                        </button>
                                        
                                        <span class="text-gray-600 dark:text-gray-300">
                                            (Max: {{ min(10, $event->available_tickets) }} tickets)
                                        </span>
                                    </div>
                                    @error('quantity')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Total Price -->
                                @if(!$event->isFree())
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                                        <div class="flex justify-between items-center text-lg">
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Subtotal:</span>
                                            <span id="subtotal" class="font-bold text-gray-900 dark:text-white">
                                                ${{ number_format($event->price, 2) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center text-lg mt-2">
                                            <span class="font-medium text-gray-700 dark:text-gray-300">Service Fee:</span>
                                            <span id="service-fee" class="font-bold text-gray-900 dark:text-white">
                                                ${{ number_format($event->price * 0.05, 2) }}
                                            </span>
                                        </div>
                                        <div class="border-t border-gray-200 dark:border-gray-600 mt-4 pt-4">
                                            <div class="flex justify-between items-center text-xl">
                                                <span class="font-bold text-gray-900 dark:text-white">Total:</span>
                                                <span id="total" class="font-bold text-blue-600 dark:text-blue-400">
                                                    ${{ number_format($event->price * 1.05, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                                    <a href="{{ route('events.show', $event) }}" 
                                       class="flex-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white px-6 py-3 rounded-lg font-medium text-center transition duration-200">
                                        ← Back to Event
                                    </a>
                                    
                                    <button type="submit" 
                                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                        @if($event->isFree())
                                            🎉 Get Free Tickets
                                        @else
                                            💳 Proceed to Payment
                                        @endif
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Sold Out Message -->
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-8 text-center">
                            <div class="w-24 h-24 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                                <span class="text-4xl">🚫</span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                Event Sold Out
                            </h2>
                            <p class="text-gray-600 dark:text-gray-300 mb-6">
                                Unfortunately, all tickets for this event have been sold.
                            </p>
                            <a href="{{ route('events.index') }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                🔍 Find Other Events
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        const eventPrice = {{ $event->price ?? 0 }};
        const maxQuantity = {{ min(10, $event->available_tickets) }};

        function updateTotal() {
            const quantity = parseInt(document.getElementById('quantity').value) || 1;
            
            if (!{{ $event->isFree() ? 'true' : 'false' }}) {
                const subtotal = eventPrice * quantity;
                const serviceFee = subtotal * 0.05;
                const total = subtotal + serviceFee;
                
                document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
                document.getElementById('service-fee').textContent = '$' + serviceFee.toFixed(2);
                document.getElementById('total').textContent = '$' + total.toFixed(2);
            }
        }

        function increaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue < maxQuantity) {
                quantityInput.value = currentValue + 1;
                updateTotal();
            }
        }

        function decreaseQuantity() {
            const quantityInput = document.getElementById('quantity');
            const currentValue = parseInt(quantityInput.value) || 1;
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateTotal();
            }
        }

        // Initialize total on page load
        updateTotal();
    </script>
            </div>
        </div>
    </div>
@endsection
