<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['name', 'category_id','sub_category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
  public function subcategory()
{
    return $this->belongsTo(SubCategory::class, 'sub_category_id');
}

  

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function getUrlNameAttribute()
    {
        return Str::slug($this->name);
    }
       public function blogs()
{
    return $this->belongsToMany(Blog::class, 'blog_tag', 'tag_id', 'blog_id')
                ->withTimestamps();
}
}