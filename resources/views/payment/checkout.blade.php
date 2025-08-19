@extends('layouts.dashbord')
@section('content')
<div class="flex h-screen bg-gray-100">
    <x-dashboard-sidebar role="client" :current-route="request()->route()->getName()" />
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Event Information -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Complete Your Purchase</h3>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
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
                                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        {{ $ticket->event->title }}
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-300 mt-1">
                                        📅 {{ $ticket->event->start_date->format('F j, Y \a\t g:i A') }}
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        📍 {{ $ticket->event->location }}
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        👤 Organized by {{ $ticket->event->organizer->name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ticket Details</h4>
                        
                        <div class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-300">Quantity:</span>
                                <span class="font-semibold">{{ $ticket->quantity }} ticket(s)</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-300">Price per ticket:</span>
                                <span class="font-semibold">${{ number_format($ticket->event->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-300">Subtotal:</span>
                                <span class="font-semibold">${{ number_format($ticket->total_price, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 dark:text-gray-300">Platform fee (15%):</span>
                                <span class="font-semibold">${{ number_format($ticket->total_price * 0.15, 2) }}</span>
                            </div>
                            <hr class="my-3 border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-blue-600">${{ number_format($ticket->total_price * 1.15, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div class="mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Information</h4>
                        
                        <form id="payment-form" class="space-y-6">
                            @csrf
                            
                            <!-- Stripe Elements will be inserted here -->
                            <div id="card-element" class="p-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white">
                                <!-- Stripe Elements will create form elements here -->
                            </div>
                            
                            <!-- Error messages -->
                            <div id="card-errors" role="alert" class="text-red-600 text-sm hidden"></div>
                            
                            <!-- Submit button -->
                            <button type="submit" id="submit-button" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span id="button-text">
                                    💳 Pay ${{ number_format($ticket->total_price * 1.15, 2) }}
                                </span>
                                <span id="spinner" class="hidden">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </form>
                    </div>

                    <!-- Security Notice -->
                    <div class="bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-green-800 dark:text-green-200">
                                    <strong>Secure Payment:</strong> Your payment information is processed securely by Stripe. We never store your credit card details.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Cancel Link -->
                    <div class="mt-6 text-center">
                        <a href="{{ route('events.show', $ticket->event) }}" 
                           class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 text-sm">
                            ← Cancel and return to event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Stripe JavaScript -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Initialize Stripe
        const stripe = Stripe('{{ config("services.stripe.key") }}');
        const elements = stripe.elements();

        // Create card element
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#9e2146',
                },
            },
        });

        // Mount card element
        cardElement.mount('#card-element');

        // Handle form submission
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');
        const cardErrors = document.getElementById('card-errors');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            // Disable submit button and show spinner
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');

            try {
                // Create payment intent
                const response = await fetch('{{ route("payment.create-intent", $ticket) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                });

                if (!response.ok) {
                    throw new Error('Failed to create payment intent');
                }

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                const { client_secret } = data;

                // Confirm payment with Stripe
                const { error, paymentIntent } = await stripe.confirmCardPayment(client_secret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: '{{ Auth::user()->name }}',
                            email: '{{ Auth::user()->email }}',
                        },
                    }
                });

                if (error) {
                    // Show error to customer
                    cardErrors.textContent = error.message;
                    cardErrors.classList.remove('hidden');
                    
                    // Re-enable submit button
                    submitButton.disabled = false;
                    buttonText.classList.remove('hidden');
                    spinner.classList.add('hidden');
                } else {
                    // Payment succeeded, redirect to success page
                    window.location.href = '{{ route("payment.success", $ticket) }}';
                }
            } catch (error) {
                console.error('Payment error:', error);
                cardErrors.textContent = 'An unexpected error occurred. Please try again.';
                cardErrors.classList.remove('hidden');
                
                // Re-enable submit button
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }
        });

        // Handle real-time validation errors from the card Element
        cardElement.on('change', ({ error }) => {
            if (error) {
                cardErrors.textContent = error.message;
                cardErrors.classList.remove('hidden');
            } else {
                cardErrors.textContent = '';
                cardErrors.classList.add('hidden');
            }
        });
    </script>
@endsection
