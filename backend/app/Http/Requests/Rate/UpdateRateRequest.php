<?php

namespace App\Http\Requests\Rate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'beach_club_id' => ['sometimes', 'integer', 'exists:beach_clubs,id'],
            'name' => ['sometimes', 'string', 'max:120'],
            'unit_type' => ['nullable', 'string', 'max:40'],
            'daily_price' => ['sometimes', 'numeric', 'min:0'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $startDate = $this->input('start_date');
            $endDate = $this->input('end_date');

            if ($startDate && $endDate && strtotime($endDate) < strtotime($startDate)) {
                $validator->errors()->add('end_date', 'End date must be after or equal to start date.');
            }
        });
    }
}
