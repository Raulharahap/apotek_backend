@extends('admin.layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<style>
    /* ======================================================== */
    /* GENERAL STYLES                                           */
    /* ======================================================== */
    .page-title-text {
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .invoice-text {
        color: #1a1a2e;
    }

    .kasir-name-text {
        color: #334155;
        font-size: 0.9rem;
    }

    .row-hover-effect {
        border-bottom: 1px solid #f2f2f2;
        transition: 0.2s ease;
    }

    /* Styling agar tabel tidak terlihat "putus" putih-putih */
    .table-custom {
        border-spacing: 0;
        border-collapse: collapse;
        margin-bottom: 0;
    }

    .table-custom td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        vertical-align: middle;
    }

    /* Efek hover baris */
    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Tombol Aksi Bulat (Tengah Sempurna) */
    .btn-icon {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }

    .btn-icon i {
        line-height: 0;
        font-size: 1.1rem;
        margin: 0;
    }

    /* Kesejajaran Tombol Transaksi Baru */
    .btn-add-transaksi {
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-add-transaksi i {
        line-height: 0;
        font-size: 1.1rem;
    }

    .btn-add-transaksi span {
        margin-top: 2px;
        /* optical centering */
    }

    /* Warna Badge Custom */
    .bg-primary-light {
        background-color: #eef2ff;
        color: #435ebe;
    }

    .bg-light-secondary {
        background-color: #f1f5f9;
        color: #64748b;
    }

    /* Input Style */
    .form-control:focus {
        border-color: #435ebe;
        box-shadow: 0 0 0 0.25rem rgba(67, 94, 190, 0.1);
    }

    /* Pagination Styling */
    .pagination .page-item.active .page-link {
        background-color: #435ebe;
        border-color: #435ebe;
    }

    .pagination .page-link {
        color: #435ebe;
        border-radius: 8px;
        margin: 0 2px;
    }

    /* Button Light custom (print) */
    .btn-light-primary {
        background-color: #eef2ff;
        color: #435ebe;
        border: none;
        transition: 0.2s;
    }

    .btn-light-primary:hover {
        background-color: #e0e7ff;
        color: #3730a3;
    }

    /* ======================================================== */
    /* FULL SUPPORT DARK MODE                                   */
    /* ======================================================== */
    [data-bs-theme="dark"] .page-title-text {
        color: #f8fafc;
    }

    [data-bs-theme="dark"] .text-muted {
        color: #94a3b8 !important;
    }

    [data-bs-theme="dark"] .text-dark {
        color: #e2e8f0 !important;
    }

    [data-bs-theme="dark"] .card {
        background-color: #1e1e2d !important;
    }

    [data-bs-theme="dark"] .form-control {
        background-color: #2d2d3e !important;
        border-color: #4b5563 !important;
        color: #f8fafc !important;
    }

    [data-bs-theme="dark"] .form-control:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.2) !important;
    }

    [data-bs-theme="dark"] .form-control-icon i {
        color: #94a3b8 !important;
    }

    /* Hover Table di Dark Mode */
    [data-bs-theme="dark"] .table-custom tbody tr:hover {
        background-color: #252536 !important;
    }

    [data-bs-theme="dark"] .row-hover-effect {
        border-bottom-color: #2d2d3e !important;
    }

    /* Warna Teks Tabel di Dark Mode */
    [data-bs-theme="dark"] .invoice-text {
        color: #f8fafc !important;
    }

    [data-bs-theme="dark"] .kasir-name-text {
        color: #cbd5e1 !important;
    }

    /* Badge & Tombol di Dark Mode */
    [data-bs-theme="dark"] .bg-light-secondary {
        background-color: #2d2d3e !important;
        color: #cbd5e1 !important;
    }

    [data-bs-theme="dark"] .bg-primary-light {
        background-color: rgba(67, 94, 190, 0.2) !important;
        color: #a5b4fc !important;
    }

    [data-bs-theme="dark"] .btn-light {
        background-color: #2d2d3e !important;
        border-color: #4b5563 !important;
        color: #e2e8f0 !important;
    }

    [data-bs-theme="dark"] .btn-light:hover {
        background-color: #3b3b4f !important;
    }

    [data-bs-theme="dark"] .btn-light-primary {
        background-color: rgba(67, 94, 190, 0.2) !important;
        color: #a5b4fc !important;
    }

    [data-bs-theme="dark"] .btn-light-primary:hover {
        background-color: rgba(67, 94, 190, 0.3) !important;
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title-text">Riwayat Transaksi</h3>
            <p class="text-muted" style="font-size: 0.9rem;">Kelola dan pantau semua data penjualan apotek</p>
        </div>
        <!-- FIX ICON LURUS: Menggunakan line-height: 0 pada ikon dan margin adjustment pada teks -->
        <a href="{{ route('kasir.index') }}" class="btn btn-primary rounded-pill px-4 btn-add-transaksi shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>
            <span>Transaksi Baru</span>
        </a>
    </div>

    <section class="section">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-body p-3">
                <form action="{{ route('kasir.history') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-dark ms-2">Cari Invoice</label>
                        <div class="form-group position-relative has-icon-left mb-0">
                            <input type="text" name="invoice" class="form-control rounded-pill border-light-subtle"
                                placeholder="Contoh: TRX-09FA..." value="{{ request('invoice') }}">
                            <div class="form-control-icon">
                                <i class="bi bi-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-dark ms-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control rounded-pill border-light-subtle" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-dark ms-2">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control rounded-pill border-light-subtle" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill w-100 fw-bold">Cari</button>
                            @if(request()->anyFilled(['invoice', 'start_date']))
                            <a href="{{ route('kasir.history') }}" class="btn btn-light rounded-pill border" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom">
                        <!-- FIX HEADER: Menggunakan rgba untuk garis tipis (transparan putih) -->
                        <thead style="background-color: #435ebe; border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
                            <tr>
                                <th class="text-white py-3 ps-4 border-0">No</th>
                                <th class="text-white py-3 border-0">Invoice</th>
                                <th class="text-white py-3 border-0">Tanggal</th>
                                <th class="text-white py-3 border-0">Kasir</th>
                                <th class="text-white py-3 border-0">Pelanggan</th>
                                <th class="text-white py-3 text-center border-0">Item</th>
                                <th class="text-white py-3 border-0">Total</th>
                                <th class="text-white py-3 border-0">Profit</th>
                                <th class="text-white py-3 text-center pe-4 border-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $index => $sale)
                            @php
                            $totalCost = $sale->details->sum(function($d) {
                            return ($d->product->purchase_price ?? 0) * $d->qty;
                            });
                            $profit = $sale->final_price - $totalCost;
                            @endphp
                            <tr class="row-hover-effect">
                                <td class="ps-4 text-muted small">{{ ($sales->currentPage() - 1) * $sales->perPage() + $loop->iteration }}</td>
                                <td>
                                    <span class="fw-bold invoice-text">{{ $sale->invoice_number }}</span>
                                </td>
                                <td class="text-muted small">{{ $sale->created_at->format('d M Y, H:i') }}</td>
                                <td class="kasir-name-text">{{ $sale->user->name ?? 'Admin' }}</td>
                                <td><span class="badge bg-light-secondary rounded-pill" style="font-size: 0.7rem; font-weight: 600;">UMUM</span></td>
                                <td class="text-center">
                                    <span class="badge bg-primary-light rounded-pill fw-bold" style="min-width: 25px;">
                                        {{ $sale->details->count() }}
                                    </span>
                                </td>
                                <td class="fw-bold invoice-text">Rp {{ number_format($sale->final_price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="fw-bold text-success" style="font-size: 0.9rem;">Rp {{ number_format($profit, 0, ',', '.') }}</span>
                                </td>
                                <td class="text-center pe-4">
                                    <!-- FIX ICON PRINT: Tengah Sempurna menggunakan class .btn-icon khusus -->
                                    <a href="{{ route('kasir.print', $sale->id) }}" class="btn btn-icon btn-light-primary rounded-circle" title="Cetak Struk">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted small">
                                    <img src="{{ asset('admin/assets/static/images/svg/empty.svg') }}" style="height: 100px; opacity: 0.5;" class="mb-3 d-block mx-auto">
                                    Belum ada transaksi ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $sales->links('pagination::bootstrap-5') }}
        </div>
    </section>
</div>
@endsection