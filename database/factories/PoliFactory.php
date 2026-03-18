<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PoliFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama'      => 'Poli ' . $this->faker->word(),
            'kode_poli' => strtoupper($this->faker->unique()->lexify('???')),
            'jenis'     => $this->faker->randomElement(['rawat_jalan', 'rawat_inap', 'igd']),
            'aktif'     => true,
        ];
    }
}
