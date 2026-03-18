<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getDashboard()
    {
        return view('admin.dashboard');
    }

    public function getPasien()
    {
        return view('admin.pasien.index');
    }

    public function createPasien()
    {
        // Memanggil file: resources/views/admin/pasien/create.blade.php
        return view('admin.pasien.create');
    }

    public function getRekamMedis()
    {
        return view('admin.rekam-medis.index');
    }

    public function getKunjungan()
    {
        return view('admin.kunjungan.index');
    }

    public function getFarmasi()
    {
        return view('admin.farmasi.index');
    }

    public function getLaporan()
    {
        return view('admin.laporan.index');
    }
}
