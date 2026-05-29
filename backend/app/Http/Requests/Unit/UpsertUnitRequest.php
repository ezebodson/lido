<?php

namespace App\Http\Requests\Unit;

use App\Enums\UnitStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        $unitId = $this->route('unit')?->id;
        $beachClubId = $this->user()?->beach_club_id;

        return [
            'sector_id' => ['required', 'integer', Rule::exists('sectors', 'id')->where('beach_club_id', $beachClubId)],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('units', 'code')->ignore($unitId)->where('beach_club_id', $beachClubId)],
            'capacity' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', Rule::enum(UnitStatus::class)],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
