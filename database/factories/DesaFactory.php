<?php

namespace Database\Factories;

use App\Models\Desa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Desa>
 */
class DesaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->numerify('##.##.##.####'),
            'kecamatanId' => \App\Models\Kecamatan::factory(),
            'namaDesa' => fake()->word(),
        ];
    }
}
