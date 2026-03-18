<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Pasien;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasienTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        $user = User::factory()->create(['role' => 'admin', 'aktif' => true]);
        return $user;
    }

    public function test_list_pasien(): void
    {
        $user = $this->actingAsAdmin();
        Pasien::factory(3)->create();

        $this->actingAs($user, 'sanctum')->getJson('/api/v1/pasien')
            ->assertStatus(200)
            ->assertJsonStructure(['data', 'total']);
    }

    public function test_buat_pasien_valid(): void
    {
        $user = $this->actingAsAdmin();

        $this->actingAs($user, 'sanctum')->postJson('/api/v1/pasien', [
            'nama'           => 'Budi Santoso',
            'jk'             => 'Laki-Laki',
            'nik'            => '3302011234567890',
            'no_hp'          => '081234567890',
            'alamat_lengkap' => 'Jl. Contoh No. 123, Kota Contoh',
        ])->assertStatus(201)
            ->assertJsonPath('nama', 'Budi Santoso');
    }

    public function test_buat_pasien_nik_duplikat(): void
    {
        $user = $this->actingAsAdmin();
        Pasien::factory()->create(['nik' => '3302011234567890']);

        $this->actingAs($user, 'sanctum')->postJson('/api/v1/pasien', [
            'nama' => 'Coba Lagi',
            'jk'   => 'Perempuan',
            'nik'  => '3302011234567890',
        ])->assertStatus(422)
            ->assertJsonPath('errors.nik', fn($v) => count($v) > 0);
    }

    public function test_detail_pasien(): void
    {
        $user   = $this->actingAsAdmin();
        $pasien = Pasien::factory()->create();

        $this->actingAs($user, 'sanctum')->getJson("/api/v1/pasien/{$pasien->id}")
            ->assertStatus(200)
            ->assertJsonPath('id', $pasien->id);
    }

    public function test_update_pasien(): void
    {
        $user   = $this->actingAsAdmin();
        $pasien = Pasien::factory()->create();

        $this->actingAs($user, 'sanctum')->putJson("/api/v1/pasien/{$pasien->id}", ['nama' => 'Nama Baru'])
            ->assertStatus(200)
            ->assertJsonPath('nama', 'Nama Baru');
    }

    public function test_tanpa_auth_ditolak(): void
    {
        $this->getJson('/api/v1/pasien')->assertStatus(401);
    }
}
