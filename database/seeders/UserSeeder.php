<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\OrganizerProfile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@eventmanagement.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        // Create Approved Organizers
        $organizer1 = User::create([
            'name' => 'John Smith',
            'email' => 'john@eventorganizer.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        OrganizerProfile::create([
            'user_id' => $organizer1->id,
            'company_name' => 'Smith Events Co.',
            'contact_info' => '+1 (555) 123-4567',
            'bio' => 'Professional event organizer with 10+ years of experience in corporate and entertainment events.',
            'website' => 'https://smithevents.com',
        ]);

        $organizer2 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@musicevents.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        OrganizerProfile::create([
            'user_id' => $organizer2->id,
            'company_name' => 'Music Events Ltd.',
            'contact_info' => '+1 (555) 987-6543',
            'bio' => 'Specializing in music concerts and festivals. We bring the best artists to your city.',
            'website' => 'https://musicevents.com',
        ]);

        $organizer3 = User::create([
            'name' => 'Mike Wilson',
            'email' => 'mike@techconferences.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        OrganizerProfile::create([
            'user_id' => $organizer3->id,
            'company_name' => 'Tech Conferences Inc.',
            'contact_info' => '+1 (555) 456-7890',
            'bio' => 'Leading technology conference organizer. We host cutting-edge tech events worldwide.',
            'website' => 'https://techconferences.com',
        ]);

        // Create Pending Organizer (for testing approval workflow)
        $pendingOrganizer = User::create([
            'name' => 'Emma Davis',
            'email' => 'emma@newevents.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'is_approved' => false,
            'email_verified_at' => now(),
        ]);

        OrganizerProfile::create([
            'user_id' => $pendingOrganizer->id,
            'company_name' => 'New Events Agency',
            'contact_info' => '+1 (555) 111-2222',
            'bio' => 'New event organizing company focusing on community and cultural events.',
            'website' => 'https://newevents.com',
        ]);

        // Create Client Users
        $clients = [
            ['name' => 'Alice Brown', 'email' => 'alice@example.com'],
            ['name' => 'Bob Green', 'email' => 'bob@example.com'],
            ['name' => 'Carol White', 'email' => 'carol@example.com'],
            ['name' => 'David Black', 'email' => 'david@example.com'],
            ['name' => 'Eva Gray', 'email' => 'eva@example.com'],
        ];

        foreach ($clients as $clientData) {
            User::create([
                'name' => $clientData['name'],
                'email' => $clientData['email'],
                'password' => Hash::make('password'),
                'role' => 'client',
                'is_approved' => true,
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: admin@eventmanagement.com / password');
        $this->command->info('Organizers: john@eventorganizer.com, sarah@musicevents.com, mike@techconferences.com / password');
        $this->command->info('Pending Organizer: emma@newevents.com / password');
        $this->command->info('Clients: alice@example.com, bob@example.com, etc. / password');
    }
}
