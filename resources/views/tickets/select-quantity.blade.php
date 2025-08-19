@extends('layouts.dashbord')
@section('content')
<div class="flex h-screen bg-gray-100">
    <x-dashboard-sidebar role="client" :current-route="request()->route()->getName()" />
<div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 lg:ml-0">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Event Information -->
                    <div class="mb-8">
                        <div class="flex items-start space-x-6">
                            @if($event->image_path)
                                <img src="{{ Storage::url($event->image_path) }}" 
                                     alt="{{ $event->title }}" 
                                     class="w-32 h-32 object-cover rounded-lg shadow-md">
                            @else
                                <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                                    <span class="text-white text-3xl font-bold">
                                        {{ substr($event->title, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $event->title }}</h1>
                                <p class="text-gray-600 dark:text-gray-300 mb-2">
                                    📅 {{ $event->start_date->format('F j, Y \a\t g:i A') }}
                                </p>
                                <p class="text-gray-600 dark:text-gray-300 mb-2">
                                    📍 {{ $event->location }}
                                </p>
                                <p class="text-gray-600 dark:text-gray-300">
                                    👤 Organized by {{ $event->organizer->name }}
                                </p>
                                
                                <!-- Event Type Badge -->
                                <div class="mt-3">
                                    @if($event->type === 'free')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            🎫 Free Event
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            💰 Paid Event - ${{ number_format($event->price, 2) }} per ticket
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Selection Form -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Select Number of Tickets</h2>
                        
                        <form action="{{ route('tickets.purchase', $event) }}" method="POST" id="ticket-form">
                            @csrf
                            
                            <!-- Quantity Selection -->
                            <div class="mb-6">
                                <label for="quantity" class="block text-lg font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    How many tickets do you need?
                                </label>

                                @php
                                    $maxAvailable = $event->max_tickets ? min(10, $event->max_tickets - $event->tickets_sold) : 10;
                                @endphp

                                <!-- Quantity Input with +/- Buttons -->
                                <div class="bg-white dark:bg-gray-600 rounded-xl p-6 border border-gray-200 dark:border-gray-500 mb-4">
                                    <div class="flex items-center justify-center space-x-6">
                                        <button type="button" id="decrease-btn"
                                                class="w-14 h-14 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-full flex items-center justify-center text-2xl font-bold transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg"
                                                onclick="changeQuantity(-1)">
                                            −
                                        </button>

                                        <div class="flex flex-col items-center">
                                            <input type="number" name="quantity" id="quantity-input"
                                                   value="1" min="1" max="{{ $maxAvailable }}"
                                                   class="w-24 h-16 text-center text-3xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-blue-500 bg-gray-50 dark:bg-gray-700 dark:text-white"
                                                   onchange="updateTotal()" oninput="validateQuantity()">
                                            <span class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium" id="ticket-label">ticket</span>
                                        </div>

                                        <button type="button" id="increase-btn"
                                                class="w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center text-2xl font-bold transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg"
                                                onclick="changeQuantity(1)">
                                            +
                                        </button>
                                    </div>

                                    <!-- Quick Select Buttons -->
                                    <div class="flex justify-center space-x-2 mt-4">
                                        @for($i = 1; $i <= min(5, $maxAvailable); $i++)
                                            <button type="button"
                                                    class="px-3 py-1 text-sm bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 rounded-full transition duration-200"
                                                    onclick="setQuantity({{ $i }})">
                                                {{ $i }}
                                            </button>
                                        @endfor
                                        @if($maxAvailable > 5)
                                            <button type="button"
                                                    class="px-3 py-1 text-sm bg-gray-100 hover:bg-blue-100 text-gray-700 hover:text-blue-700 rounded-full transition duration-200"
                                                    onclick="setQuantity({{ $maxAvailable }})">
                                                Max ({{ $maxAvailable }})
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Quantity Info -->
                                <div class="text-center mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Maximum: {{ $maxAvailable }} tickets
                                    </p>
                                    @if($maxAvailable < 10)
                                        <p class="text-sm text-orange-600 dark:text-orange-400 mt-1">
                                            ⚠️ Only {{ $maxAvailable }} tickets remaining
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Price Calculation -->
                            <div class="bg-white dark:bg-gray-600 rounded-lg p-4 mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Order Summary</h3>
                                
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-300">Quantity:</span>
                                        <span class="font-semibold" id="selected-quantity">1</span>
                                    </div>
                                    
                                    @if($event->type === 'paid')
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 dark:text-gray-300">Price per ticket:</span>
                                            <span class="font-semibold">${{ number_format($event->price, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 dark:text-gray-300">Subtotal:</span>
                                            <span class="font-semibold" id="subtotal">${{ number_format($event->price, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between items-center text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">Platform fee (15%):</span>
                                            <span class="text-gray-500" id="platform-fee">${{ number_format($event->price * 0.15, 2) }}</span>
                                        </div>
                                        <hr class="border-gray-300 dark:border-gray-500">
                                        <div class="flex justify-between items-center text-lg font-bold">
                                            <span>Total:</span>
                                            <span class="text-blue-600" id="total-price">${{ number_format($event->price * 1.15, 2) }}</span>
                                        </div>
                                    @else
                                        <div class="flex justify-between items-center text-lg font-bold text-green-600">
                                            <span>Total:</span>
                                            <span>FREE</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="{{ route('events.show', $event) }}" 
                                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-medium text-center transition duration-200">
                                    ← Back to Event
                                </a>
                                
                                <button type="submit" 
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                    @if($event->type === 'free')
                                        🎫 Get Free Tickets
                                    @else
                                        💳 Proceed to Payment
                                    @endif
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Event Details -->
                    <div class="mt-8 bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Event Details</h3>
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            {{ Str::limit($event->description, 200) }}
                        </p>
                        @if($event->terms_conditions)
                            <div class="mt-3 pt-3 border-t border-blue-200 dark:border-blue-700">
                                <p class="text-xs text-blue-700 dark:text-blue-300">
                                    <strong>Terms:</strong> {{ $event->terms_conditions }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript for Quantity Control and Price Calculation -->
    <script>
        const maxAvailable = {{ $maxAvailable }};
        const pricePerTicket = {{ $event->price ?? 0 }};
        const isFreeEvent = {{ $event->type === 'free' ? 'true' : 'false' }};

        function changeQuantity(change) {
            const input = document.getElementById('quantity-input');
            const currentValue = parseInt(input.value);
            const newValue = currentValue + change;

            if (newValue >= 1 && newValue <= maxAvailable) {
                input.value = newValue;
                updateTotal();
                updateButtons();
                updateTicketLabel();
            }
        }

        function setQuantity(quantity) {
            const input = document.getElementById('quantity-input');
            if (quantity >= 1 && quantity <= maxAvailable) {
                input.value = quantity;
                updateTotal();
                updateButtons();
                updateTicketLabel();
            }
        }

        function validateQuantity() {
            const input = document.getElementById('quantity-input');
            let value = parseInt(input.value);

            if (isNaN(value) || value < 1) {
                value = 1;
            } else if (value > maxAvailable) {
                value = maxAvailable;
            }

            input.value = value;
            updateTotal();
            updateButtons();
            updateTicketLabel();
        }

        function updateButtons() {
            const input = document.getElementById('quantity-input');
            const decreaseBtn = document.getElementById('decrease-btn');
            const increaseBtn = document.getElementById('increase-btn');
            const currentValue = parseInt(input.value);

            decreaseBtn.disabled = currentValue <= 1;
            increaseBtn.disabled = currentValue >= maxAvailable;
        }

        function updateTicketLabel() {
            const input = document.getElementById('quantity-input');
            const label = document.getElementById('ticket-label');
            const currentValue = parseInt(input.value);

            label.textContent = currentValue === 1 ? 'ticket' : 'tickets';
        }

        function updateTotal() {
            const input = document.getElementById('quantity-input');
            const selectedQuantity = parseInt(input.value);

            // Update quantity display
            document.getElementById('selected-quantity').textContent = selectedQuantity;

            if (!isFreeEvent) {
                // Calculate prices
                const subtotal = pricePerTicket * selectedQuantity;
                const platformFee = subtotal * 0.15;
                const total = subtotal + platformFee;

                // Update displays
                document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
                document.getElementById('platform-fee').textContent = '$' + platformFee.toFixed(2);
                document.getElementById('total-price').textContent = '$' + total.toFixed(2);
            }
        }

        // Keyboard support for quantity input
        document.addEventListener('keydown', function(e) {
            if (document.activeElement.id === 'quantity-input') {
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    changeQuantity(1);
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    changeQuantity(-1);
                }
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateTotal();
            updateButtons();
            updateTicketLabel();
        });
    </script>
@endsection
