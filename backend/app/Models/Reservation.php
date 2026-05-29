<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use App\Traits\BelongsToBeachClub;
use Database\Factories\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use BelongsToBeachClub, HasFactory;

    protected $fillable = [
        'beach_club_id',
        'customer_id',
        'unit_id',
        'rate_id',
        'code',
        'start_date',
        'end_date',
        'status',
        'guests',
        'total_amount',
        'paid_amount',
        'notes',
    ];

    protected $appends = ['pending_amount'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => ReservationStatus::class,
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany('paid_at');
    }

    public function getPendingAmountAttribute(): float
    {
        return max((float) $this->total_amount - (float) $this->paid_amount, 0);
    }

    public function syncPaidAmount(): void
    {
        $this->forceFill([
            'paid_amount' => (float) $this->payments()->sum('amount'),
        ])->saveQuietly();
    }
}
