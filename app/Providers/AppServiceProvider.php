<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $can = fn(array $roles) => fn($user) => in_array($user->role, $roles);

        // Admin bypass — admin dapat melakukan semua tindakan
        Gate::before(fn($user) => $user->role === 'admin' ? true : null);

        Gate::define('create-pasien',   $can(['front_office', 'dokter', 'perawat']));
        Gate::define('update-pasien',   $can(['front_office', 'dokter', 'perawat']));
        Gate::define('delete-pasien',   $can([])); // admin only via Gate::before

        Gate::define('create-rekam',    $can(['dokter', 'perawat']));
        Gate::define('update-rekam',    $can(['dokter']));

        Gate::define('manage-obat',     $can(['apoteker']));
        Gate::define('manage-stok',     $can(['apoteker']));
        Gate::define('manage-invoice',  $can(['kasir']));
        Gate::define('manage-users',    $can([])); // admin only
        Gate::define('manage-settings', $can([])); // admin only
        Gate::define('sync-satusehat',  $can(['dokter']));
    }
}
