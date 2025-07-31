<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'product_id', 'name', 'email', 'phone', 'message', 'rating'
    ];

    public function replies()
    {
        return $this->hasMany(ReviewReply::class);
    }
    protected $casts = [
    'rating' => 'float',
];

}
