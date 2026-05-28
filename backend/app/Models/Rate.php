<?php

namespace App\Models;

use Database\Factories\RateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    /** @use HasFactory<RateFactory> */
    use HasFactory;

    protected $fillable = [
        'beach_club_id',
        'name',
        'unit_type',
        'daily_price',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'daily_price' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function beachClub(): BelongsTo
    {
        return $this->belongsTo(BeachClub::class);
    }
}
