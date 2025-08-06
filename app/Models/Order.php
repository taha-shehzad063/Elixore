<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'shipping_address_id', 'billing_address_id', 'shipping_method',
        'payment_method', 'total', 'total_quantity', 'order_note', 'status','payment_proof','shipping_cost'
    ];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress() {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress() {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }
}
