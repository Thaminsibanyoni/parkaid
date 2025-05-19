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
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
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

    public function primaryImage()
    {
        return $this->hasOne(ParkingSpaceImage::class)->where('is_primary', true);
    }

    public function availability()
    {
        return $this->hasMany(ParkingSpaceAvailability::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasManyThrough(Review::class, Booking::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    public function getPrimaryImageUrlAttribute()
    {
        $primaryImage = $this->primaryImage;
        if ($primaryImage) {
            return asset('storage/' . $primaryImage->image_path);
        }

        // If no primary image, try to get the first image
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }

        return asset('images/default-parking.jpg');
    }

    public function getFormattedPriceAttribute()
    {
        return 'R' . number_format($this->price_per_hour, 2);
    }

    public function getFormattedDailyPriceAttribute()
    {
        return $this->price_per_day ? 'R' . number_format($this->price_per_day, 2) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByVehicleType($query, $type)
    {
        return $query->where('vehicle_type', $type);
    }

    public function isAvailable($startDateTime, $endDateTime)
    {
        // Check if there are any overlapping bookings
        $conflictingBookings = $this->bookings()
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($startDateTime, $endDateTime) {
                $query->whereBetween('start_datetime', [$startDateTime, $endDateTime])
                      ->orWhereBetween('end_datetime', [$startDateTime, $endDateTime])
                      ->orWhere(function ($q) use ($startDateTime, $endDateTime) {
                          $q->where('start_datetime', '<=', $startDateTime)
                            ->where('end_datetime', '>=', $endDateTime);
                      });
            })->count();
        return $conflictingBookings === 0;
    }
}
