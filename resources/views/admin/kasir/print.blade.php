@extends('layouts.kasir')

@section('title', 'Invoice ' . $sale->invoice_number)

@section('content')
<style>
    /* --- WRAPPER BACKGROUND --- */
    .invoice-wrapper {
        height: calc(100vh - 55px);
        /* Membatasi tinggi agar bisa di-scroll */
        overflow-y: auto;
        /* Mengaktifkan scroll vertikal */
        background-color: #f4f6f9;
        padding: 3rem 1rem;
        transition: background-color 0.3s;
    }

    /* Kustomisasi Scrollbar agar lebih rapi (Opsional) */
    .invoice-wrapper::-webkit-scrollbar {
        width: 8px;
    }

    .invoice-wrapper::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }

    /* --- TOP BAR (BUTTONS) --- */
    .top-bar {
        width: 100%;
        max-width: 800px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0 auto 2.5rem auto;
        /* margin auto untuk ketengah */
    }

    .btn-back {
        background-color: white;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        border-radius: 50rem;
        padding: 0.6rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        line-height: 1;
        /* Memastikan sejajar */
    }

    .btn-back i {
        display: flex;
        align-items: center;
        line-height: 0;
        font-size: 1.1rem;
    }

    .btn-back:hover {
        background-color: #f8fafc;
        color: #1e293b;
    }

    .format-group {
        background-color: #e2e8f0;
        border-radius: 10px;
        padding: 5px;
        display: flex;
        gap: 5px;
    }

    .btn-format {
        background: transparent;
        border: none;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        transition: 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-format.active {
        background: white;
        color: #1e293b;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .btn-cetak {
        background-color: #6366f1;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: 0.2s;
        line-height: 1;
    }

    .btn-cetak i {
        display: flex;
        align-items: center;
        line-height: 0;
    }

    .btn-cetak:hover {
        background-color: #4f46e5;
        color: white;
    }

    /* ======================================================== */
    /* 1. LAYOUT INVOICE (WEB / A4)                             */
    /* ======================================================== */
    .invoice-card {
        background: #ffffff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        width: 100%;
        max-width: 800px;
        color: #1e293b;
        margin: 0 auto;
        /* margin auto untuk ketengah */
    }

    .invoice-header {
        background: #4f46e5;
        color: white;
        padding: 2.5rem;
    }

    .status-badge {
        background: #d1fae5;
        color: #059669;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .table-invoice th {
        border-top: none;
        color: #94a3b8;
        font-size: 0.8rem;
        text-transform: uppercase;
        padding-bottom: 1rem;
    }

    /* ======================================================== */
    /* 2. LAYOUT STRUK 80MM                                     */
    /* ======================================================== */
    .receipt-80mm {
        background: #ffffff;
        color: #000000;
        font-family: 'Courier New', Courier, monospace;
        font-size: 13.5px;
        line-height: 1.4;
        width: 320px;
        /* Lebar pas untuk 80mm */
        padding: 2rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
    }

    .receipt-80mm .dashed-line {
        border-bottom: 1px dashed #000;
        margin: 8px 0;
    }

    .receipt-80mm .double-line {
        border-bottom: 2px solid #000;
        /* Menggunakan border ganda agar lebih rapi dari === */
        border-top: 2px solid #000;
        height: 4px;
        margin: 8px 0;
    }

    /* ======================================================== */
    /* 3. LAYOUT STRUK 58MM                                     */
    /* ======================================================== */
    .receipt-58mm {
        background: #ffffff;
        color: #000000;
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
        /* Ukuran font lebih kecil */
        line-height: 1.3;
        width: 230px;
        /* Lebar pas untuk 58mm */
        padding: 1.5rem 1rem;
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
    }

    .receipt-58mm .dashed-line {
        border-bottom: 1px dashed #000;
        margin: 6px 0;
    }

    /* ======================================================== */
    /* DARK MODE SUPPORT (Kertas Struk Tetap Putih)             */
    /* ======================================================== */
    [data-bs-theme="dark"] .invoice-wrapper {
        background-color: #0f172a;
    }

    [data-bs-theme="dark"] .btn-back {
        background-color: #1e1e2d;
        border-color: #2d2d3e;
        color: #f8fafc;
    }

    [data-bs-theme="dark"] .format-group {
        background-color: #1e1e2d;
    }

    [data-bs-theme="dark"] .btn-format {
        color: #94a3b8;
    }

    [data-bs-theme="dark"] .btn-format.active {
        background-color: #2d2d3e;
        color: #f8fafc;
    }

    /* Pastikan struk / invoice tetap terlihat seperti kertas fisik (putih) */
    .invoice-card *,
    .receipt-80mm *,
    .receipt-58mm * {
        color: inherit;
    }

    /* ======================================================== */
    /* MEDIA PRINT SETTINGS                                     */
    /* ======================================================== */
    @media print {

        body,
        html {
            background: white !important;
            margin: 0;
            padding: 0;
        }

        /* Sembunyikan elemen UI yang tidak perlu diprint */
        header,
        .sidebar,
        .navbar,
        .top-bar,
        .main-footer,
        .no-print {
            display: none !important;
        }

        .invoice-wrapper {
            background: white !important;
            padding: 0 !important;
            min-height: auto !important;
            height: auto !important;
            overflow-y: visible !important;
        }

        /* Format kertas saat diprint (Hilangkan bayangan & border-radius) */
        .print-area {
            box-shadow: none !important;
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 0 !important;
            width: 100% !important;
            /* Memaksa mengisi lebar kertas printer */
        }

        .receipt-80mm {
            max-width: 80mm;
            padding: 5mm !important;
        }

        .receipt-58mm {
            max-width: 58mm;
            padding: 2mm !important;
            font-size: 10px;
        }

        .invoice-card {
            max-width: 100%;
            padding: 10mm !important;
        }
    }
</style>

<!-- Tempat injeksi dinamis untuk ukuran halaman print -->
<style id="dynamic-print-style"></style>

<div class="invoice-wrapper">

    <!-- TOP BAR TOMBOL -->
    <div class="top-bar no-print">
        <a href="{{ route('kasir.index') }}" class="btn-back">
            <i class="bi bi-arrow-left"></i> <span>Kembali ke kasir</span>
        </a>

        <div class="d-flex align-items-center gap-3">
            <div class="format-group">
                <button class="btn-format active" onclick="switchFormat('invoice')">
                    <i class="bi bi-file-earmark-text"></i> Invoice
                </button>
                <button class="btn-format" onclick="switchFormat('80mm')">
                    <i class="bi bi-receipt"></i> Struk 80mm
                </button>
                <button class="btn-format" onclick="switchFormat('58mm')">
                    <i class="bi bi-receipt"></i> Struk 58mm
                </button>
            </div>
            <button onclick="window.print()" class="btn-cetak">
                <i class="bi bi-printer"></i> <span>Cetak</span>
            </button>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- 1. TAMPILAN INVOICE (A4/Web Formatted)                     -->
    <!-- ========================================================== -->
    <div id="view-invoice" class="print-area invoice-card">
        <div class="invoice-header d-flex justify-content-between align-items-start">
            <div>
                <!-- Perbaikan Alignment Icon Surat dengan tulisan Invoice -->
                <div class="d-flex align-items-center gap-2 mb-2" style="line-height: 1;">
                    <i class="bi bi-file-earmark-text text-white d-flex align-items-center" style="font-size: 1.5rem; line-height: 0;"></i>
                    <span class="fw-bold text-uppercase text-white d-flex align-items-center" style="margin-top: 2px; font-size: 1rem;">Invoice</span>
                </div>
                <h2 class="fw-bold mb-1 text-white">{{ $sale->invoice_number }}</h2>
                <p class="mb-0 text-white opacity-75">{{ $sale->created_at->format('d M Y, H.i') }}</p>
            </div>
            <div class="text-end">
                <span class="status-badge mb-2 d-inline-block">Lunas</span>
                <p class="fw-bold mb-0 text-white">Tunai</p>
            </div>
        </div>

        <div class="p-5">
            <div class="row mb-5">
                <div class="col-md-6">
                    <p class="text-muted small text-uppercase fw-bold mb-2">Pelanggan</p>
                    <h5 class="fw-bold mb-1">Pelanggan Umum</h5>
                    <p class="text-muted small">Transaksi Penjualan Apotek</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted small text-uppercase fw-bold mb-2">Kasir</p>
                    <h5 class="fw-bold mb-1">{{ $sale->user->name ?? 'Kasir' }}</h5>
                </div>
            </div>

            <table class="table table-invoice mb-5">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->details as $item)
                    <tr>
                        <td class="py-3">
                            <div class="fw-bold text-dark">{{ $item->product_name }}</div>
                            <small class="text-muted">Item ID: {{ $item->product_id }}</small>
                        </td>
                        <td class="text-center py-3">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-center py-3">{{ $item->qty }}</td>
                        <td class="text-end py-3 fw-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row justify-content-end">
                <div class="col-md-5">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
                    </div>
                    @if($sale->total_discount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Diskon</span>
                        <span class="text-danger">- Rp {{ number_format($sale->total_discount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Total</h4>
                        <h4 class="fw-bold text-primary mb-0">Rp {{ number_format($sale->final_price, 0, ',', '.') }}</h4>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tunai</span>
                        <span class="fw-bold">Rp {{ number_format($sale->cash_received, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-success fw-bold">Kembali</span>
                        <span class="text-success fw-bold">Rp {{ number_format($sale->cash_change, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <p class="text-muted small text-uppercase" style="letter-spacing: 2px;">Terima kasih telah berbelanja</p>
            </div>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- 2. TAMPILAN STRUK 80MM                                     -->
    <!-- ========================================================== -->
    <div id="view-80mm" class="print-area receipt-80mm d-none">

        <div class="text-center mb-3">
            <div class="fw-bold" style="font-size: 1.1rem;">TOKO ANDA</div>
            <div>Jl. Contoh No. 123</div>
            <div>Telp: 08123456789</div>
        </div>

        <div class="double-line"></div>

        <div class="d-flex justify-content-between">
            <span>No:</span>
            <span>{{ $sale->invoice_number }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Tgl:</span>
            <span>{{ $sale->created_at->format('d/m/Y, H.i') }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Kasir:</span>
            <span>{{ $sale->user->name ?? 'Kasir' }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Pelanggan:</span>
            <span>Umum</span>
        </div>

        <div class="double-line"></div>

        @foreach($sale->details as $item)
        <div class="mb-2">
            <div>{{ $item->product_name }}</div>
            <div class="d-flex justify-content-between">
                <span>{{ $item->qty }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach

        <div class="dashed-line"></div>

        <div class="d-flex justify-content-between">
            <span>Subtotal</span>
            <span>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
        </div>

        @if($sale->total_discount > 0)
        <div class="d-flex justify-content-between">
            <span>Diskon</span>
            <span>- Rp {{ number_format($sale->total_discount, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="d-flex justify-content-between fw-bold mt-1">
            <span>TOTAL</span>
            <span>Rp {{ number_format($sale->final_price, 0, ',', '.') }}</span>
        </div>

        <div class="dashed-line"></div>

        <div class="d-flex justify-content-between">
            <span>Bayar (TUNAI)</span>
            <span>Rp {{ number_format($sale->cash_received, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between fw-bold mt-1">
            <span>Kembali</span>
            <span>Rp {{ number_format($sale->cash_change, 0, ',', '.') }}</span>
        </div>

        <div class="double-line"></div>

        <div class="text-center mt-3">
            <div>Terima kasih</div>
            <div>Barang yang sudah dibeli</div>
            <div>tidak dapat ditukar/dikembalikan</div>
        </div>

    </div>

    <!-- ========================================================== -->
    <!-- 3. TAMPILAN STRUK 58MM                                     -->
    <!-- ========================================================== -->
    <div id="view-58mm" class="print-area receipt-58mm d-none">

        <div class="text-center fw-bold">TOKO ANDA</div>
        <div class="text-center mb-1">08123456789</div>

        <div class="dashed-line"></div>

        <div>#{{ $sale->invoice_number }}</div>
        <div>{{ $sale->created_at->format('d/m/Y, H.i') }}</div>

        <div class="dashed-line"></div>

        @foreach($sale->details as $item)
        <div class="mb-1">
            <div>{{ $item->product_name }}</div>
            <div class="d-flex justify-content-between">
                <span>{{ $item->qty }}x</span>
                <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach

        <div class="dashed-line"></div>

        <div class="d-flex justify-content-between fw-bold">
            <span>TOTAL</span>
            <span>Rp{{ number_format($sale->final_price, 0, ',', '.') }}</span>
        </div>

        @if($sale->total_discount > 0)
        <div class="d-flex justify-content-between">
            <span>Diskon</span>
            <span>-Rp{{ number_format($sale->total_discount, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="d-flex justify-content-between">
            <span>Bayar</span>
            <span>Rp{{ number_format($sale->cash_received, 0, ',', '.') }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span>Kembali</span>
            <span>Rp{{ number_format($sale->cash_change, 0, ',', '.') }}</span>
        </div>

        <div class="dashed-line"></div>

        <div class="text-center mt-2">
            Terima kasih!
        </div>

    </div>

</div>

<script>
    // Set default view to 'invoice'
    let currentFormat = 'invoice';

    function switchFormat(format) {
        currentFormat = format;

        // Reset semua tombol & view
        const buttons = document.querySelectorAll('.btn-format');
        const views = document.querySelectorAll('.print-area');

        buttons.forEach(btn => btn.classList.remove('active'));
        views.forEach(view => view.classList.add('d-none'));

        // Aktifkan yang dipilih
        if (format === 'invoice') {
            buttons[0].classList.add('active');
            document.getElementById('view-invoice').classList.remove('d-none');
            setPrintPageSize('A4');
        } else if (format === '80mm') {
            buttons[1].classList.add('active');
            document.getElementById('view-80mm').classList.remove('d-none');
            setPrintPageSize('80mm');
        } else if (format === '58mm') {
            buttons[2].classList.add('active');
            document.getElementById('view-58mm').classList.remove('d-none');
            setPrintPageSize('58mm');
        }
    }

    function setPrintPageSize(size) {
        const styleTag = document.getElementById('dynamic-print-style');
        if (size === 'A4') {
            styleTag.innerHTML = `@media print { @page { size: A4 portrait; margin: 10mm; } }`;
        } else if (size === '80mm') {
            styleTag.innerHTML = `@media print { @page { size: 80mm auto; margin: 0; } }`;
        } else if (size === '58mm') {
            styleTag.innerHTML = `@media print { @page { size: 58mm auto; margin: 0; } }`;
        }
    }

    // Initialize default CSS rules for printing
    setPrintPageSize('A4');
</script>
@endsection