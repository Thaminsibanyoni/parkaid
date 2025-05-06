<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkingSpace extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'vehicle_type',
        'price_per_hour',
        'price_per_day',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'price_per_hour' => 'decimal:2',
        'price_per_day' => 'decimal:2',
    ];

    /**
     * Get the user that owns the parking space.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the images for the parking space.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ParkingSpaceImage::class);
    }

    /**
     * Get the availability for the parking space.
     */
    public function availability(): HasMany
    {
        return $this->hasMany(ParkingSpaceAvailability::class);
    }

    /**
     * Get the bookings for the parking space.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
