<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
       protected $fillable = ['name','category_id'];
         use HasFactory;

   public function blogs()
{
    return $this->belongsToMany(Blog::class, 'blog_tag', 'tag_id', 'blog_id')
                ->withTimestamps();
}

// app/Models/Tag.php
public function products()
{
    return $this->belongsToMany(Product::class);
}
public function category()
{
    return $this->belongsTo(Category::class);
}

}
