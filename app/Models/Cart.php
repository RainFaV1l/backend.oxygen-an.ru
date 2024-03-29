<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status_id',
        'total',
        'fio',
        'tel',
        'email',
        'height',
        'city',
        'promotional_code',
    ];

    protected $hidden = [
        'updated_at'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class, 'user_id', 'id');

    }

    /**
     * @return BelongsTo
     */
    public function status(): BelongsTo
    {

        return $this->belongsTo(CartStatus::class, 'status_id', 'id');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {

        return $this->hasMany(Order::class, 'cart_id', 'id');

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {

        return $this->belongsToMany(Product::class, 'orders', 'cart_id', 'product_id');

    }
}
