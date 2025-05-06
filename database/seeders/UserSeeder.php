<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get role IDs
        $adminRoleId = Role::where('name', 'admin')->first()->id;
        $hostRoleId = Role::where('name', 'host')->first()->id;
        $renterRoleId = Role::where('name', 'renter')->first()->id;

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@parkaid.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRoleId,
            'phone' => '+27123456789',
        ]);

        UserProfile::create([
            'user_id' => $admin->id,
            'address' => '123 Admin Street',
            'city' => 'Cape Town',
            'province' => 'Western Cape',
            'postal_code' => '8001',
            'verified' => true,
        ]);

        // Create host users
        $host1 = User::create([
            'name' => 'Host User',
            'email' => 'host@parkaid.com',
            'password' => Hash::make('password'),
            'role_id' => $hostRoleId,
            'phone' => '+27123456790',
        ]);

        UserProfile::create([
            'user_id' => $host1->id,
            'address' => '456 Host Avenue',
            'city' => 'Cape Town',
            'province' => 'Western Cape',
            'postal_code' => '8001',
            'verified' => true,
        ]);

        $host2 = User::create([
            'name' => 'Second Host',
            'email' => 'host2@parkaid.com',
            'password' => Hash::make('password'),
            'role_id' => $hostRoleId,
            'phone' => '+27123456791',
        ]);

        UserProfile::create([
            'user_id' => $host2->id,
            'address' => '789 Host Boulevard',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'postal_code' => '2000',
            'verified' => true,
        ]);

        // Create renter users
        $renter1 = User::create([
            'name' => 'Renter User',
            'email' => 'renter@parkaid.com',
            'password' => Hash::make('password'),
            'role_id' => $renterRoleId,
            'phone' => '+27123456792',
        ]);

        UserProfile::create([
            'user_id' => $renter1->id,
            'address' => '101 Renter Road',
            'city' => 'Cape Town',
            'province' => 'Western Cape',
            'postal_code' => '8001',
            'verified' => true,
        ]);

        $renter2 = User::create([
            'name' => 'Second Renter',
            'email' => 'renter2@parkaid.com',
            'password' => Hash::make('password'),
            'role_id' => $renterRoleId,
            'phone' => '+27123456793',
        ]);

        UserProfile::create([
            'user_id' => $renter2->id,
            'address' => '202 Renter Street',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'postal_code' => '2000',
            'verified' => true,
        ]);
    }
}
