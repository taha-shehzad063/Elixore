<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function getUrlNameAttribute()
    {
        return Str::slug($this->name);
    }
    
}