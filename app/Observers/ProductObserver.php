<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    public function created(Product $product) {
        Cache::forget('product' . $product->id);
    }

    public function updated(Product $product) {
        Cache::forget('product' . $product->id);
    }
}
