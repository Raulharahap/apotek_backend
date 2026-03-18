<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DokterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama'        => 'dr. ' . $this->faker->name(),
            'jk'          => $this->faker->randomElement(['Laki-Laki', 'Perempuan']),
            'no_hp'       => '08' . $this->faker->numerify('#########'),
            'spesialisasi' => $this->faker->randomElement(['Umum', 'Bedah', 'Gigi', 'Anak']),
            'aktif'       => true,
        ];
    }
}
