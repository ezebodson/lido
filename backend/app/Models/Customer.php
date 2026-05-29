<?php

namespace App\Models;

use App\Traits\BelongsToBeachClub;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use BelongsToBeachClub, HasFactory;

    protected $fillable = [
        'beach_club_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'document_number',
        'notes',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
