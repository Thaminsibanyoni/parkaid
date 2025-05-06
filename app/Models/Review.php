<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'rating',
        'comment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Get the booking that owns the review.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user that created the review through the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->through('booking');
    }

    /**
     * Get the parking space that was reviewed through the booking.
     */
    public function parkingSpace(): BelongsTo
    {
        return $this->belongsTo(ParkingSpace::class)->through('booking');
    }
}
