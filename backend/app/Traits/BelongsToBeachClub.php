<?php

namespace App\Traits;

use App\Enums\UserRole;
use App\Models\BeachClub;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBeachClub
{
    protected static function bootBelongsToBeachClub(): void
    {
        static::creating(function ($model): void {
            $user = auth()->user();

            if ($user && empty($model->beach_club_id)) {
                $model->beach_club_id = $user->beach_club_id;
            }
        });

        static::addGlobalScope('beach_club', function (Builder $builder): void {
            $user = auth()->user();

            if (! $user || $user->role === UserRole::SUPER_ADMIN) {
                return;
            }

            $builder->where($builder->qualifyColumn('beach_club_id'), $user->beach_club_id);
        });
    }

    public function beachClub(): BelongsTo
    {
        return $this->belongsTo(BeachClub::class);
    }
}
