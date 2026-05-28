<?php

namespace App\Http\Requests\Unit;

use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'beach_club_id' => ['sometimes', 'integer', 'exists:beach_clubs,id'],
            'sector_id' => ['sometimes', 'integer', 'exists:sectors,id'],
            'code' => ['sometimes', 'string', 'max:30'],
            'status' => ['sometimes', Rule::in([Unit::STATUS_AVAILABLE, Unit::STATUS_OCCUPIED, Unit::STATUS_MAINTENANCE, Unit::STATUS_RESERVED])],
            'x' => ['nullable', 'numeric'],
            'y' => ['nullable', 'numeric'],
            'capacity' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
