<?php

namespace App\Http\Requests\Sector;

use Illuminate\Foundation\Http\FormRequest;

class StoreSectorRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:20'],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
