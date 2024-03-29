<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function carts() {
        return $this->belongsTo(Cart::class, 'status_id', 'id');
    }
}
