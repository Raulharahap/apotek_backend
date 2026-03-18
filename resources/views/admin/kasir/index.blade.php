@extends('layouts.kasir')

@section('content')
<style>
    /* --- LAYOUT UTAMA --- */
    .cart-section-container {
        height: calc(100vh - 55px);
        background: white;
        border-left: 1px solid var(--kasir-border, #e2e8f0);
        display: flex;
        flex-direction: column;
    }

    .scrollable-area {
        overflow-y: auto;
        height: calc(100vh - 55px);
    }

    .cart-scroll-area {
        flex: 1 1 auto;
        overflow-y: auto;
        padding: 1.25rem;
    }

    .scrollable-area::-webkit-scrollbar,
    .cart-scroll-area::-webkit-scrollbar {
        width: 5px;
    }

    .scrollable-area::-webkit-scrollbar-thumb,
    .cart-scroll-area::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }

    .cart-sticky-bottom {
        flex: 0 0 auto;
        padding: 1rem 1.25rem;
        background: white;
        border-top: 1px solid #f1f5f9;
        box-shadow: 0 -10px 15px -3px rgba(0, 0, 0, 0.02);
        z-index: 10;
    }

    /* --- INPUT SEARCH --- */
    .search-kasir {
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        font-size: 0.9rem;
        background-color: white;
        color: #111827;
    }

    /* --- LABEL & FORM --- */
    .label-kasir {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0.4rem;
    }

    .form-kasir {
        background-color: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #1e293b;
        font-weight: 600;
    }

    .form-kasir:focus {
        border-color: var(--kasir-primary, #3b82f6);
        box-shadow: 0 0 0 3px var(--kasir-primary-light, #bfdbfe);
    }

    /* --- FILTER KATEGORI --- */
    .btn-category {
        border-radius: 8px;
        padding: 6px 16px;
        border: 1px solid var(--kasir-border, #e2e8f0);
        background-color: white;
        color: #4b5563;
        font-size: 0.9rem;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .btn-category.active {
        background-color: var(--kasir-primary, #3b82f6) !important;
        color: white !important;
        border-color: var(--kasir-primary, #3b82f6) !important;
    }

    /* --- PRODUK CARD --- */
    .product-card {
        border: 1px solid #f3f4f6;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }

    .product-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        transform: translateY(-3px);
        border-color: #e5e7eb;
    }

    /* Mencegah klik jika stok kosong */
    .disabled-card {
        cursor: not-allowed !important;
    }

    .disabled-card:hover {
        box-shadow: none !important;
        transform: none !important;
        border-color: #f3f4f6 !important;
    }

    /* Overlay visual agar klik tembus ke card */
    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 120px;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: 0.2s;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        pointer-events: none !important;
    }

    .product-overlay * {
        pointer-events: none !important;
        user-select: none !important;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    /* --- STOK BADGE --- */
    .stock-badge {
        font-size: 0.7rem;
        background-color: rgba(255, 255, 255, 0.95);
        color: #334155;
        backdrop-filter: blur(4px);
    }

    .stock-badge-danger {
        font-size: 0.7rem;
        background-color: rgba(254, 226, 226, 0.95);
        color: #dc2626;
        backdrop-filter: blur(4px);
    }

    /* --- METODE BAYAR & NOMINAL --- */
    .btn-payment {
        border: 1px solid #e2e8f0;
        background: white;
        color: #475569;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-payment.active {
        background-color: var(--kasir-primary-light, #eff6ff);
        border-color: var(--kasir-primary, #3b82f6);
        color: var(--kasir-primary, #3b82f6);
    }

    .btn-nominal {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        border-radius: 8px;
        padding: 6px 0;
        font-size: 0.85rem;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-nominal.active {
        background-color: var(--kasir-primary, #3b82f6) !important;
        border-color: var(--kasir-primary, #3b82f6) !important;
        color: white !important;
    }

    /* --- TRANSAKSI DITAHAN UI --- */
    .btn-dashed-hold {
        border: 1.5px dashed #f59e0b !important;
        color: #f59e0b;
        background-color: #fffbeb;
        font-size: 0.9rem;
        padding: 10px;
        font-weight: 600;
        transition: 0.2s;
    }

    .btn-dashed-hold:hover {
        background-color: #fef3c7;
    }

    .hold-input-box {
        border: 2px solid var(--kasir-primary, #3b82f6) !important;
        border-radius: 8px;
        background: white;
        padding: 2px 10px;
    }

    .hold-input-box input {
        border: none !important;
        box-shadow: none !important;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .btn-ok-hold {
        background-color: #f59e0b;
        color: white;
        border-radius: 8px !important;
        font-weight: 700;
        min-width: 45px;
        height: 38px;
        border: none;
        transition: 0.2s ease;
    }

    .btn-ok-hold:hover,
    .btn-ok-hold:focus,
    .btn-ok-hold:active {
        background-color: #d97706 !important;
        color: white !important;
    }

    /* ACCORDION HOLD STYLE */
    .accordion-hold-btn {
        background-color: #fffaf0 !important;
        color: #d97706 !important;
        font-weight: 600;
        box-shadow: none !important;
    }

    .accordion-hold-btn::after {
        filter: invert(53%) sepia(85%) saturate(1478%) hue-rotate(1deg) brightness(97%) contrast(93%);
    }

    /* --- KERANJANG SIMETRIS --- */
    .cart-item-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.25rem;
    }

    .cart-item-info {
        flex: 1;
        min-width: 0;
    }

    .cart-item-price-total {
        width: 90px;
        text-align: right;
    }

    /* --- CHANGE BOX (KEMBALIAN) --- */
    .bg-success-light {
        background-color: #f0fdf4 !important;
        border: 1px solid #bbf7d0 !important;
        border-radius: 8px;
    }

    .text-success-custom {
        color: #16a34a !important;
    }

    /* ======================================================== */
    /* FULL SUPPORT DARK MODE                                   */
    /* ======================================================== */
    [data-bs-theme="dark"] .cart-section-container,
    [data-bs-theme="dark"] .cart-sticky-bottom {
        background-color: #1e1e2d !important;
        border-color: #2d2d3e !important;
    }

    [data-bs-theme="dark"] .search-kasir,
    [data-bs-theme="dark"] .hold-input-box {
        background-color: #2d2d3e !important;
        border: 1px solid #4b5563 !important;
        color: #f8fafc !important;
    }

    [data-bs-theme="dark"] .btn-category:not(.active) {
        border: 1px solid #4b5563 !important;
        background-color: #1e1e2d !important;
        color: #cbd5e1 !important;
    }

    [data-bs-theme="dark"] .form-kasir,
    [data-bs-theme="dark"] .hold-input-box input {
        background-color: transparent !important;
        color: #f8fafc !important;
    }

    [data-bs-theme="dark"] .btn-nominal:not(.active) {
        background-color: #2d2d3e !important;
        border: 1px solid #4b5563 !important;
        color: #cbd5e1 !important;
    }

    [data-bs-theme="dark"] .btn-payment {
        background-color: #2d2d3e !important;
        border-color: #4b5563 !important;
        color: #cbd5e1 !important;
    }

    [data-bs-theme="dark"] .btn-payment.active {
        background-color: rgba(99, 102, 241, 0.15) !important;
        border-color: var(--kasir-primary, #3b82f6) !important;
        color: var(--kasir-primary, #3b82f6) !important;
    }

    [data-bs-theme="dark"] .bg-success-light {
        background-color: rgba(74, 222, 128, 0.08) !important;
        border: 1px solid rgba(74, 222, 128, 0.2) !important;
    }

    [data-bs-theme="dark"] .text-success-custom {
        color: #4ade80 !important;
    }

    /* Dark Mode untuk Stok Badge */
    [data-bs-theme="dark"] .stock-badge {
        background-color: rgba(30, 30, 45, 0.95) !important;
        color: #cbd5e1 !important;
        border-color: #4b5563 !important;
    }

    [data-bs-theme="dark"] .stock-badge-danger {
        background-color: rgba(127, 29, 29, 0.95) !important;
        color: #fca5a5 !important;
        border-color: #991b1b !important;
    }

    /* Dark mode untuk list accordion hold */
    [data-bs-theme="dark"] .accordion-hold-btn,
    [data-bs-theme="dark"] .hold-item-row {
        background-color: #2d2d3e !important;
        border-color: #4b5563 !important;
    }

    [data-bs-theme="dark"] .hold-item-title {
        color: #e2e8f0 !important;
    }
</style>

<div class="row g-0">
    <div class="col-lg-8 py-3 px-4 scrollable-area">
        <div class="mb-3 position-relative">
            <input type="text" class="form-control rounded-pill px-4 py-2 search-kasir" id="searchProduct" placeholder="Cari produk atau scan barcode...">
            <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-4 text-muted"></i>
        </div>

        <div class="d-flex gap-2 mb-3 overflow-x-auto pb-2" style="scrollbar-width: none;">
            <button class="btn btn-category active filter-btn" data-id="all">Semua</button>
            @foreach($categories as $cat)
            <button class="btn btn-category filter-btn" data-id="{{ $cat->id }}">{{ $cat->name }}</button>
            @endforeach
        </div>

        <div class="row g-3" id="product-list">
            @foreach($products as $product)
            <div class="col-6 col-md-4 col-xl-3 product-item" data-category="{{ $product->category_id }}">

                <!-- UPDATE: Hapus atribut style="{ { ... } }" inline agar VS Code tidak eror -->
                <div class="card product-card h-100 bg-white border-0 {{ $product->stock <= 0 ? 'opacity-50 disabled-card' : 'btn-add-cart' }}"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->selling_price }}"
                    data-stock="{{ $product->stock }}"
                    data-image="{{ $product->image ? asset('uploads/products/'.$product->image) : 'https://via.placeholder.com/50' }}">

                    <div class="position-relative">
                        <img src="{{ $product->image ? asset('uploads/products/'.$product->image) : 'https://via.placeholder.com/300x200' }}"
                            class="card-img-top"
                            style="height: 120px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px;">

                        <!-- INDIKATOR STOK (POJOK KANAN ATAS) -->
                        <div class="position-absolute top-0 end-0 m-2" style="z-index: 2; pointer-events: none;">
                            @if($product->stock > 0)
                            <span class="badge rounded-pill shadow-sm border px-2 py-1 d-flex align-items-center gap-1 stock-badge">
                                <i class="bi bi-box-seam text-primary" style="font-size: 0.7rem;"></i> {{ $product->stock }}
                            </span>
                            @else
                            <span class="badge rounded-pill shadow-sm border border-danger px-2 py-1 d-flex align-items-center gap-1 stock-badge-danger">
                                <i class="bi bi-x-circle" style="font-size: 0.7rem;"></i> Habis
                            </span>
                            @endif
                        </div>

                        <!-- UPDATE: Overlay Tombol "Tambah" hanya muncul jika stok > 0 -->
                        @if($product->stock > 0)
                        <div class="product-overlay">
                            <span class="btn btn-primary rounded-pill btn-sm px-4 py-2 fw-bold shadow">+ Tambah</span>
                        </div>
                        @endif
                    </div>
                    <div class="card-body p-2 text-center">
                        <h6 class="mb-1 text-truncate text-dark fw-semibold" style="font-size: 0.8rem;">{{ $product->name }}</h6>
                        <h6 class="text-primary fw-bold mb-0" style="font-size: 0.9rem;">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</h6>
                    </div>
                </div>
            </div>
            @endforeach

            <div id="empty-state" class="col-12 text-center py-5 d-none">
                <i class="bi bi-box-seam text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="text-muted mt-2 fw-semibold">Produk tidak ditemukan</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4 cart-section-container p-0">
        <div class="cart-scroll-area">

            <!-- ACCORDION HOLD TRANSAKSI -->
            <div class="accordion mb-3 d-none" id="accordionHold">
                <div class="accordion-item accordion-hold border-0 shadow-sm rounded-3 overflow-hidden">
                    <h2 class="accordion-header">
                        <button class="accordion-button accordion-hold-btn d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHold">
                            <span class="badge me-2 d-flex align-items-center justify-content-center" id="holdCount"
                                style="background-color: #f59e0b; color: white; width: 26px; height: 26px; font-size: 0.85rem; border-radius: 6px;">0</span>
                            Transaksi Ditahan
                        </button>
                    </h2>
                    <div id="collapseHold" class="accordion-collapse collapse show" data-bs-parent="#accordionHold">
                        <div class="accordion-body p-0" id="holdListContainer"></div>
                    </div>
                </div>
            </div>

            <button id="btnShowHold" class="btn w-100 rounded-3 mb-4 d-none align-items-center justify-content-center gap-2 btn-dashed-hold">
                <i class="bi bi-clock"></i> Tahan Transaksi
            </button>

            <div id="sectionHoldInput" class="d-none align-items-center gap-2 mb-4">
                <div class="hold-input-box flex-grow-1">
                    <input type="text" id="holdLabelInput" class="form-control px-0" placeholder="Label (opsional)">
                </div>
                <button class="btn btn-ok-hold btn-icon-center" id="btnConfirmHold" type="button">OK</button>
                <button id="btnCancelHold" class="btn btn-link text-decoration-none btn-icon-center btn-close-custom"
                    style="width: 30px; height: 30px;">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="label-kasir mb-0"><i class="bi bi-cart3 me-1"></i> Keranjang</span>
                <span class="badge rounded-pill px-3 py-1 fw-bold" id="cartCountBadge"
                    style="background-color: var(--kasir-primary-light, #eff6ff); color: var(--kasir-primary, #3b82f6); font-size: 0.7rem;">0 item</span>
            </div>

            <div id="cartItemsContainer"></div>

            <hr style="border-color: #f1f5f9;">

            <div class="mb-3 mt-3">
                <div class="label-kasir">Metode Pembayaran</div>
                <button class="btn btn-payment active d-inline-flex align-items-center justify-content-center gap-2 px-4 py-2">
                    <i class="bi bi-cash" style="font-size: 1.1rem; line-height: 0;"></i> <span>Tunai</span>
                </button>
            </div>

            <div class="mb-3">
                <div class="label-kasir">Nominal Cepat</div>
                <div class="row g-2" id="quickCashRow">
                    @foreach([10000, 20000, 50000, 100000] as $amt)
                    <div class="col-3">
                        <button class="btn btn-nominal w-100 btn-quick-cash" data-amount="{{ $amt }}">
                            Rp {{ number_format($amt/1000, 0) }}k
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-3">
                <div class="label-kasir">Diskon (Rp)</div>
                <input type="number" id="input-diskon" class="form-control form-kasir py-2 px-3" placeholder="0" value="0" min="0">
            </div>
            <div class="mb-1">
                <div class="label-kasir">Jumlah Bayar (Rp)</div>
                <input type="number" id="input-bayar" class="form-control form-kasir py-2 px-3 fs-6" placeholder="0" min="0">
            </div>
        </div>

        <div class="cart-sticky-bottom">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="text-secondary fw-medium" style="font-size: 0.8rem;">Subtotal</span>
                <span class="text-dark fw-semibold" id="subtotalText" style="font-size: 0.85rem;">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-dark fw-bold" style="font-size: 0.95rem;">Total</span>
                <span class="fw-bold text-primary" style="font-size: 1.2rem;" id="totalText" data-val="0">Rp 0</span>
            </div>

            <div class="d-flex justify-content-between align-items-center p-2 rounded-3 mb-3 bg-success-light" id="changeBox">
                <span class="fw-medium text-success-custom" id="changeLabel" style="font-size: 0.85rem;">Kembalian</span>
                <span class="fw-bold text-success-custom" style="font-size: 0.95rem;" id="text-kembalian">Rp 0</span>
            </div>

            <button class="btn btn-primary w-100 rounded-3 py-2 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm disabled"
                id="btnCheckout" style="font-size: 0.9rem;">
                <i class="bi bi-receipt"></i> <span id="btnCheckoutText">Selesaikan Transaksi</span>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        let cart = [];

        function checkEmpty() {
            let visible = $('.product-item:visible').length;
            $('#empty-state').toggleClass('d-none', visible > 0);
        }

        function safeAlert(icon, title, text) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                alert(title + "\n" + text);
            }
        }

        function showToast(message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    text: message,
                    toast: true,
                    position: 'top-right',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    icon: 'success'
                });
            } else {
                console.log(message);
            }
        }

        // --- FILTER ---
        $('.filter-btn').on('click', function() {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            let cat = $(this).data('id');
            $('.product-item').each(function() {
                $(this).toggle(cat === 'all' || $(this).data('category') == cat);
            });
            checkEmpty();
        });

        // --- SEARCH ---
        $('#searchProduct').on('keyup', function() {
            let val = $(this).val().toLowerCase();
            $('.product-item').each(function() {
                $(this).toggle($(this).find('.card-body').text().toLowerCase().indexOf(val) > -1);
            });
            checkEmpty();
        });

        // --- ADD TO CART ---
        $(document).on('click', '.btn-add-cart', function(e) {
            e.preventDefault();

            let id = String($(this).data('id'));
            let name = $(this).data('name');
            let price = parseInt($(this).data('price')) || 0;
            let stock = parseInt($(this).data('stock')) || 0;
            let image = $(this).data('image');

            let exist = cart.find(i => String(i.id) === id);

            if (exist) {
                if (exist.qty >= stock) {
                    safeAlert('warning', 'Stok Terbatas', 'Jumlah melebihi stok tersedia!');
                    return;
                }
                exist.qty++;
            } else {
                if (stock <= 0) {
                    safeAlert('error', 'Stok Habis', 'Stok produk ini kosong!');
                    return;
                }
                cart.push({
                    id,
                    name,
                    price,
                    stock,
                    image,
                    qty: 1
                });
            }

            showToast(name + ' ditambahkan');
            renderCart();
        });

        function renderCart() {
            let html = '',
                subtotal = 0;

            if (cart.length > 0) {
                $('#btnShowHold').removeClass('d-none').addClass('d-flex');
            } else {
                $('#btnShowHold').addClass('d-none').removeClass('d-flex');
                $('#sectionHoldInput').addClass('d-none');
            }

            cart.forEach((item, index) => {
                let totalItem = item.price * item.qty;
                subtotal += totalItem;
                html += `
                <div class="cart-item-row">
                    <img src="${item.image}" class="rounded-3 border" width="45" height="45" style="object-fit: cover; flex-shrink: 0;">
                    <div class="cart-item-info">
                        <h6 class="mb-0 fw-bold text-dark text-truncate" style="font-size: 0.85rem;">${item.name}</h6>
                        <small class="text-muted fw-medium" style="font-size: 0.75rem;">Rp ${item.price.toLocaleString('id-ID')} × ${item.qty}</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-light rounded-2 border btn-qty d-flex align-items-center justify-content-center p-0" data-index="${index}" data-act="minus" style="width: 26px; height: 26px;">-</button>
                        <span class="fw-bold text-dark text-center" style="font-size: 0.8rem; min-width: 16px;">${item.qty}</span>
                        <button class="btn btn-sm btn-light rounded-2 border btn-qty d-flex align-items-center justify-content-center p-0" data-index="${index}" data-act="plus" style="width: 26px; height: 26px;">+</button>
                        <i class="bi bi-trash3 text-danger ms-1 btn-remove d-flex align-items-center justify-content-center" data-index="${index}" style="cursor: pointer; font-size: 1.1rem; width: 26px; height: 26px;"></i>
                    </div>
                    <div class="cart-item-price-total">
                        <h6 class="mb-0 fw-bold text-primary" style="font-size: 0.85rem;">Rp ${totalItem.toLocaleString('id-ID')}</h6>
                    </div>
                </div>`;
            });

            $('#cartItemsContainer').html(html || '<div class="text-center py-4 text-muted small">Keranjang kosong</div>');
            $('#cartCountBadge').text(cart.length + ' item');
            updateTotal(subtotal);
        }

        function updateTotal(subtotal) {
            let diskon = parseInt($('#input-diskon').val()) || 0;
            let final = subtotal - diskon;
            $('#subtotalText').text('Rp ' + subtotal.toLocaleString('id-ID'));
            $('#totalText').text('Rp ' + (final < 0 ? 0 : final).toLocaleString('id-ID')).data('val', final < 0 ? 0 : final);
            calcKembali();
        }

        function calcKembali() {
            let total = $('#totalText').data('val') || 0;
            let bayar = parseInt($('#input-bayar').val()) || 0;
            let kembali = bayar - total;

            if (total <= 0) {
                $('#btnCheckout').addClass('disabled');
                $('#btnCheckoutText').text('Selesaikan Transaksi');

                $('#text-kembalian').text('Rp 0');
                $('#changeLabel').text('Kembalian');
                $('#changeBox').addClass('bg-success-light').css({
                    'background-color': '',
                    'border': ''
                });
                $('#changeBox .text-success-custom').css('color', '');

                return;
            }

            if (kembali < 0) {
                $('#text-kembalian').text('Rp ' + Math.abs(kembali).toLocaleString('id-ID'));
                $('#changeLabel').text('Kurang');
                $('#changeBox')
                    .removeClass('bg-success-light')
                    .css({
                        'background-color': '#fff1f2',
                        'border': '1px solid #fecdd3'
                    });
                $('#changeBox .text-success-custom').css('color', '#e11d48');
                $('#btnCheckout').addClass('disabled');
                $('#btnCheckoutText').text('Uang Kurang Rp ' + Math.abs(kembali).toLocaleString('id-ID'));
            } else {
                $('#text-kembalian').text('Rp ' + kembali.toLocaleString('id-ID'));
                $('#changeLabel').text('Kembalian');
                $('#changeBox')
                    .addClass('bg-success-light')
                    .css({
                        'background-color': '',
                        'border': ''
                    });
                $('#changeBox .text-success-custom').css('color', '');
                $('#btnCheckout').removeClass('disabled');
                $('#btnCheckoutText').text('Selesaikan Transaksi');
            }
        }

        $(document).on('click', '.btn-qty', function(e) {
            e.stopPropagation();
            let idx = $(this).data('index');
            let item = cart[idx];
            if ($(this).data('act') === 'plus') {
                if (item.qty >= item.stock) {
                    safeAlert('warning', 'Stok Habis', 'Melebihi stok tersedia.');
                    return;
                }
                item.qty++;
            } else if (item.qty > 1) {
                item.qty--;
            }
            renderCart();
        });

        $(document).on('click', '.btn-remove', function(e) {
            e.stopPropagation();
            cart.splice($(this).data('index'), 1);
            renderCart();
        });

        $('#input-bayar').on('input', function() {
            $('.btn-nominal').removeClass('active');
            calcKembali();
        });

        $('#input-diskon').on('input', function() {
            let sub = cart.reduce((a, b) => a + (b.price * b.qty), 0);
            updateTotal(sub);
        });

        $('.btn-quick-cash').on('click', function() {
            $('.btn-nominal').removeClass('active');
            $(this).addClass('active');
            $('#input-bayar').val($(this).data('amount'));
            calcKembali();
        });

        $('#btnShowHold').on('click', function() {
            $(this).addClass('d-none').removeClass('d-flex');
            $('#sectionHoldInput').removeClass('d-none').addClass('d-flex');
            $('#holdLabelInput').focus();
        });

        $('#btnCancelHold').on('click', function() {
            $('#sectionHoldInput').addClass('d-none').removeClass('d-flex');
            $('#btnShowHold').removeClass('d-none').addClass('d-flex');
            $('#holdLabelInput').val('');
        });

        refreshHoldList();

        // --- 1. FITUR TAHAN TRANSAKSI (HOLD) ---
        $('#btnConfirmHold').off('click').on('click', function() {
            if (cart.length === 0) return;

            let label = $('#holdLabelInput').val();
            let totalPrice = cart.reduce((a, b) => a + (b.price * b.qty), 0);

            $.ajax({
                url: "{{ url('/admin/kasir/hold') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    label: label,
                    cart_data: cart,
                    total_price: totalPrice
                },
                success: function(res) {
                    showToast("Transaksi berhasil ditahan");
                    cart = [];

                    $('#input-bayar').val('');
                    $('#input-diskon').val('0');
                    $('.btn-nominal').removeClass('active');

                    renderCart();

                    $('#holdLabelInput').val('');
                    $('#sectionHoldInput').addClass('d-none').removeClass('d-flex');
                    $('#btnShowHold').removeClass('d-none').addClass('d-flex');
                    refreshHoldList();
                },
                error: function(err) {
                    safeAlert('error', 'Gagal', 'Tidak bisa menahan transaksi');
                }
            });
        });

        // --- 2. FUNGSI REFRESH LIST HOLD (ACCORDION) FIX UI ---
        function refreshHoldList() {
            $.get("{{ url('/admin/kasir/hold-list') }}", function(res) {
                if (res.length > 0) {
                    $('#accordionHold').hide().removeClass('d-none').fadeIn();
                    $('#holdCount').text(res.length);

                    let html = '';
                    res.forEach(hold => {
                        let items = typeof hold.cart_data === 'string' ? JSON.parse(hold.cart_data) : hold.cart_data;
                        let itemCount = items ? items.length : 0;

                        html += `
                            <div class="d-flex justify-content-between align-items-center py-2 px-3 border-bottom hold-item-row" style="background-color: #fffaf0;">
                                <div>
                                    <h6 class="mb-1 fw-bold hold-item-title" style="color: #78350f; font-size: 0.9rem;">${hold.label || 'Tanpa Nama'}</h6>
                                    <small style="color: #f59e0b; font-weight: 600; font-size: 0.75rem;">${itemCount} item • Rp ${Number(hold.total_price).toLocaleString('id-ID')}</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn d-flex align-items-center justify-content-center btn-restore-hold shadow-sm p-0" data-id="${hold.id}" style="background-color: #f59e0b; color: white; border: none; width: 30px; height: 30px; border-radius: 6px;">
                                        <i class="bi bi-cart-plus" style="font-size: 1rem; line-height: 0; margin: 0;"></i>
                                    </button>
                                    <button class="btn d-flex align-items-center justify-content-center btn-delete-hold shadow-sm p-0" data-id="${hold.id}" style="background-color: transparent; color: #f59e0b; border: 1px solid #f59e0b; width: 30px; height: 30px; border-radius: 6px;">
                                        <i class="bi bi-trash3" style="font-size: 0.95rem; line-height: 0; margin: 0;"></i>
                                    </button>
                                </div>
                            </div>`;
                    });
                    $('#holdListContainer').html(html);
                } else {
                    $('#accordionHold').addClass('d-none');
                }
            });
        }

        // --- 3. PANGGIL BALIK (RESTORE) ---
        $(document).on('click', '.btn-restore-hold', function() {
            let id = $(this).data('id');

            $.get("{{ url('/admin/kasir/hold-list') }}", function(res) {
                let hold = res.find(h => h.id == id);
                if (hold) {
                    if (cart.length > 0) {
                        safeAlert('warning', 'Keranjang Terisi', 'Kosongkan keranjang dulu sebelum mengambil antrian!');
                        return;
                    }

                    cart = typeof hold.cart_data === 'string' ? JSON.parse(hold.cart_data) : hold.cart_data;
                    renderCart();

                    $.ajax({
                        url: "{{ url('/admin/kasir/hold') }}/" + id,
                        method: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            refreshHoldList();
                        }
                    });
                }
            });
        });

        // Hapus Data Tahan Transaksi (Tombol Sampah di List Hold)
        $(document).on('click', '.btn-delete-hold', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Transaksi?',
                text: "Data transaksi yang ditahan akan dihapus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/admin/kasir/hold') }}/" + id,
                        method: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            showToast("Data ditahan dihapus");
                            refreshHoldList();
                        }
                    });
                }
            });
        });

        // --- FITUR SELESAIKAN TRANSAKSI (CHECKOUT) ---
        $('#btnCheckout').off('click').on('click', function() {
            if (cart.length === 0 || $(this).hasClass('disabled')) return;

            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: "Pastikan uang yang diterima sudah benar!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('kasir.store') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            items: cart,
                            total_price: cart.reduce((a, b) => a + (b.price * b.qty), 0),
                            total_discount: parseInt($('#input-diskon').val()) || 0,
                            final_price: $('#totalText').data('val') || 0,
                            cash_received: parseInt($('#input-bayar').val()) || 0,
                            cash_change: (parseInt($('#input-bayar').val()) || 0) - ($('#totalText').data('val') || 0)
                        },
                        success: function(res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Transaksi Berhasil',
                                text: 'Cetak Struk?',
                                showCancelButton: true,
                                confirmButtonText: 'Cetak',
                                cancelButtonText: 'Nanti saja'
                            }).then((printResult) => {
                                if (printResult.isConfirmed && res.print_url) {
                                    window.open(res.print_url, '_blank', 'width=400,height=600');
                                }
                                location.reload();
                            });
                        },
                        error: function(err) {
                            let msg = err.responseJSON && err.responseJSON.message ? err.responseJSON.message : 'Gagal menyimpan transaksi.';
                            Swal.fire('Gagal', msg, 'error');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush