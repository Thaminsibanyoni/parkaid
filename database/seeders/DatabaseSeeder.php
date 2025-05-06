
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checking
        DB::statement('PRAGMA foreign_keys = OFF');

        // Truncate tables
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('user_profiles')->truncate();
        DB::table('parking_spaces')->truncate();
        DB::table('parking_space_images')->truncate();
        DB::table('parking_space_availability')->truncate();
        DB::table('bookings')->truncate();
        DB::table('payments')->truncate();
        DB::table('messages')->truncate();
        DB::table('reviews')->truncate();

        // Enable foreign key checking
        DB::statement('PRAGMA foreign_keys = ON');

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ParkingSpaceSeeder::class,
            BookingSeeder::class,
            MessageSeeder::class,
        ]);
    }
}
