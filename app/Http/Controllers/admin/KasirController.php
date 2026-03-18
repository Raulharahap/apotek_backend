<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\HoldSale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    /**
     * Tampilkan Halaman Utama Kasir
     */
    public function index()
    {
        $categories = Category::all();
        // Ambil semua produk tanpa peduli stoknya berapa:
        $products = Product::all();

        return view('admin.kasir.index', compact('categories', 'products'));
    }

    /**
     * Simpan Transaksi Permanen (Checkout)
     */
    public function store(Request $request)
    {
        // Validasi input
        if (!$request->items || count($request->items) == 0) {
            return response()->json(['status' => 'error', 'message' => 'Keranjang masih kosong!'], 400);
        }

        try {
            DB::beginTransaction();

            // 1. Cek stok kembali untuk memastikan tidak ada bentrokan saat transaksi bersamaan
            foreach ($request->items as $item) {
                $p = Product::find($item['id']);
                if (!$p || $p->stock < $item['qty']) {
                    throw new \Exception("Stok produk {$item['name']} tidak mencukupi!");
                }
            }

            // 2. Generate Nomor Invoice (Contoh: INV-20231025-0001)
            $invoice = 'INV-' . date('Ymd') . '-' . str_pad(Sale::whereDate('created_at', date('Y-m-d'))->count() + 1, 4, '0', STR_PAD_LEFT);

            // 3. Simpan ke Tabel Sales
            // Menggunakan Auth::guard('admin')->id() agar ID yang masuk benar-benar admin yang login
            $sale = Sale::create([
                'invoice_number' => $invoice,
                'total_price'    => $request->total_price,
                'total_discount' => $request->total_discount ?? 0,
                'final_price'    => $request->final_price,
                'cash_received'  => $request->cash_received,
                'cash_change'    => $request->cash_change,
                'user_id'        => Auth::guard('admin')->id(),
            ]);

            // 4. Simpan Detail Belanja & Potong Stok
            foreach ($request->items as $item) {
                SaleDetail::create([
                    'sale_id'      => $sale->id,
                    'product_id'   => $item['id'],
                    'product_name' => $item['name'],
                    'price'        => $item['price'],
                    'qty'          => $item['qty'],
                    'subtotal'     => $item['price'] * $item['qty'],
                ]);

                // Pengurangan stok permanen
                Product::where('id', $item['id'])->decrement('stock', $item['qty']);
            }

            DB::commit();

            // Kirim balik data URL print untuk dipanggil di frontend (SweetAlert)
            return response()->json([
                'status'    => 'success',
                'message'   => 'Transaksi Berhasil!',
                'sale_id'   => $sale->id,
                'print_url' => route('kasir.print', $sale->id)
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fitur Tahan Transaksi (Hold)
     */
    public function hold(Request $request)
    {
        HoldSale::create([
            'label'       => $request->label ?? 'Antrian #' . (HoldSale::count() + 1),
            'cart_data'   => $request->cart_data, // Disimpan sebagai JSON (otomatis via Model cast)
            'total_price' => $request->total_price,
            'user_id'     => Auth::guard('admin')->id()
        ]);

        return response()->json(['status' => 'success', 'message' => 'Transaksi Berhasil Ditahan!']);
    }

    /**
     * Ambil Daftar Transaksi yang Sedang Ditahan (Hanya milik kasir login)
     */
    public function getHoldList()
    {
        $holds = HoldSale::where('user_id', Auth::guard('admin')->id())
            ->latest()
            ->get();
        return response()->json($holds);
    }

    /**
     * Hapus Data Hold
     */
    public function destroyHold($id)
    {
        HoldSale::where('id', $id)
            ->where('user_id', Auth::guard('admin')->id())
            ->delete();

        return response()->json(['status' => 'success', 'message' => 'Data antrian berhasil dihapus']);
    }

    /**
     * Fungsi Cetak Struk (Persiapan untuk Tampilan Struk)
     */
    public function printStruk($id)
    {
        // Pastikan 'user' ada di dalam with()
        $sale = Sale::with(['details', 'user'])->findOrFail($id);
        return view('admin.kasir.print', compact('sale'));
    }

    public function history(Request $request)
    {
        $query = Sale::with(['user', 'details.product'])->latest();

        // Filter Nomor Invoice
        if ($request->filled('invoice')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice . '%');
        }

        // Filter Rentang Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Menggunakan paginate 20 data per halaman
        // appends(request()->all()) penting agar filter tidak hilang saat klik tombol "Next"
        $sales = $query->paginate(20)->appends(request()->all());

        return view('admin.kasir.history', compact('sales'));
    }
}
