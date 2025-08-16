<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\OrganizerProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
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

        echo "Admin user created: admin@eventmanagement.com / password\n";

        // Create Sample Organizer (Approved)
        $organizer1 = User::create([
            'name' => 'John Smith',
            'email' => 'organizer@eventmanagement.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        OrganizerProfile::create([
            'user_id' => $organizer1->id,
            'bio' => 'Experienced event organizer with 10+ years in the industry. Specializing in corporate events and conferences.',
            'company_name' => 'Smith Events Co.',
            'contact_info' => 'john@smithevents.com',
            'phone' => '+1-555-0123',
            'website' => 'https://smithevents.com',
        ]);

        echo "Approved organizer created: organizer@eventmanagement.com / password\n";

        // Create Sample Organizer (Pending Approval)
        $organizer2 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'pending@eventmanagement.com',
            'password' => Hash::make('password'),
            'role' => 'organizer',
            'is_approved' => false,
            'email_verified_at' => now(),
        ]);

        OrganizerProfile::create([
            'user_id' => $organizer2->id,
            'bio' => 'New event organizer passionate about creating memorable experiences. Looking to organize music and art events.',
            'company_name' => 'Creative Events LLC',
            'contact_info' => 'sarah@creativeevents.com',
            'phone' => '+1-555-0456',
            'website' => 'https://creativeevents.com',
        ]);

        echo "Pending organizer created: pending@eventmanagement.com / password\n";

        // Create Sample Clients
        $client1 = User::create([
            'name' => 'Mike Wilson',
            'email' => 'client1@eventmanagement.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        $client2 = User::create([
            'name' => 'Emily Davis',
            'email' => 'client2@eventmanagement.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'is_approved' => true,
            'email_verified_at' => now(),
        ]);

        echo "Sample clients created: client1@eventmanagement.com, client2@eventmanagement.com / password\n";

        echo "\n=== LOGIN CREDENTIALS ===\n";
        echo "Admin: admin@eventmanagement.com / password\n";
        echo "Organizer (Approved): organizer@eventmanagement.com / password\n";
        echo "Organizer (Pending): pending@eventmanagement.com / password\n";
        echo "Client 1: client1@eventmanagement.com / password\n";
        echo "Client 2: client2@eventmanagement.com / password\n";
        echo "========================\n";
    }
}
