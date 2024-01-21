<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class ProductsObserver
{
    public function created() {
        Cache::forget('products');
    }

    public function updated() {
        Cache::forget('products');
    }
}
