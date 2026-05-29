<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BeachClubFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->company().' Beach Club';

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'timezone' => 'America/Argentina/Buenos_Aires',
            'currency' => 'ARS',
            'is_active' => true,
        ];
    }
}
