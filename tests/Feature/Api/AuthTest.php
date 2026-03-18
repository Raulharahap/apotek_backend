<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_berhasil(): void
    {
        $user = User::factory()->create([
            'email'    => 'admin@remedis.id',
            'password' => bcrypt('password'),
            'role'     => 'admin',
            'aktif'    => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'admin@remedis.id',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'role']]);
    }

    public function test_login_password_salah(): void
    {
        User::factory()->create(['email' => 'admin@remedis.id', 'password' => bcrypt('password'), 'aktif' => true]);

        $this->postJson('/api/v1/auth/login', ['email' => 'admin@remedis.id', 'password' => 'wrongpass'])
            ->assertStatus(401);
    }

    public function test_login_akun_tidak_aktif(): void
    {
        User::factory()->create(['email' => 'inactive@remedis.id', 'password' => bcrypt('password'), 'aktif' => false]);

        $this->postJson('/api/v1/auth/login', ['email' => 'inactive@remedis.id', 'password' => 'password'])
            ->assertStatus(403);
    }

    public function test_me_returns_user(): void
    {
        $user  = User::factory()->create(['aktif' => true]);
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->getJson('/api/v1/auth/me')
            ->assertStatus(200)
            ->assertJsonPath('id', $user->id);
    }

    public function test_logout(): void
    {
        $user  = User::factory()->create(['aktif' => true]);
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/auth/logout')
            ->assertStatus(200);
    }

    public function test_akses_tanpa_token_ditolak(): void
    {
        $this->getJson('/api/v1/auth/me')->assertStatus(401);
    }
}
