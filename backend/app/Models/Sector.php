<?php

namespace App\Models;

use Database\Factories\SectorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sector extends Model
{
    /** @use HasFactory<SectorFactory> */
    use HasFactory;

    protected $fillable = ['beach_club_id', 'name', 'code', 'position'];

    public function beachClub(): BelongsTo
    {
        return $this->belongsTo(BeachClub::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}
