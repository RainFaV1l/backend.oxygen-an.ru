<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'image_path',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function product() {

        return $this->belongsTo(Product::class, 'product_id', 'id');

    }
}
