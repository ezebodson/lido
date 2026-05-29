<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        $customerId = $this->route('customer')?->id;
        $beachClubId = $this->user()?->beach_club_id;

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('customers', 'email')->ignore($customerId)->where('beach_club_id', $beachClubId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'document_number' => ['nullable', 'string', 'max:50', Rule::unique('customers', 'document_number')->ignore($customerId)->where('beach_club_id', $beachClubId)],
            'notes' => ['nullable', 'string'],
        ];
    }
}
