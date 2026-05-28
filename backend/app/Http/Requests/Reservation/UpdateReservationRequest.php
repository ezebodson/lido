<?php

namespace App\Http\Requests\Reservation;

use App\Models\Reservation;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'beach_club_id' => ['sometimes', 'integer', 'exists:beach_clubs,id'],
            'unit_id' => ['sometimes', 'integer', 'exists:units,id'],
            'customer_id' => ['sometimes', 'integer', 'exists:customers,id'],
            'status' => ['sometimes', Rule::in(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date'],
            'guests' => ['sometimes', 'integer', 'min:1'],
            'total_amount' => ['sometimes', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $reservation = $this->route('reservation');
            $unitId = (int) ($this->input('unit_id') ?? $reservation?->unit_id);
            $startDate = $this->input('start_date', $reservation?->start_date?->toDateString() ?? $reservation?->start_date);
            $endDate = $this->input('end_date', $reservation?->end_date?->toDateString() ?? $reservation?->end_date);
            $unit = Unit::find($unitId);

            if (! $unit || ! $reservation || ! $startDate || ! $endDate) {
                return;
            }

            if (Carbon::parse($endDate)->lt(Carbon::parse($startDate))) {
                $validator->errors()->add('end_date', 'End date must be after or equal to start date.');
            }

            if ($unit->status === Unit::STATUS_MAINTENANCE) {
                $validator->errors()->add('unit_id', 'Unit is under maintenance.');
            }

            $hasOverlap = Reservation::query()
                ->where('unit_id', $unit->id)
                ->where('id', '!=', $reservation->id)
                ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                ->whereDate('start_date', '<=', $endDate)
                ->whereDate('end_date', '>=', $startDate)
                ->exists();

            if ($hasOverlap) {
                $validator->errors()->add('start_date', 'Reservation overlaps an existing booking.');
            }
        });
    }
}
