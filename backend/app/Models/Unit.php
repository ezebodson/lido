<?php

namespace App\Models;

use App\Enums\UnitStatus;
use App\Traits\BelongsToBeachClub;
use Database\Factories\UnitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    /** @use HasFactory<UnitFactory> */
    use BelongsToBeachClub, HasFactory;

    protected $fillable = [
        'beach_club_id',
        'sector_id',
        'name',
        'code',
        'capacity',
        'status',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'status' => UnitStatus::class,
            'is_active' => 'boolean',
        ];
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
