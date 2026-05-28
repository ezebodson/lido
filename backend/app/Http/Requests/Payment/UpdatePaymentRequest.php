<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'beach_club_id' => ['sometimes', 'integer', 'exists:beach_clubs,id'],
            'reservation_id' => ['sometimes', 'integer', 'exists:reservations,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'amount' => ['sometimes', 'numeric', 'min:0.01'],
            'method' => ['sometimes', Rule::in(['cash', 'card', 'bank_transfer'])],
            'status' => ['sometimes', Rule::in(['pending', 'paid', 'refunded'])],
            'paid_at' => ['nullable', 'date'],
            'reference' => ['nullable', 'string', 'max:80'],
        ];
    }
}
