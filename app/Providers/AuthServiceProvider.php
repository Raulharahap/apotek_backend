<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ── Helper ─────────────────────────────────────────────────────────
        $can = fn(array $roles) => fn($user) => in_array($user->role, $roles);

        // ── Pasien ─────────────────────────────────────────────────────────
        Gate::define('create-pasien',  $can(['admin', 'front_office', 'dokter', 'perawat']));
        Gate::define('update-pasien',  $can(['admin', 'front_office', 'dokter', 'perawat']));
        Gate::define('delete-pasien',  $can(['admin']));

        // ── Rekam ──────────────────────────────────────────────────────────
        Gate::define('create-rekam',   $can(['admin', 'dokter', 'perawat']));
        Gate::define('update-rekam',   $can(['admin', 'dokter']));

        // ── Obat / Farmasi ─────────────────────────────────────────────────
        Gate::define('manage-obat',    $can(['admin', 'apoteker']));
        Gate::define('manage-stok',    $can(['admin', 'apoteker']));

        // ── Billing ────────────────────────────────────────────────────────
        Gate::define('manage-invoice', $can(['admin', 'kasir']));

        // ── Admin ──────────────────────────────────────────────────────────
        Gate::define('manage-users',   $can(['admin']));
        Gate::define('manage-settings', $can(['admin']));

        // ── SATUSEHAT Sync ─────────────────────────────────────────────────
        Gate::define('sync-satusehat', $can(['admin', 'dokter']));
    }
}
