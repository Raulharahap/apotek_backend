@extends('admin.layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Laporan</h3>
                    <p class="text-subtitle text-muted">Laporan & statistik</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/admin/dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Laporan</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="page-content">
        <section class="section">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-people-fill fs-1 text-primary"></i>
                            <h5 class="mt-3">Laporan Pasien</h5>
                            <p class="text-muted">Rekap data pasien</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">Lihat Laporan</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-calendar-check-fill fs-1 text-success"></i>
                            <h5 class="mt-3">Laporan Kunjungan</h5>
                            <p class="text-muted">Rekap kunjungan pasien</p>
                            <a href="#" class="btn btn-outline-success btn-sm">Lihat Laporan</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-capsule fs-1 text-warning"></i>
                            <h5 class="mt-3">Laporan Farmasi</h5>
                            <p class="text-muted">Rekap stok & penggunaan obat</p>
                            <a href="#" class="btn btn-outline-warning btn-sm">Lihat Laporan</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection