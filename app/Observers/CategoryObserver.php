<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
    public function created(ProductCategory $product) {
        Cache::forget('product' . $product->id);
    }

    public function updated(ProductCategory $product) {
        Cache::forget('product' . $product->id);
    }
}
