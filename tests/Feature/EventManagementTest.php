<?php

use App\Models\User;
use App\Models\Event;
use App\Models\OrganizerProfile;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

beforeEach(function () {
    // Create test users
    $this->admin = User::create([
        'name' => 'Test Admin',
        'email' => 'admin@test.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);

    $this->organizer = User::create([
        'name' => 'Test Organizer',
        'email' => 'organizer@test.com',
        'password' => Hash::make('password'),
        'role' => 'organizer',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);

    OrganizerProfile::create([
        'user_id' => $this->organizer->id,
        'company_name' => 'Test Events Co.',
        'contact_info' => '+1 (555) 123-4567',
        'bio' => 'Test organizer profile',
    ]);

    $this->client = User::create([
        'name' => 'Test Client',
        'email' => 'client@test.com',
        'password' => Hash::make('password'),
        'role' => 'client',
        'is_approved' => true,
        'email_verified_at' => now(),
    ]);
});

test('admin can access dashboard', function () {
    $response = $this->actingAs($this->admin)->get('/admin/dashboard');
    $response->assertStatus(200);
    $response->assertSee('Admin Dashboard');
});

test('organizer can create event', function () {
    $eventData = [
        'title' => 'Test Event',
        'description' => 'This is a test event description.',
        'category' => 'Technology',
        'type' => 'paid',
        'price' => 50.00,
        'max_tickets' => 100,
        'start_date' => Carbon::now()->addDays(30)->format('Y-m-d\TH:i'),
        'location' => 'Test Venue',
        'address' => '123 Test Street',
        'terms_conditions' => 'Test terms and conditions',
    ];

    $response = $this->actingAs($this->organizer)
        ->post('/organizer/events', $eventData);

    $response->assertRedirect();
    $this->assertDatabaseHas('events', [
        'title' => 'Test Event',
        'organizer_id' => $this->organizer->id,
        'approved' => false, // Should require approval
    ]);
});

test('admin can approve event', function () {
    $event = Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Test Event',
        'description' => 'Test description',
        'category' => 'Technology',
        'type' => 'free',
        'start_date' => Carbon::now()->addDays(30),
        'location' => 'Test Location',
        'approved' => false,
    ]);

    $response = $this->actingAs($this->admin)
        ->post("/admin/events/{$event->id}/approve");

    $response->assertRedirect();
    $this->assertDatabaseHas('events', [
        'id' => $event->id,
        'approved' => true,
    ]);
});

test('public can view approved events', function () {
    Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Public Event',
        'description' => 'Test description',
        'category' => 'Technology',
        'type' => 'free',
        'start_date' => Carbon::now()->addDays(30),
        'location' => 'Test Location',
        'approved' => true,
    ]);

    $response = $this->get('/events');
    $response->assertStatus(200);
    $response->assertSee('Public Event');
});

test('unapproved events not visible to public', function () {
    Event::create([
        'organizer_id' => $this->organizer->id,
        'title' => 'Unapproved Event',
        'description' => 'Test description',
        'category' => 'Technology',
        'type' => 'free',
        'start_date' => Carbon::now()->addDays(30),
        'location' => 'Test Location',
        'approved' => false,
    ]);

    $response = $this->get('/events');
    $response->assertStatus(200);
    $response->assertDontSee('Unapproved Event');
});
