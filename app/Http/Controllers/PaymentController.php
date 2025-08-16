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
     * Show checkout page for a ticket
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

        return view('payment.checkout', compact('ticket'));
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
    public function success(Ticket $ticket)
    {
        // Ensure the ticket belongs to the authenticated user
        if ($ticket->client_id !== Auth::id()) {
            abort(403);
        }

        if (!$ticket->isPaid()) {
            return redirect()->route('payment.checkout', $ticket)
                ->with('error', 'Payment not completed yet.');
        }

        $ticket->load(['event', 'payment']);

        return view('payment.success', compact('ticket'));
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
            case 'payment_intent.succeeded':
                $paymentIntent = $event['data']['object'];
                $this->handlePaymentSucceeded($paymentIntent);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event['data']['object'];
                $this->handlePaymentFailed($paymentIntent);
                break;

            default:
                // Unexpected event type
                return response('Unexpected event type', 400);
        }

        return response('Success', 200);
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
