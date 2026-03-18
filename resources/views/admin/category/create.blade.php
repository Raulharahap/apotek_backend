@extends('admin.layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="page-heading">
    <div class="mb-4">
        <a href="{{ url('/admin/category') }}" class="text-muted text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Kategori
        </a>
        <h3 class="mt-2 product-title">Tambah Kategori Baru</h3>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-form p-4">
                <form action="{{ url('/admin/category/store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label text-primary">Nama Kategori *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Contoh: Obat Sirup">
                        @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ url('/admin/category') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection