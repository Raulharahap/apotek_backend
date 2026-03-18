<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    protected $fillable = ['sale_id', 'product_id', 'product_name', 'price', 'qty', 'subtotal'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    // TAMBAHKAN RELASI INI
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
