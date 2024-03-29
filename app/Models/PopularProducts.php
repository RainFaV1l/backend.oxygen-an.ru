<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularProducts extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'popular_product_id',
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function popularProduct() {
        return $this->belongsTo(Product::class, 'popular_product_id', 'id');
    }
}
