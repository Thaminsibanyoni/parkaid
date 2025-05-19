<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parking_space_id',
        'user_id',
        'start_datetime',
        'end_datetime',
        'total_price',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the parking space that owns the booking.
     */
    public function parkingSpace(): BelongsTo
    {
        return $this->belongsTo(ParkingSpace::class);
    }

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment associated with the booking.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the review associated with the booking.
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get the messages for the booking.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getDurationAttribute()
    {
        return $this->start_datetime->diffInHours($this->end_datetime);
    }

    public function getFormattedStartAttribute()
    {
        return $this->start_datetime->format('D, M j, Y g:i A');
    }

    public function getFormattedEndAttribute()
    {
        return $this->end_datetime->format('D, M j, Y g:i A');
    }

    public function getFormattedPriceAttribute()
    {
        return 'R' . number_format($this->total_price, 2);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', now())
                    ->where('status', 'confirmed');
    }

    public function scopePast($query)
    {
        return $query->where('end_datetime', '<', now());
    }
}
