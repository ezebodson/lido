<?php

namespace App\Http\Requests\Rate;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'beach_club_id' => ['sometimes', 'integer', 'exists:beach_clubs,id'],
            'name' => ['required', 'string', 'max:120'],
            'unit_type' => ['nullable', 'string', 'max:40'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (strtotime($this->input('end_date')) < strtotime($this->input('start_date'))) {
                $validator->errors()->add('end_date', 'End date must be after or equal to start date.');
            }
        });
    }
}
