<?php

namespace Database\Factories;

use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Dokter;
use Illuminate\Database\Eloquent\Factories\Factory;

class AntrianFactory extends Factory
{
    public function definition(): array
    {
        return [
            'no_antrian'      => strtoupper($this->faker->lexify('?')) . $this->faker->numerify('###'),
            'pasien_id'       => Pasien::factory(),
            'poli_id'         => Poli::factory(),
            'dokter_id'       => Dokter::factory(),
            'tanggal'         => today(),
            'jenis_kunjungan' => 'rawat_jalan',
            'sumber'          => 'walk_in',
            'status'          => 'menunggu',
        ];
    }
}
