<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'type', 'name', 'phone', 'address', 'city', 'state', 'zip', 'country'
    ];
}
