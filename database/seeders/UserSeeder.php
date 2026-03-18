<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Administrator',
                'email'    => 'admin@remedis.id',
                'password' => Hash::make('password'),
                'role'     => 'admin',
                'aktif'    => true,
            ],
            [
                'name'     => 'Dr. Budi Santoso',
                'email'    => 'dokter@remedis.id',
                'password' => Hash::make('password'),
                'role'     => 'dokter',
                'aktif'    => true,
            ],
            [
                'name'     => 'Siti Rahayu, A.Md.Kep',
                'email'    => 'perawat@remedis.id',
                'password' => Hash::make('password'),
                'role'     => 'perawat',
                'aktif'    => true,
            ],
            [
                'name'     => 'Dewi Kusuma, S.Farm',
                'email'    => 'apoteker@remedis.id',
                'password' => Hash::make('password'),
                'role'     => 'apoteker',
                'aktif'    => true,
            ],
            [
                'name'     => 'Andi Wijaya',
                'email'    => 'kasir@remedis.id',
                'password' => Hash::make('password'),
                'role'     => 'kasir',
                'aktif'    => true,
            ],
            [
                'name'     => 'Rina Marlina',
                'email'    => 'pendaftaran@remedis.id',
                'password' => Hash::make('password'),
                'role'     => 'front_office',
                'aktif'    => true,
            ],
            [
                'name'     => 'Dr. Ahmad Fauzi (Pimpinan)',
                'email'    => 'pimpinan@remedis.id',
                'password' => Hash::make('password'),
                'role'     => 'pimpinan',
                'aktif'    => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }

        $this->command->info('✅ UserSeeder: ' . count($users) . ' user berhasil dibuat.');
        $this->command->table(
            ['Nama', 'Email', 'Role', 'Password'],
            collect($users)->map(fn($u) => [
                $u['name'],
                $u['email'],
                $u['role'],
                'password'
            ])->toArray()
        );
    }
}
