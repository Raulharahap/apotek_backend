<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoldSale extends Model
{
    // 1. Daftarkan kolom agar bisa disimpan
    protected $fillable = ['label', 'cart_data', 'total_price', 'user_id'];

    // 2. WAJIB: Ubah array JavaScript menjadi JSON otomatis saat simpan ke DB
    protected $casts = [
        'cart_data' => 'array',
        'total_price' => 'decimal:2'
    ];
}
