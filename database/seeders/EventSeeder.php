<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get approved organizers
        $organizers = User::where('role', 'organizer')->where('is_approved', true)->get();

        if ($organizers->isEmpty()) {
            $this->command->error('No approved organizers found. Please run UserSeeder first.');
            return;
        }

        $categories = ['Concerts', 'Festivals', 'Theatre', 'Sports', 'Cinema', 'Business', 'Technology', 'Arts & Culture', 'Education', 'Food & Drink', 'Health & Wellness'];

        $events = [
            [
                'title' => 'Summer Music Festival 2025',
                'description' => 'Join us for the biggest music festival of the year featuring top artists from around the world. Experience three days of non-stop music, food, and entertainment.',
                'category' => 'Festivals',
                'type' => 'paid',
                'price' => 150.00,
                'max_tickets' => 5000,
                'start_date' => Carbon::now()->addDays(30),
                'location' => 'Central Park Amphitheater',
                'address' => '123 Park Avenue, New York, NY 10001',
                'approved' => true,
                'terms_conditions' => 'No outside food or drinks. Age 18+ only. Tickets are non-refundable.',
            ],
            [
                'title' => 'Tech Innovation Conference',
                'description' => 'Discover the latest trends in technology and innovation. Network with industry leaders and learn about cutting-edge developments in AI, blockchain, and more.',
                'category' => 'Technology',
                'type' => 'paid',
                'price' => 299.00,
                'max_tickets' => 500,
                'start_date' => Carbon::now()->addDays(45),
                'location' => 'Convention Center',
                'address' => '456 Tech Boulevard, San Francisco, CA 94105',
                'approved' => true,
                'terms_conditions' => 'Professional attire required. Lunch included.',
            ],
            [
                'title' => 'Community Art Workshop',
                'description' => 'Free art workshop for all ages. Learn basic painting techniques and create your own masterpiece to take home.',
                'category' => 'Arts & Culture',
                'type' => 'free',
                'price' => null,
                'max_tickets' => 50,
                'start_date' => Carbon::now()->addDays(15),
                'location' => 'Community Center',
                'address' => '789 Main Street, Austin, TX 78701',
                'approved' => true,
                'terms_conditions' => 'All materials provided. Children under 12 must be accompanied by an adult.',
            ],
            [
                'title' => 'Shakespeare in the Park',
                'description' => 'Classic performance of Romeo and Juliet under the stars. Bring your blankets and enjoy this timeless tale in a beautiful outdoor setting.',
                'category' => 'Theatre',
                'type' => 'free',
                'price' => null,
                'max_tickets' => 200,
                'start_date' => Carbon::now()->addDays(20),
                'location' => 'Riverside Park',
                'address' => '321 River Road, Portland, OR 97201',
                'approved' => true,
                'terms_conditions' => 'Weather dependent. Bring your own seating.',
            ],
            [
                'title' => 'Local Food & Wine Tasting',
                'description' => 'Sample the best local cuisine and wines from regional vendors. Meet local chefs and learn about sustainable farming practices.',
                'category' => 'Food & Drink',
                'type' => 'paid',
                'price' => 75.00,
                'max_tickets' => 100,
                'start_date' => Carbon::now()->addDays(25),
                'location' => 'Downtown Plaza',
                'address' => '654 Market Street, Seattle, WA 98101',
                'approved' => true,
                'terms_conditions' => 'Must be 21+ for wine tasting. Valid ID required.',
            ],
        ];

        foreach ($events as $index => $eventData) {
            $organizer = $organizers[$index % $organizers->count()];

            Event::create([
                'organizer_id' => $organizer->id,
                'title' => $eventData['title'],
                'description' => $eventData['description'],
                'category' => $eventData['category'],
                'type' => $eventData['type'],
                'price' => $eventData['price'],
                'max_tickets' => $eventData['max_tickets'],
                'tickets_sold' => 0,
                'start_date' => $eventData['start_date'],
                'location' => $eventData['location'],
                'address' => $eventData['address'],
                'approved' => $eventData['approved'],
                'terms_conditions' => $eventData['terms_conditions'],
            ]);
        }

        // Create some pending events for testing admin approval
        $pendingEvents = [
            [
                'title' => 'New Year Celebration',
                'description' => 'Ring in the new year with live music, fireworks, and celebration.',
                'category' => 'Festivals',
                'type' => 'free',
                'price' => null,
                'max_tickets' => 1000,
                'start_date' => Carbon::now()->addDays(60),
                'location' => 'City Square',
                'address' => '100 City Plaza, Chicago, IL 60601',
                'approved' => false,
            ],
            [
                'title' => 'Business Networking Event',
                'description' => 'Connect with local business leaders and entrepreneurs.',
                'category' => 'Business',
                'type' => 'paid',
                'price' => 50.00,
                'max_tickets' => 75,
                'start_date' => Carbon::now()->addDays(35),
                'location' => 'Business Center',
                'address' => '200 Corporate Drive, Miami, FL 33101',
                'approved' => false,
            ],
        ];

        foreach ($pendingEvents as $eventData) {
            $organizer = $organizers->random();

            Event::create([
                'organizer_id' => $organizer->id,
                'title' => $eventData['title'],
                'description' => $eventData['description'],
                'category' => $eventData['category'],
                'type' => $eventData['type'],
                'price' => $eventData['price'],
                'max_tickets' => $eventData['max_tickets'],
                'tickets_sold' => 0,
                'start_date' => $eventData['start_date'],
                'location' => $eventData['location'],
                'address' => $eventData['address'],
                'approved' => $eventData['approved'],
            ]);
        }

        $this->command->info('Events seeded successfully!');
        $this->command->info('Created ' . count($events) . ' approved events and ' . count($pendingEvents) . ' pending events.');
    }
}
