<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PasienFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama'          => $this->faker->name(),
            'no_rm'         => 'RM-' . $this->faker->unique()->numerify('######'),
            'nik'           => $this->faker->unique()->numerify('################'),
            'jk'            => $this->faker->randomElement(['Laki-Laki', 'Perempuan']),
            'tgl_lahir'     => $this->faker->date(),
            'no_hp'         => '08' . $this->faker->numerify('#########'),
            'alamat_lengkap' => $this->faker->address(),
            'kabupaten'     => $this->faker->city(),
            'provinsi'      => 'Jawa Tengah',
            'cara_bayar'    => $this->faker->randomElement(['Umum/Mandiri', 'BPJS', 'Asuransi']),
        ];
    }
}
