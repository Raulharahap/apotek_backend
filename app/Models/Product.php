<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Kasih tahu Laravel kolom mana saja yang boleh diisi
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'purchase_price',
        'selling_price',
        'stock',
        'unit',
        'expired_date',
        'image'
    ];
}
