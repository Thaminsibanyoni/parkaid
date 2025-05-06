<?php

namespace Database\Seeders;

use App\Models\ParkingSpace;
use App\Models\ParkingSpaceAvailability;
use App\Models\ParkingSpaceImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParkingSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get host users
        $host1 = User::where('email', 'host@parkaid.com')->first();
        $host2 = User::where('email', 'host2@parkaid.com')->first();

        // Create parking spaces for host1 (Cape Town)
        $space1 = ParkingSpace::create([
            'user_id' => $host1->id,
            'name' => 'Secure Parking in City Center',
            'description' => 'Secure parking space in the heart of Cape Town. 24/7 access with security cameras.',
            'address' => '123 Long Street',
            'city' => 'Cape Town',
            'province' => 'Western Cape',
            'postal_code' => '8001',
            'latitude' => -33.9249,
            'longitude' => 18.4241,
            'vehicle_type' => 'car',
            'price_per_hour' => 25.00,
            'price_per_day' => 150.00,
            'status' => 'active',
        ]);

        // Add images for space1
        ParkingSpaceImage::create([
            'parking_space_id' => $space1->id,
            'image_path' => 'parking_spaces/space1_1.jpg',
            'is_primary' => true,
        ]);

        ParkingSpaceImage::create([
            'parking_space_id' => $space1->id,
            'image_path' => 'parking_spaces/space1_2.jpg',
            'is_primary' => false,
        ]);

        // Add availability for space1
        for ($day = 0; $day < 7; $day++) {
            ParkingSpaceAvailability::create([
                'parking_space_id' => $space1->id,
                'day_of_week' => $day,
                'start_time' => '08:00:00',
                'end_time' => '20:00:00',
                'is_available' => true,
            ]);
        }

        $space2 = ParkingSpace::create([
            'user_id' => $host1->id,
            'name' => 'Waterfront Parking',
            'description' => 'Convenient parking near V&A Waterfront. Perfect for tourists and shoppers.',
            'address' => '45 Waterfront Road',
            'city' => 'Cape Town',
            'province' => 'Western Cape',
            'postal_code' => '8002',
            'latitude' => -33.9033,
            'longitude' => 18.4197,
            'vehicle_type' => 'car',
            'price_per_hour' => 30.00,
            'price_per_day' => 180.00,
            'status' => 'active',
        ]);

        // Add images for space2
        ParkingSpaceImage::create([
            'parking_space_id' => $space2->id,
            'image_path' => 'parking_spaces/space2_1.jpg',
            'is_primary' => true,
        ]);

        // Add availability for space2
        for ($day = 0; $day < 7; $day++) {
            ParkingSpaceAvailability::create([
                'parking_space_id' => $space2->id,
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '21:00:00',
                'is_available' => true,
            ]);
        }

        // Create parking spaces for host2 (Johannesburg)
        $space3 = ParkingSpace::create([
            'user_id' => $host2->id,
            'name' => 'Sandton Business District Parking',
            'description' => 'Secure parking in Sandton CBD. Close to Sandton City Mall and major businesses.',
            'address' => '87 Rivonia Road',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'postal_code' => '2196',
            'latitude' => -26.1052,
            'longitude' => 28.0567,
            'vehicle_type' => 'car',
            'price_per_hour' => 35.00,
            'price_per_day' => 200.00,
            'status' => 'active',
        ]);

        // Add images for space3
        ParkingSpaceImage::create([
            'parking_space_id' => $space3->id,
            'image_path' => 'parking_spaces/space3_1.jpg',
            'is_primary' => true,
        ]);

        // Add availability for space3
        for ($day = 1; $day < 6; $day++) { // Monday to Friday only
            ParkingSpaceAvailability::create([
                'parking_space_id' => $space3->id,
                'day_of_week' => $day,
                'start_time' => '07:00:00',
                'end_time' => '19:00:00',
                'is_available' => true,
            ]);
        }

        $space4 = ParkingSpace::create([
            'user_id' => $host2->id,
            'name' => 'Rosebank Mall Parking',
            'description' => 'Convenient parking near Rosebank Mall. Easy access to shops and restaurants.',
            'address' => '15 Cradock Avenue',
            'city' => 'Johannesburg',
            'province' => 'Gauteng',
            'postal_code' => '2196',
            'latitude' => -26.1467,
            'longitude' => 28.0437,
            'vehicle_type' => 'car',
            'price_per_hour' => 28.00,
            'price_per_day' => 170.00,
            'status' => 'active',
        ]);

        // Add images for space4
        ParkingSpaceImage::create([
            'parking_space_id' => $space4->id,
            'image_path' => 'parking_spaces/space4_1.jpg',
            'is_primary' => true,
        ]);

        // Add availability for space4
        for ($day = 0; $day < 7; $day++) {
            ParkingSpaceAvailability::create([
                'parking_space_id' => $space4->id,
                'day_of_week' => $day,
                'start_time' => '08:00:00',
                'end_time' => '22:00:00',
                'is_available' => true,
            ]);
        }
    }
}
