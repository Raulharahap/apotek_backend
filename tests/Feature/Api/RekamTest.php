<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Poli;
use App\Models\Rekam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RekamTest extends TestCase
{
    use RefreshDatabase;

    private function makeFixtures(): array
    {
        $poli   = Poli::factory()->create();
        $dokter = Dokter::factory()->create(['poli_id' => $poli->id]);
        $pasien = Pasien::factory()->create();
        /** @var \App\Models\User $user */
        $user   = User::factory()->create(['role' => 'dokter', 'aktif' => true, 'dokter_id' => $dokter->id]);
        return compact('poli', 'dokter', 'pasien', 'user');
    }

    public function test_buat_rekam_medis(): void
    {
        ['user' => $user, 'pasien' => $pasien, 'dokter' => $dokter, 'poli' => $poli] = $this->makeFixtures();

        $this->actingAs($user, 'sanctum')->postJson('/api/v1/rekam', [
            'pasien_id'       => $pasien->id,
            'dokter_id'       => $dokter->id,
            'poli_id'         => $poli->id,
            'jenis_kunjungan' => 'rawat_jalan',
            'tgl_masuk'       => now()->toDateTimeString(),
            'keluhan'         => 'Demam 3 hari',
        ])->assertStatus(201)
            ->assertJsonPath('status_encounter', 'in-progress');
    }

    public function test_list_rekam(): void
    {
        ['user' => $user, 'pasien' => $pasien, 'dokter' => $dokter] = $this->makeFixtures();
        Rekam::factory(2)->create(['pasien_id' => $pasien->id, 'dokter_id' => $dokter->id]);

        $this->actingAs($user, 'sanctum')->getJson('/api/v1/rekam')
            ->assertStatus(200);
    }

    public function test_detail_rekam(): void
    {
        ['user' => $user, 'pasien' => $pasien, 'dokter' => $dokter] = $this->makeFixtures();
        $rekam = Rekam::factory()->create(['pasien_id' => $pasien->id, 'dokter_id' => $dokter->id]);

        $this->actingAs($user, 'sanctum')->getJson("/api/v1/rekam/{$rekam->id}/detail")
            ->assertStatus(200);
    }

    public function test_update_status_rekam(): void
    {
        ['user' => $user, 'pasien' => $pasien, 'dokter' => $dokter] = $this->makeFixtures();
        $rekam = Rekam::factory()->create(['pasien_id' => $pasien->id, 'dokter_id' => $dokter->id]);

        $this->actingAs($user, 'sanctum')->putJson("/api/v1/rekam/{$rekam->id}", [
            'status_encounter' => 'finished',
            'tgl_keluar'       => now()->addHours(2)->toDateTimeString(),
        ])->assertStatus(200)
            ->assertJsonPath('status_encounter', 'finished');
    }

    public function test_tambah_diagnosa(): void
    {
        ['user' => $user, 'pasien' => $pasien, 'dokter' => $dokter] = $this->makeFixtures();
        $rekam = Rekam::factory()->create(['pasien_id' => $pasien->id, 'dokter_id' => $dokter->id]);

        $this->actingAs($user, 'sanctum')->postJson("/api/v1/rekam/{$rekam->id}/diagnosa", [
            'diagnosa'            => 'Hipertensi',
            'jenis'               => 'utama',
            'clinical_status'     => 'active',
            'verification_status' => 'confirmed',
        ])->assertStatus(201);
    }

    public function test_akses_tanpa_token_ditolak(): void
    {
        $this->postJson('/api/v1/rekam', [])->assertStatus(401);
    }
}
