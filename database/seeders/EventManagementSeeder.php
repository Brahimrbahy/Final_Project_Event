<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the approved organizer
        $organizer = User::where('email', 'organizer@eventmanagement.com')->first();
        $clients = User::where('role', 'client')->get();

        if (!$organizer) {
            echo "Please run AdminUserSeeder first!\n";
            return;
        }

        // Create sample events
        $events = [
            [
                'title' => 'Tech Conference 2024',
                'description' => 'Annual technology conference featuring the latest innovations in AI, blockchain, and web development. Join industry leaders and networking opportunities.',
                'category' => 'Technology',
                'type' => 'paid',
                'price' => 299.99,
                'max_tickets' => 500,
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(32),
                'location' => 'Convention Center',
                'address' => '123 Main St, Downtown',
                'approved' => true,
            ],
            [
                'title' => 'Community Art Workshop',
                'description' => 'Free art workshop for all ages. Learn painting, drawing, and sculpture techniques from local artists.',
                'category' => 'Arts & Culture',
                'type' => 'free',
                'price' => null,
                'max_tickets' => 50,
                'start_date' => Carbon::now()->addDays(15),
                'end_date' => Carbon::now()->addDays(15)->addHours(4),
                'location' => 'Community Center',
                'address' => '456 Oak Ave, Midtown',
                'approved' => true,
            ],
            [
                'title' => 'Business Networking Mixer',
                'description' => 'Professional networking event for entrepreneurs and business professionals. Includes dinner and drinks.',
                'category' => 'Business',
                'type' => 'paid',
                'price' => 75.00,
                'max_tickets' => 100,
                'start_date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addDays(20)->addHours(3),
                'location' => 'Grand Hotel',
                'address' => '789 Business Blvd, Financial District',
                'approved' => true,
            ],
            [
                'title' => 'Music Festival 2024',
                'description' => 'Three-day music festival featuring local and international artists across multiple genres.',
                'category' => 'Music',
                'type' => 'paid',
                'price' => 150.00,
                'max_tickets' => 2000,
                'start_date' => Carbon::now()->addDays(60),
                'end_date' => Carbon::now()->addDays(62),
                'location' => 'City Park',
                'address' => '321 Park Lane, Riverside',
                'approved' => true,
            ],
            [
                'title' => 'Startup Pitch Competition',
                'description' => 'Entrepreneurs pitch their innovative ideas to a panel of investors. Open to all attendees.',
                'category' => 'Business',
                'type' => 'free',
                'price' => null,
                'max_tickets' => 200,
                'start_date' => Carbon::now()->addDays(45),
                'end_date' => Carbon::now()->addDays(45)->addHours(6),
                'location' => 'Innovation Hub',
                'address' => '654 Startup St, Tech District',
                'approved' => false, // Pending approval
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::create([
                'organizer_id' => $organizer->id,
                'title' => $eventData['title'],
                'description' => $eventData['description'],
                'category' => $eventData['category'],
                'type' => $eventData['type'],
                'price' => $eventData['price'],
                'max_tickets' => $eventData['max_tickets'],
                'tickets_sold' => 0,
                'start_date' => $eventData['start_date'],
                'end_date' => $eventData['end_date'],
                'location' => $eventData['location'],
                'address' => $eventData['address'],
                'approved' => $eventData['approved'],
            ]);

            echo "Created event: {$event->title}\n";

            // Create some sample tickets for approved events
            if ($event->approved && $clients->count() > 0) {
                $ticketCount = rand(5, 20);

                for ($i = 0; $i < $ticketCount; $i++) {
                    $client = $clients->random();
                    $quantity = rand(1, 3);

                    $ticket = Ticket::create([
                        'event_id' => $event->id,
                        'client_id' => $client->id,
                        'quantity' => $quantity,
                        'total_price' => $event->isFree() ? 0 : ($event->price * $quantity),
                        'payment_status' => 'paid',
                    ]);

                    // Create payment record for paid events
                    if ($event->isPaid()) {
                        Payment::create([
                            'event_id' => $event->id,
                            'client_id' => $client->id,
                            'ticket_id' => $ticket->id,
                            'amount' => $ticket->total_price,
                            'admin_fee' => Payment::calculateAdminFee($ticket->total_price),
                            'organizer_amount' => Payment::calculateOrganizerAmount($ticket->total_price),
                            'status' => 'completed',
                        ]);
                    }

                    // Update tickets sold
                    $event->increment('tickets_sold', $quantity);
                }
            }
        }

        echo "\nSample events and tickets created successfully!\n";
    }
}
