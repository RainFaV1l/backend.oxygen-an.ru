<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class CategoriesObserver
{
    public function created() {
        Cache::forget('categories');
    }

    public function updated() {
        Cache::forget('categories');
    }
}
