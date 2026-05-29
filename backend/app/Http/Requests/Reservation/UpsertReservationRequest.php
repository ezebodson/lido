<?php

namespace App\Http\Requests\Reservation;

use App\Enums\ReservationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'integer', Rule::exists('customers', 'id')],
            'unit_id' => ['required', 'integer', Rule::exists('units', 'id')],
            'rate_id' => ['nullable', 'integer', Rule::exists('rates', 'id')],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', Rule::enum(ReservationStatus::class)],
            'guests' => ['required', 'integer', 'min:1', 'max:20'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
