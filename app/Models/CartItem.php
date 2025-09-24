<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price', 'selected_options','selected_color'];

    protected $casts = [
        'selected_options' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
      public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
