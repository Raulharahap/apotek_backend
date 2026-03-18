<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'total_price',
        'total_discount',
        'final_price',
        'cash_received',
        'cash_change',
        'user_id'
    ];

    // TAMBAHKAN INI
    public function user()
    {
        // Menghubungkan user_id di tabel sales dengan id di tabel admins
        return $this->belongsTo(Admin::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
