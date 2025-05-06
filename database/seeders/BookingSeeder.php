<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\ParkingSpace;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and parking spaces
        $renter1 = User::where('email', 'renter@parkaid.com')->first();
        $renter2 = User::where('email', 'renter2@parkaid.com')->first();
        
        $capeTownSpaces = ParkingSpace::where('city', 'Cape Town')->get();
        $joburgSpaces = ParkingSpace::where('city', 'Johannesburg')->get();

        // Create completed bookings for renter1 in Cape Town
        $pastBooking1 = Booking::create([
            'parking_space_id' => $capeTownSpaces[0]->id,
            'user_id' => $renter1->id,
            'start_datetime' => Carbon::now()->subDays(10)->setHour(9)->setMinute(0),
            'end_datetime' => Carbon::now()->subDays(10)->setHour(14)->setMinute(0),
            'total_price' => 5 * $capeTownSpaces[0]->price_per_hour,
            'status' => 'completed',
        ]);

        // Add payment for past booking
        Payment::create([
            'booking_id' => $pastBooking1->id,
            'transaction_id' => 'TRX' . mt_rand(100000, 999999),
            'amount' => $pastBooking1->total_price,
            'payment_method' => 'PayFast',
            'status' => 'completed',
        ]);

        // Add review for past booking
        Review::create([
            'booking_id' => $pastBooking1->id,
            'rating' => 5,
            'comment' => 'Great parking space! Very secure and easy to find.',
        ]);

        // Create another completed booking for renter1
        $pastBooking2 = Booking::create([
            'parking_space_id' => $capeTownSpaces[1]->id,
            'user_id' => $renter1->id,
            'start_datetime' => Carbon::now()->subDays(5)->setHour(10)->setMinute(0),
            'end_datetime' => Carbon::now()->subDays(5)->setHour(16)->setMinute(0),
            'total_price' => 6 * $capeTownSpaces[1]->price_per_hour,
            'status' => 'completed',
        ]);

        // Add payment for past booking
        Payment::create([
            'booking_id' => $pastBooking2->id,
            'transaction_id' => 'TRX' . mt_rand(100000, 999999),
            'amount' => $pastBooking2->total_price,
            'payment_method' => 'PayFast',
            'status' => 'completed',
        ]);

        // Add review for past booking
        Review::create([
            'booking_id' => $pastBooking2->id,
            'rating' => 4,
            'comment' => 'Good location, but a bit tight for larger vehicles.',
        ]);

        // Create upcoming booking for renter1
        $upcomingBooking1 = Booking::create([
            'parking_space_id' => $capeTownSpaces[0]->id,
            'user_id' => $renter1->id,
            'start_datetime' => Carbon::now()->addDays(2)->setHour(9)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(2)->setHour(17)->setMinute(0),
            'total_price' => 8 * $capeTownSpaces[0]->price_per_hour,
            'status' => 'confirmed',
        ]);

        // Add payment for upcoming booking
        Payment::create([
            'booking_id' => $upcomingBooking1->id,
            'transaction_id' => 'TRX' . mt_rand(100000, 999999),
            'amount' => $upcomingBooking1->total_price,
            'payment_method' => 'PayFast',
            'status' => 'completed',
        ]);

        // Create completed bookings for renter2 in Johannesburg
        $pastBooking3 = Booking::create([
            'parking_space_id' => $joburgSpaces[0]->id,
            'user_id' => $renter2->id,
            'start_datetime' => Carbon::now()->subDays(7)->setHour(8)->setMinute(0),
            'end_datetime' => Carbon::now()->subDays(7)->setHour(17)->setMinute(0),
            'total_price' => 9 * $joburgSpaces[0]->price_per_hour,
            'status' => 'completed',
        ]);

        // Add payment for past booking
        Payment::create([
            'booking_id' => $pastBooking3->id,
            'transaction_id' => 'TRX' . mt_rand(100000, 999999),
            'amount' => $pastBooking3->total_price,
            'payment_method' => 'PayFast',
            'status' => 'completed',
        ]);

        // Add review for past booking
        Review::create([
            'booking_id' => $pastBooking3->id,
            'rating' => 5,
            'comment' => 'Perfect location for business meetings in Sandton. Will use again!',
        ]);

        // Create upcoming booking for renter2
        $upcomingBooking2 = Booking::create([
            'parking_space_id' => $joburgSpaces[1]->id,
            'user_id' => $renter2->id,
            'start_datetime' => Carbon::now()->addDays(3)->setHour(10)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(3)->setHour(15)->setMinute(0),
            'total_price' => 5 * $joburgSpaces[1]->price_per_hour,
            'status' => 'confirmed',
        ]);

        // Add payment for upcoming booking
        Payment::create([
            'booking_id' => $upcomingBooking2->id,
            'transaction_id' => 'TRX' . mt_rand(100000, 999999),
            'amount' => $upcomingBooking2->total_price,
            'payment_method' => 'PayFast',
            'status' => 'completed',
        ]);

        // Create pending booking for renter2
        $pendingBooking = Booking::create([
            'parking_space_id' => $joburgSpaces[0]->id,
            'user_id' => $renter2->id,
            'start_datetime' => Carbon::now()->addDays(5)->setHour(9)->setMinute(0),
            'end_datetime' => Carbon::now()->addDays(5)->setHour(18)->setMinute(0),
            'total_price' => 9 * $joburgSpaces[0]->price_per_hour,
            'status' => 'pending',
        ]);
    }
}
