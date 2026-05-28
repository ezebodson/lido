<?php

namespace Database\Factories;

use App\Models\BeachClub;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BeachClub>
 */
class BeachClubFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company().' Beach Club';

        return [
            'name' => $name,
            'slug' => str($name)->slug()->toString(),
            'address' => fake()->address(),
            'is_active' => true,
        ];
    }
}
