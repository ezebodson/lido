<?php

namespace App\Models;

use Database\Factories\BeachClubFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BeachClub extends Model
{
    /** @use HasFactory<BeachClubFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'timezone',
        'currency',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function sectors(): HasMany
    {
        return $this->hasMany(Sector::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }
}
