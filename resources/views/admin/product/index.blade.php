@extends('admin.layouts.app')

@section('title', 'Produk')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="product-title">Produk</h3>
            <p class="text-muted">{{ $products->count() }} produk terdaftar</p>
        </div>
        <a href="{{ url('/admin/product/create') }}" class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center justify-content-center">
            <i class="bi bi-plus-circle me-2 d-flex"></i>
            <span>Tambah Produk</span>
        </a>
    </div>

    <section class="section">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-group position-relative has-icon-left">
                    <input type="text" id="searchInput" class="form-control rounded-pill" placeholder="Cari produk...">
                    <div class="form-control-icon">
                        <i class="bi bi-search"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-8 d-flex justify-content-end gap-2">
                <button id="btn-grid" class="btn btn-view-toggle active">
                    <i class="bi bi-grid-fill"></i>
                </button>
                <button id="btn-list" class="btn btn-view-toggle">
                    <i class="bi bi-list-ul"></i>
                </button>
            </div>
        </div>

        <div id="product-grid-wrapper" class="row">
            @forelse ($products as $product)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 product-item" data-name="{{ strtolower($product->name) }}" data-sku="{{ strtolower($product->sku) }}">
                <div class="card product-card h-100 shadow-sm border-0">
                    <div class="card-img-container">
                        <span class="badge bg-dark position-absolute top-0 end-0 m-2" style="z-index: 5;">Stok: {{ $product->stock }}</span>
                        <img src="{{ $product->image ? asset('uploads/products/'.$product->image) : 'https://via.placeholder.com/300x200' }}"
                            class="card-img-top" alt="{{ $product->name }}">

                        <div class="product-action">
                            <a href="{{ url('/admin/product/edit/'.$product->id) }}" class="btn-action-custom edit" title="Edit">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </a>
                            <button type="button" class="btn-action-custom delete btn-delete" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                <i class="bi bi-trash fs-5"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body px-3 py-3">
                        <span class="badge bg-light-primary text-primary mb-2" style="font-size: 0.7rem;">{{ $product->unit }}</span>
                        <h6 class="product-title mb-1 text-truncate" title="{{ $product->name }}">{{ $product->name }}</h6>
                        <small class="text-muted d-block mb-3">{{ $product->sku }}</small>
                        <div class="d-flex justify-content-between align-items-center">
                            <div><small class="text-muted d-block">Harga Beli</small><span class="fw-bold">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</span></div>
                            <div class="text-end"><small class="text-muted d-block">Harga Jual</small><span class="fw-bold text-price fs-5 text-primary">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada produk.</p>
            </div>
            @endforelse
        </div>

        <div id="product-list-wrapper" class="card border-0 shadow-sm" style="display: none;">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0">Data Produk</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-product mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" width="50">No</th>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @foreach ($products as $index => $product)
                            <tr class="product-row" data-name="{{ strtolower($product->name) }}" data-sku="{{ strtolower($product->sku) }}">
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $product->image ? asset('uploads/products/'.$product->image) : 'https://via.placeholder.com/50?text=NA' }}"
                                            class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                                        <div>
                                            <h6 class="product-title mb-0" style="font-size: 0.9rem;">{{ $product->name }}</h6>
                                            <small class="text-muted">{{ $product->sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light-primary text-primary">{{ $product->unit }}</span></td>
                                <td>Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                <td class="fw-bold text-primary">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-light-success text-success px-3">{{ $product->stock }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ url('/admin/product/edit/'.$product->id) }}" class="btn btn-light-warning btn-sm">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <button type="button" class="btn btn-light-danger btn-sm btn-delete" data-id="{{ $product->id }}" data-name="{{ $product->name }}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Fix untuk Decorators Error: Simpan session ke variabel JS murni
        const successMessage = "{{ session('success') }}";

        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
            });
        }

        // Toggle View Logic
        const btnGrid = $('#btn-grid');
        const btnList = $('#btn-list');
        const gridWrapper = $('#product-grid-wrapper');
        const listWrapper = $('#product-list-wrapper');

        btnGrid.on('click', function() {
            btnGrid.addClass('active');
            btnList.removeClass('active');
            gridWrapper.show().addClass('d-flex');
            listWrapper.hide();
        });

        btnList.on('click', function() {
            btnList.addClass('active');
            btnGrid.removeClass('active');
            gridWrapper.hide().removeClass('d-flex');
            listWrapper.show();
        });

        // Delete Logic
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Hapus Obat?',
                text: `Kamu akan menghapus ${name}. Data tidak bisa dikembalikan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ url('/admin/product/delete') }}/${id}`,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Terhapus!', response.message, 'success')
                                .then(() => {
                                    location.reload();
                                });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush