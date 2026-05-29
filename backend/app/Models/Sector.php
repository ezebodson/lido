<?php

namespace App\Models;

use App\Traits\BelongsToBeachClub;
use Database\Factories\SectorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    /** @use HasFactory<SectorFactory> */
    use BelongsToBeachClub, HasFactory;

    protected $fillable = [
        'beach_club_id',
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }
}
