<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Payment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\TicketConfirmation;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Stripe\Exception\CardException;
use Stripe\Exception\ApiErrorException;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Show ticket quantity selection page
     */
    public function selectQuantity(Event $event)
    {
        // Check if user is a client
        if (!Auth::user()->isClient()) {
            return redirect()->back()->with('error', 'Only clients can purchase tickets.');
        }

        // Check if event is still upcoming
        if ($event->start_date <= now()) {
            return redirect()->back()->with('error', 'Cannot purchase tickets for past events.');
        }

        // Check if event is approved
        if (!$event->approved) {
            return redirect()->back()->with('error', 'This event is not available for booking.');
        }

        // Check if tickets are available
        if ($event->max_tickets && $event->tickets_sold >= $event->max_tickets) {
            return redirect()->back()->with('error', 'This event is sold out.');
        }

        return view('tickets.select-quantity', compact('event'));
    }

    /**
     * Purchase tickets for an event
     */
    public function purchaseTickets(Request $request, Event $event)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        // Check if user is a client
        if (!Auth::user()->isClient()) {
            return redirect()->back()->with('error', 'Only clients can purchase tickets.');
        }

        // Check if event is still upcoming
        if ($event->start_date <= now()) {
            return redirect()->back()->with('error', 'Cannot purchase tickets for past events.');
        }

        // Check if event is approved
        if (!$event->approved) {
            return redirect()->back()->with('error', 'This event is not available for booking.');
        }

        // Check ticket availability
        if ($event->max_tickets && ($event->tickets_sold + $request->quantity) > $event->max_tickets) {
            return redirect()->back()->with('error', 'Not enough tickets available.');
        }

        // Calculate total price
        $totalPrice = $event->type === 'free' ? 0 : ($event->price * $request->quantity);

        // Create ticket record
        $ticket = Ticket::create([
            'event_id' => $event->id,
            'client_id' => Auth::id(),
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'payment_status' => $event->type === 'free' ? 'paid' : 'unpaid',
            'ticket_code' => 'TKT-' . strtoupper(Str::random(8)),
        ]);

        // Update tickets sold count
        $event->increment('tickets_sold', $request->quantity);

        // For free events, mark as complete and send email
        if ($event->type === 'free') {
            // Send confirmation email
            try {
                Mail::to(Auth::user())->send(new TicketConfirmation($ticket));
            } catch (\Exception $e) {
                // Log email error but don't fail the ticket creation
                Log::error('Failed to send ticket confirmation email: ' . $e->getMessage());
            }

            return redirect()->route('payment.success', $ticket)
                ->with('success', 'Free tickets obtained successfully! Check your email for confirmation.');
        }

        // For paid events, redirect to payment
        return redirect()->route('payment.checkout', $ticket)
            ->with('success', 'Tickets reserved! Please complete payment.');
    }

    /**
     * Create Stripe Checkout Session and redirect to hosted checkout
     */
    public function checkout(Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        if ($ticket->client_id !== Auth::id()) {
            abort(403);
        }

        // Check if ticket is already paid
        if ($ticket->isPaid()) {
            return redirect()->route('client.ticket-details', $ticket)
                ->with('info', 'This ticket is already paid.');
        }

        // Check if event is still available
        if (!$ticket->event->hasAvailableTickets()) {
            return redirect()->route('events.show', $ticket->event)
                ->with('error', 'This event is no longer available.');
        }

        $ticket->load('event');

        try {
            // Calculate total amount including platform fee (5%)
            $subtotal = $ticket->total_price;
            $platformFee = $subtotal * 0.05;
            $totalAmount = $subtotal + $platformFee;

            // Create Stripe Checkout Session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $ticket->event->title,
                                'description' => "Event Date: " . $ticket->event->start_date->format('F j, Y \a\t g:i A') .
                                               "\nLocation: " . $ticket->event->location .
                                               "\nQuantity: " . $ticket->quantity . " ticket(s)",
                            ],
                            'unit_amount' => round($totalAmount * 100), // Convert to cents
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('payment.success', $ticket) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel', $ticket),
                'metadata' => [
                    'ticket_id' => $ticket->id,
                    'event_id' => $ticket->event_id,
                    'client_id' => $ticket->client_id,
                    'subtotal' => round($subtotal * 100),
                    'platform_fee' => round($platformFee * 100),
                    'total_amount' => round($totalAmount * 100),
                ],
                'customer_email' => Auth::user()->email,
                'expires_at' => now()->addMinutes(30)->timestamp, // Session expires in 30 minutes
            ]);

            // Store the session ID in the ticket for reference
            $ticket->update([
                'stripe_session_id' => $session->id,
                'payment_status' => 'pending'
            ]);

            // Redirect to Stripe Checkout
            return redirect($session->url);

        } catch (ApiErrorException $e) {
            Log::error('Stripe Checkout Session creation failed: ' . $e->getMessage());
            return redirect()->route('events.show', $ticket->event)
                ->with('error', 'Unable to process payment at this time. Please try again.');
        }
    }

    /**
     * Create payment intent for Stripe
     */
    public function createPaymentIntent(Request $request, Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        if ($ticket->client_id !== Auth::id()) {
            abort(403);
        }

        if ($ticket->isPaid()) {
            return response()->json(['error' => 'Ticket is already paid'], 400);
        }

        try {
            // Calculate total amount including platform fee (15%)
            $subtotal = $ticket->total_price;
            $platformFee = $subtotal * 0.15;
            $totalAmount = ($subtotal + $platformFee) * 100; // Convert to cents

            $adminFee = $platformFee * 100; // Platform fee in cents
            $organizerAmount = $subtotal * 100; // Organizer amount in cents

            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount,
                'currency' => 'usd',
                'metadata' => [
                    'ticket_id' => $ticket->id,
                    'event_id' => $ticket->event_id,
                    'client_id' => $ticket->client_id,
                    'subtotal' => $subtotal * 100,
                    'admin_fee' => $adminFee,
                    'organizer_amount' => $organizerAmount,
                ],
                'description' => "Ticket for: {$ticket->event->title} (Qty: {$ticket->quantity})",
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ]);

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Confirm payment and update records
     */
    public function confirmPayment(Request $request, Ticket $ticket)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        // Ensure the ticket belongs to the authenticated user
        if ($ticket->client_id !== Auth::id()) {
            abort(403);
        }

        try {
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                // Calculate fees
                $amount = $ticket->total_price;
                $adminFee = Payment::calculateAdminFee($amount);
                $organizerAmount = Payment::calculateOrganizerAmount($amount);

                // Create payment record
                $payment = Payment::create([
                    'event_id' => $ticket->event_id,
                    'client_id' => $ticket->client_id,
                    'ticket_id' => $ticket->id,
                    'amount' => $amount,
                    'admin_fee' => $adminFee,
                    'organizer_amount' => $organizerAmount,
                    'stripe_payment_id' => $paymentIntent->id,
                    'stripe_payment_intent_id' => $paymentIntent->id,
                    'status' => 'completed',
                    'stripe_response' => $paymentIntent->toArray(),
                ]);

                // Update ticket status
                $ticket->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $paymentIntent->id,
                ]);

                // Send confirmation email
                try {
                    Mail::to(Auth::user())->send(new TicketConfirmation($ticket));
                } catch (\Exception $e) {
                    // Log email error but don't fail the payment
                    Log::error('Failed to send ticket confirmation email: ' . $e->getMessage());
                }

                return response()->json([
                    'success' => true,
                    'redirect_url' => route('payment.success', $ticket),
                ]);
            } else {
                return response()->json(['error' => 'Payment not completed'], 400);
            }

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle payment success page
     */
    public function success(Request $request, Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        if ($ticket->client_id !== Auth::id()) {
            abort(403);
        }

        // If session_id is provided, verify the Stripe session
        if ($request->has('session_id')) {
            try {
                $session = Session::retrieve($request->session_id);

                // Verify this session belongs to this ticket
                if ($session->metadata->ticket_id != $ticket->id) {
                    return redirect()->route('events.show', $ticket->event)
                        ->with('error', 'Invalid payment session.');
                }

                // If payment is successful but ticket not yet marked as paid
                if ($session->payment_status === 'paid' && !$ticket->isPaid()) {
                    $this->processSuccessfulPayment($ticket, $session);
                }

            } catch (ApiErrorException $e) {
                Log::error('Error retrieving Stripe session: ' . $e->getMessage());
                return redirect()->route('events.show', $ticket->event)
                    ->with('error', 'Unable to verify payment. Please contact support.');
            }
        }

        // Check if ticket is paid
        if (!$ticket->isPaid()) {
            return redirect()->route('payment.checkout', $ticket)
                ->with('error', 'Payment not completed yet.');
        }

        $ticket->load(['event', 'payment']);

        return view('payment.success', compact('ticket'));
    }

    /**
     * Process successful payment from Stripe Checkout Session
     */
    private function processSuccessfulPayment(Ticket $ticket, $session)
    {
        try {
            // Get payment intent from session
            $paymentIntent = PaymentIntent::retrieve($session->payment_intent);

            // Calculate amounts
            $totalAmount = $session->amount_total / 100; // Convert from cents
            $subtotal = $session->metadata->subtotal / 100;
            $platformFee = $session->metadata->platform_fee / 100;
            $organizerAmount = $subtotal - $platformFee;

            // Create payment record
            $payment = Payment::create([
                'event_id' => $ticket->event_id,
                'client_id' => $ticket->client_id,
                'ticket_id' => $ticket->id,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'stripe_session_id' => $session->id,
                'amount' => $subtotal,
                'admin_fee' => $platformFee,
                'organizer_amount' => $organizerAmount,
                'total_amount' => $totalAmount,
                'payment_method' => 'card',
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            // Update ticket status
            $ticket->update([
                'payment_status' => 'paid',
                'payment_id' => $payment->id,
            ]);

            // Send confirmation email
            try {
                Mail::to($ticket->client)->send(new TicketConfirmation($ticket));
                Log::info("Ticket confirmation email sent for ticket ID: {$ticket->id}");
            } catch (\Exception $e) {
                Log::error("Failed to send ticket confirmation email for ticket ID {$ticket->id}: " . $e->getMessage());
            }

            Log::info("Payment processed successfully for ticket ID: {$ticket->id}");

        } catch (\Exception $e) {
            Log::error("Error processing successful payment for ticket ID {$ticket->id}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle payment cancellation
     */
    public function cancel(Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        if ($ticket->client_id !== Auth::id()) {
            abort(403);
        }

        return view('payment.cancel', compact('ticket'));
    }

    /**
     * Process refund
     */
    public function refund(Request $request, Payment $payment)
    {
        // Only admins can process refunds for now
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        try {
            $refund = \Stripe\Refund::create([
                'payment_intent' => $payment->stripe_payment_intent_id,
                'amount' => $payment->amount * 100, // Convert to cents
            ]);

            // Update payment status
            $payment->update([
                'status' => 'refunded',
                'stripe_response' => array_merge(
                    $payment->stripe_response ?? [],
                    ['refund' => $refund->toArray()]
                ),
            ]);

            // Update ticket status
            $payment->ticket->update([
                'payment_status' => 'refunded',
            ]);

            // Update event tickets sold count
            $payment->event->decrement('tickets_sold', $payment->ticket->quantity);

            return redirect()->back()->with('success', 'Refund processed successfully.');

        } catch (ApiErrorException $e) {
            return redirect()->back()->with('error', 'Refund failed: ' . $e->getMessage());
        }
    }

    /**
     * Webhook handler for Stripe events
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('Invalid signature', 400);
        }

        // Handle the event
        switch ($event['type']) {
            case 'checkout.session.completed':
                $session = $event['data']['object'];
                $this->handleCheckoutSessionCompleted($session);
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event['data']['object'];
                $this->handlePaymentSucceeded($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event['data']['object'];
                $this->handlePaymentFailed($paymentIntent);
                break;

            default:
                // Log unexpected event type but don't return error
                Log::info('Received unexpected Stripe webhook event type: ' . $event['type']);
                break;
        }

        return response('Success', 200);
    }

    /**
     * Handle Stripe Checkout Session completion
     */
    private function handleCheckoutSessionCompleted($session)
    {
        $ticketId = $session['metadata']['ticket_id'] ?? null;

        if ($ticketId) {
            $ticket = Ticket::find($ticketId);

            if ($ticket && !$ticket->isPaid()) {
                try {
                    // Get the Stripe Session object for more details
                    $stripeSession = Session::retrieve($session['id']);
                    $this->processSuccessfulPayment($ticket, $stripeSession);

                    Log::info("Checkout session completed for ticket ID: {$ticketId}");
                } catch (\Exception $e) {
                    Log::error("Error processing checkout session completion for ticket ID {$ticketId}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Handle successful payment webhook
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        $ticketId = $paymentIntent['metadata']['ticket_id'] ?? null;
        
        if ($ticketId) {
            $ticket = Ticket::find($ticketId);
            
            if ($ticket && !$ticket->isPaid()) {
                // Update ticket and create payment record if not already done
                $this->createPaymentFromWebhook($ticket, $paymentIntent);
            }
        }
    }

    /**
     * Handle failed payment webhook
     */
    private function handlePaymentFailed($paymentIntent)
    {
        $ticketId = $paymentIntent['metadata']['ticket_id'] ?? null;
        
        if ($ticketId) {
            $ticket = Ticket::find($ticketId);
            
            if ($ticket) {
                $ticket->update(['payment_status' => 'failed']);
                
                // Optionally, you could decrement the tickets_sold count
                // $ticket->event->decrement('tickets_sold', $ticket->quantity);
            }
        }
    }

    /**
     * Create payment record from webhook
     */
    private function createPaymentFromWebhook(Ticket $ticket, $paymentIntent)
    {
        $amount = $ticket->total_price;
        $adminFee = Payment::calculateAdminFee($amount);
        $organizerAmount = Payment::calculateOrganizerAmount($amount);

        Payment::create([
            'event_id' => $ticket->event_id,
            'client_id' => $ticket->client_id,
            'ticket_id' => $ticket->id,
            'amount' => $amount,
            'admin_fee' => $adminFee,
            'organizer_amount' => $organizerAmount,
            'stripe_payment_id' => $paymentIntent['id'],
            'stripe_payment_intent_id' => $paymentIntent['id'],
            'status' => 'completed',
            'stripe_response' => $paymentIntent,
        ]);

        $ticket->update([
            'payment_status' => 'paid',
            'transaction_id' => $paymentIntent['id'],
        ]);
    }
}
