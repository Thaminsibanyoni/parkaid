<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users
        $host1 = User::where('email', 'host@parkaid.com')->first();
        $host2 = User::where('email', 'host2@parkaid.com')->first();
        $renter1 = User::where('email', 'renter@parkaid.com')->first();
        $renter2 = User::where('email', 'renter2@parkaid.com')->first();

        // Get bookings
        $capeTownBooking = Booking::where('status', 'confirmed')
            ->whereHas('parkingSpace', function ($query) {
                $query->where('city', 'Cape Town');
            })
            ->first();

        $joburgBooking = Booking::where('status', 'confirmed')
            ->whereHas('parkingSpace', function ($query) {
                $query->where('city', 'Johannesburg');
            })
            ->first();

        // Create messages for Cape Town booking
        Message::create([
            'sender_id' => $renter1->id,
            'receiver_id' => $host1->id,
            'booking_id' => $capeTownBooking->id,
            'content' => 'Hi, I\'ve booked your parking space. Is there anything specific I should know before arriving?',
            'read' => true,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        Message::create([
            'sender_id' => $host1->id,
            'receiver_id' => $renter1->id,
            'booking_id' => $capeTownBooking->id,
            'content' => 'Hello! Thanks for booking. The entrance is on the right side of the building. You\'ll need to use the access code 1234 to enter the gate.',
            'read' => true,
            'created_at' => Carbon::now()->subDays(3)->addHours(1),
        ]);

        Message::create([
            'sender_id' => $renter1->id,
            'receiver_id' => $host1->id,
            'booking_id' => $capeTownBooking->id,
            'content' => 'Great, thank you! I\'ll be arriving around 9 AM as scheduled.',
            'read' => true,
            'created_at' => Carbon::now()->subDays(3)->addHours(2),
        ]);

        Message::create([
            'sender_id' => $host1->id,
            'receiver_id' => $renter1->id,
            'booking_id' => $capeTownBooking->id,
            'content' => 'Perfect! Let me know if you have any issues finding the place.',
            'read' => false,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // Create messages for Johannesburg booking
        Message::create([
            'sender_id' => $renter2->id,
            'receiver_id' => $host2->id,
            'booking_id' => $joburgBooking->id,
            'content' => 'Hello, I\'ve booked your parking space for my business trip. Is it close to the Sandton Convention Centre?',
            'read' => true,
            'created_at' => Carbon::now()->subDays(4),
        ]);

        Message::create([
            'sender_id' => $host2->id,
            'receiver_id' => $renter2->id,
            'booking_id' => $joburgBooking->id,
            'content' => 'Hi there! Yes, it\'s about a 5-minute walk to the Convention Centre. Very convenient location.',
            'read' => true,
            'created_at' => Carbon::now()->subDays(4)->addHours(1),
        ]);

        Message::create([
            'sender_id' => $renter2->id,
            'receiver_id' => $host2->id,
            'booking_id' => $joburgBooking->id,
            'content' => 'That\'s perfect! Is there secure access to the parking area?',
            'read' => true,
            'created_at' => Carbon::now()->subDays(4)->addHours(3),
        ]);

        Message::create([
            'sender_id' => $host2->id,
            'receiver_id' => $renter2->id,
            'booking_id' => $joburgBooking->id,
            'content' => 'Yes, there\'s 24/7 security and you\'ll get a temporary access card when you arrive. Just call me 10 minutes before you get there.',
            'read' => true,
            'created_at' => Carbon::now()->subDays(3),
        ]);

        Message::create([
            'sender_id' => $renter2->id,
            'receiver_id' => $host2->id,
            'booking_id' => $joburgBooking->id,
            'content' => 'Sounds good. I\'ll call you when I\'m on my way. Thanks!',
            'read' => false,
            'created_at' => Carbon::now()->subDays(2),
        ]);

        // Create general messages (not related to bookings)
        Message::create([
            'sender_id' => $renter1->id,
            'receiver_id' => $host2->id,
            'booking_id' => null,
            'content' => 'Hi, I\'m planning a trip to Johannesburg next month. Do you have availability for a week-long parking?',
            'read' => true,
            'created_at' => Carbon::now()->subDays(5),
        ]);

        Message::create([
            'sender_id' => $host2->id,
            'receiver_id' => $renter1->id,
            'booking_id' => null,
            'content' => 'Hello! Yes, I should have availability. When exactly are you planning to visit?',
            'read' => false,
            'created_at' => Carbon::now()->subDays(4),
        ]);
    }
}
