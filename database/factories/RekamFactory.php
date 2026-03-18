<?php

namespace Database\Factories;

use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Database\Eloquent\Factories\Factory;

class RekamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pasien_id'       => Pasien::factory(),
            'dokter_id'       => Dokter::factory(),
            'poli_id'         => Poli::factory(),
            'no_rekam'        => 'RK-' . $this->faker->unique()->numerify('########'),
            'jenis_kunjungan' => $this->faker->randomElement(['rawat_jalan', 'rawat_inap', 'igd']),
            'tgl_masuk'       => now(),
            'keluhan'         => $this->faker->sentence(),
            'cara_bayar'      => 'Umum',
            'status_encounter' => 'in-progress',
        ];
    }
}
