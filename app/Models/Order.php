<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'shipping_address_id', 'billing_address_id', 'shipping_method',
        'payment_method', 'total', 'total_quantity', 'order_note', 'status','payment_proof','shipping_cost','shipping_cost','email'
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
    public function tracking()
{
    return $this->hasMany(OrderTracking::class);
}
public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    public function getHashedIdAttribute()
    {
        $secret = env('APP_KEY', 'yourfallbacksecret');
        return base64_encode($this->id . ':' . hash_hmac('sha256', $this->id, $secret));
    }
}
