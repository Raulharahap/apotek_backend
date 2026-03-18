@extends('admin.layouts.app')

@section('title', 'Edit Obat')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<style>
    .card-form {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .form-label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #4b5563;
    }

    .image-preview-wrapper {
        border: 2px dashed #d1d5db;
        border-radius: 15px;
        height: 300px;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        background-color: #f9fafb;
        color: #9ca3af;
        cursor: default;
        padding: 20px;
        pointer-events: none;
    }

    .image-preview-wrapper i,
    .image-preview-wrapper span,
    .image-preview-wrapper small {
        display: block !important;
        width: 100%;
        text-align: center;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .form-control,
    .form-select {
        border-radius: 10px;
        padding: 10px 15px;
        border: 1px solid #e5e7eb;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .text-danger {
        font-size: 0.75rem;
        margin-top: 4px;
        display: block;
        font-weight: 500;
    }

    /* Mode Gelap Support */
    [data-bs-theme="dark"] .card-form {
        background-color: #1e1e2d;
    }

    [data-bs-theme="dark"] .image-preview-wrapper {
        background-color: #151521;
        border-color: #2d2d3e;
    }

    /* TomSelect Custom Styling */
    .ts-control {
        border-radius: 10px !important;
        padding: 10px 15px !important;
        border: 1px solid #e5e7eb !important;
        box-shadow: none !important;
    }
</style>
@endpush

@section('content')
<div class="page-heading">
    <div class="mb-4">
        <a href="{{ url('/admin/product') }}" class="text-muted text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Produk
        </a>
        <h3 class="mt-2 product-title"><i class="bi bi-pencil-square text-primary me-2"></i> Edit Obat: {{ $product->name }}</h3>
    </div>

    <form action="{{ url('/admin/product/update/'.$product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-4">
                <div class="card card-form p-4 mb-4">
                    <div class="section-title"><i class="bi bi-image text-primary"></i> Gambar Obat</div>
                    <div class="image-preview-wrapper mb-3" id="image-preview">
                        @if($product->image)
                        <img src="{{ asset('uploads/products/'.$product->image) }}" style="max-height: 100%; max-width: 100%; border-radius: 12px; object-fit: contain;">
                        @else
                        <i class="bi bi-image-fill mb-2" style="font-size: 4rem; opacity: 0.5;"></i>
                        <div class="fw-bold text-dark">Pratinjau Gambar</div>
                        <small class="text-muted">Pilih foto melalui tombol di bawah</small>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ganti Gambar (Opsional)</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" id="image-input" accept="image/*">
                        @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card card-form p-4 mb-4">
                    <div class="section-title"><i class="bi bi-info-circle text-primary"></i> Informasi Dasar</div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-primary">Kategori Obat *</label>
                            <select name="category_id" id="category-select" placeholder="Cari atau pilih kategori...">
                                <option value="">Cari atau pilih kategori...</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode / Barcode (Opsional)</label>
                            <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" placeholder="Contoh: OBT-001">
                            @error('sku') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-primary">Nama Obat *</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" placeholder="Masukkan nama obat">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Deskripsi / Komposisi (Opsional)</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card card-form p-4 mb-4">
                    <div class="section-title"><i class="bi bi-currency-dollar text-primary"></i> Harga & Stok</div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Harga Beli</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price', (int)$product->purchase_price) }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-primary">Harga Jual *</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', (int)$product->selling_price) }}">
                            </div>
                            @error('selling_price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-primary">Stok *</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}">
                            @error('stock') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Satuan</label>
                            <select class="form-select" name="unit">
                                @foreach(['Tablet', 'Botol', 'Strip', 'Sachet'] as $u)
                                <option value="{{ $u }}" {{ old('unit', $product->unit) == $u ? 'selected' : '' }}>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Kedaluwarsa</label>
                            <input type="date" name="expired_date" class="form-control" value="{{ old('expired_date', $product->expired_date) }}">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ url('/admin/product') }}" class="btn btn-light px-4 rounded-pill">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">
                        <i class="bi bi-check-circle me-2"></i> Perbarui Data
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    // 1. Logic TomSelect (Pencarian Kategori)
    new TomSelect("#category-select", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });

    // 2. Logic Preview Gambar
    const imgInput = document.getElementById('image-input');
    const imgPreview = document.getElementById('image-preview');

    imgInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.innerHTML = `<img src="${e.target.result}" style="max-height: 100%; max-width: 100%; border-radius: 12px; object-fit: contain;">`;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush