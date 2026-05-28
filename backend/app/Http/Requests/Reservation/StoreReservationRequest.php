<?php

namespace App\Http\Requests\Reservation;

use App\Models\Reservation;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'beach_club_id' => ['sometimes', 'integer', 'exists:beach_clubs,id'],
            'unit_id' => ['required', 'integer', 'exists:units,id'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'guests' => ['required', 'integer', 'min:1'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $unit = Unit::find($this->input('unit_id'));

            if (! $unit) {
                return;
            }

            if (Carbon::parse($this->input('end_date'))->lt(Carbon::parse($this->input('start_date')))) {
                $validator->errors()->add('end_date', 'End date must be after or equal to start date.');
            }

            if ($unit->status === Unit::STATUS_MAINTENANCE) {
                $validator->errors()->add('unit_id', 'Unit is under maintenance.');
            }

            $beachClubId = $this->resolvedBeachClubId($unit);
            if ((int) $unit->beach_club_id !== (int) $beachClubId) {
                $validator->errors()->add('unit_id', 'Unit does not belong to target beach club.');

                return;
            }

            $hasOverlap = Reservation::query()
                ->where('unit_id', $unit->id)
                ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                ->whereDate('start_date', '<=', $this->input('end_date'))
                ->whereDate('end_date', '>=', $this->input('start_date'))
                ->exists();

            if ($hasOverlap) {
                $validator->errors()->add('start_date', 'Reservation overlaps an existing booking.');
            }
        });
    }

    private function resolvedBeachClubId(Unit $unit): int
    {
        $user = $this->user();

        if ($user && ! $user->isSuperAdmin()) {
            return (int) $user->beach_club_id;
        }

        return (int) ($this->input('beach_club_id') ?? $unit->beach_club_id);
    }
}
