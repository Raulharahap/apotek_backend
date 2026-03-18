<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        // Mengambil data produk terbaru
        $products = Product::latest()->get();

        // Kirim ke view
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = \App\Models\Category::all(); // Ambil semua kategori
        return view('admin.product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|max:255',
            'category_id'   => 'required',
            'selling_price' => 'required|numeric',
            'sku'           => 'nullable|unique:products,sku', // Sekarang boleh kosong
            'image'         => 'nullable|image|max:2048'
        ], [
            'name.required'          => 'Nama obat harus diisi.',
            'category_id.required'   => 'Pilih kategori obat.',
            'selling_price.required' => 'Harga jual harus diisi.',
            'sku.unique'             => 'Kode barcode ini sudah dipakai obat lain.',
        ]);

        $data = $request->all();

        // Default stok jika kosong
        $data['stock'] = $request->stock ?? 0;
        $data['purchase_price'] = $request->purchase_price ?? 0;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = $imageName;
        }

        Product::create($data);
        return redirect('/admin/product')->with('success', 'Obat baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $categories = \App\Models\Category::all(); // Ambil kategori untuk dropdown
        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // VALIDASI: Perhatikan 'nullable' pada SKU
        $request->validate([
            'name'          => 'required|string|max:255',
            'category_id'   => 'required',
            'selling_price' => 'required|numeric',
            'sku'           => 'nullable|unique:products,sku,' . $id, // Ganti ke nullable
            'image'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'name.required'          => 'Nama obat tidak boleh kosong.',
            'category_id.required'   => 'Kategori wajib dipilih.',
            'selling_price.required' => 'Harga jual harus diisi.',
            'sku.unique'             => 'Barcode sudah digunakan obat lain.',
        ]);

        $data = $request->all();

        // Pastikan jika kosong, diisi string kosong atau null (sesuaikan SQL tadi)
        $data['sku'] = $request->sku ?? null;
        $data['purchase_price'] = $request->purchase_price ?? 0;
        $data['stock'] = $request->stock ?? 0;

        if ($request->hasFile('image')) {
            // Hapus foto lama
            $oldPath = public_path('uploads/products/' . $product->image);
            if ($product->image && \Illuminate\Support\Facades\File::exists($oldPath)) {
                \Illuminate\Support\Facades\File::delete($oldPath);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/products'), $imageName);
            $data['image'] = $imageName;
        }

        $product->update($data);
        return redirect('/admin/product')->with('success', 'Data obat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus file gambar dari folder public kalau ada
        $imagePath = public_path('uploads/products/' . $product->image);
        if ($product->image && File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Obat berhasil dihapus!'
        ]);
    }
}
