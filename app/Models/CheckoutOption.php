<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutOption extends Model
{
    protected $fillable = [
        'type',           // 'shipping', 'payment', 'billing'
        'key',            // e.g. 'cod', 'express'
        'label',          // Display label
        'shipping_cost',  // only for shipping
        'message',        // extra instructions
        'account_name',   // for payment method
        'account_number', // for payment method
        'status',         // 1 = active
        'bank_name',         // 1 = active
    ];
}
