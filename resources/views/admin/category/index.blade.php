@extends('admin.layouts.app')

@section('title', 'Kategori')

@section('content')
<style>
    /* ======================================================== */
    /* GENERAL STYLES                                           */
    /* ======================================================== */
    .product-title {
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 0;
    }

    .card-custom {
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header-custom {
        background-color: #ffffff;
        border-bottom: 1px solid #f2f2f2;
    }

    .table-custom {
        border-collapse: collapse;
        border-spacing: 0;
    }

    .table-custom th {
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        padding: 1.2rem 1rem;
        border-bottom: 1px solid #f2f2f2;
        border-top: none;
    }

    .table-custom td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f2f2f2;
    }

    .table-hover tbody tr {
        transition: 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Kesejajaran Tombol Tambah Kategori */
    .btn-add {
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-add i {
        line-height: 0;
        font-size: 1.1rem;
    }

    .btn-add span {
        margin-top: 2px;
        /* optical centering */
    }

    /* Tombol Aksi di Tabel */
    .btn-action {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        border-radius: 8px;
        border: none;
        transition: 0.2s;
    }

    .btn-action i {
        font-size: 1rem;
        line-height: 0;
    }

    .btn-light-warning {
        background-color: #fffbeb;
        color: #f59e0b;
    }

    .btn-light-warning:hover {
        background-color: #fef3c7;
        color: #d97706;
    }

    .btn-light-danger {
        background-color: #fef2f2;
        color: #ef4444;
    }

    .btn-light-danger:hover {
        background-color: #fee2e2;
        color: #dc2626;
    }

    /* ======================================================== */
    /* FULL SUPPORT DARK MODE                                   */
    /* ======================================================== */
    [data-bs-theme="dark"] .product-title {
        color: #f8fafc;
    }

    [data-bs-theme="dark"] .text-muted {
        color: #94a3b8 !important;
    }

    [data-bs-theme="dark"] .card {
        background-color: #1e1e2d !important;
    }

    [data-bs-theme="dark"] .card-header-custom {
        background-color: #1e1e2d !important;
        border-bottom-color: #2d2d3e !important;
    }

    [data-bs-theme="dark"] .card-header-custom h6 {
        color: #e2e8f0 !important;
    }

    [data-bs-theme="dark"] .table-custom th {
        color: #94a3b8;
        border-bottom-color: #2d2d3e;
    }

    [data-bs-theme="dark"] .table-custom td {
        border-bottom-color: #2d2d3e;
        color: #e2e8f0;
    }

    [data-bs-theme="dark"] .fw-bold {
        color: #f8fafc !important;
    }

    [data-bs-theme="dark"] .table-hover tbody tr:hover {
        background-color: #252536 !important;
    }

    /* Button Action Colors di Dark Mode */
    [data-bs-theme="dark"] .btn-light-warning {
        background-color: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
    }

    [data-bs-theme="dark"] .btn-light-warning:hover {
        background-color: rgba(245, 158, 11, 0.25);
    }

    [data-bs-theme="dark"] .btn-light-danger {
        background-color: rgba(239, 68, 68, 0.15);
        color: #f87171;
    }

    [data-bs-theme="dark"] .btn-light-danger:hover {
        background-color: rgba(239, 68, 68, 0.25);
    }
</style>

<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="product-title">Kategori</h3>
            <p class="text-muted">{{ $categories->count() }} kategori terdaftar</p>
        </div>
        <a href="{{ url('/admin/category/create') }}" class="btn btn-primary rounded-pill px-4 btn-add shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>
            <span>Tambah Kategori</span>
        </a>
    </div>

    <section class="section">
        <div class="card border-0 shadow-sm card-custom">
            <div class="card-header card-header-custom py-3">
                <h6 class="mb-0 fw-bold">Data Kategori</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" width="80">No</th>
                                <th>Nama Kategori</th>
                                <th class="text-center" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $index => $cat)
                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td class="fw-bold">{{ $cat->name }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ url('/admin/category/edit/'.$cat->id) }}" class="btn-action btn-light-warning" title="Edit">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <button type="button" class="btn-action btn-light-danger btn-delete" data-id="{{ $cat->id }}" data-name="{{ $cat->name }}" title="Hapus">
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
        const successMessage = "{{ session('success') }}";
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true
            });
        }

        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            Swal.fire({
                title: 'Hapus Kategori?',
                text: "Kategori " + name + " akan dihapus.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('/admin/category/delete') }}/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire('Terhapus!', response.message, 'success').then(() => {
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