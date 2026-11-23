<?php

namespace Database\Factories;

use App\Models\Kabupaten;
use App\Models\Provinsi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kabupaten>
 */
class KabupatenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->numerify('##.##'),
            'provinsiId' => Provinsi::factory(),
            'namaKabupaten' => fake()->word(),
        ];
    }
}
