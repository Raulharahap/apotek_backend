<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Dokter;
use App\Models\Antrian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AntrianTest extends TestCase
{
    use RefreshDatabase;

    public function test_buat_antrian(): void
    {
        /** @var \App\Models\User $user */
        $user   = User::factory()->create(['role' => 'front_office', 'aktif' => true]);
        $pasien = Pasien::factory()->create();
        $poli   = Poli::factory()->create();

        $this->actingAs($user, 'sanctum')->postJson('/api/v1/antrian', [
            'pasien_id'       => $pasien->id,
            'poli_id'         => $poli->id,
            'jenis_kunjungan' => 'rawat_jalan',
        ])->assertStatus(201)
            ->assertJsonPath('status', 'menunggu');
    }

    public function test_update_status_antrian(): void
    {
        /** @var \App\Models\User $user */
        $user    = User::factory()->create(['role' => 'perawat', 'aktif' => true]);
        $antrian = Antrian::factory()->create(['status' => 'menunggu']);

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/antrian/{$antrian->id}/status", ['status' => 'dipanggil'])
            ->assertStatus(200)
            ->assertJsonPath('status', 'dipanggil');
    }

    public function test_list_antrian_hari_ini(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['role' => 'perawat', 'aktif' => true]);
        Antrian::factory(3)->create(['tanggal' => today()]);

        $this->actingAs($user, 'sanctum')->getJson('/api/v1/antrian')
            ->assertStatus(200)
            ->assertJsonCount(3);
    }
}
