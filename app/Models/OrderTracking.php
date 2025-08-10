<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTracking extends Model
{
    use HasFactory;
     protected $table = 'order_tracking';
    protected $fillable = [
        'order_id',
        'status',
        'description',
        'location',
        'estimated_delivery',
        'tracking_number',
        'carrier'
    ];

    protected $dates = ['estimated_delivery'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}