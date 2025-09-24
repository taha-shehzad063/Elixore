<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id', 'status','session_id','selected_color'];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    
}

