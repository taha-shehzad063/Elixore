<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
     use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'discount_price',
        'image',
        'slug',
        'category_id', 
        'sub_category_id',
         'info', 
         'description', 
         'availability',
            'material', // New column added
            'tags', // Assuming this is a JSON column or a pivot table
    ];
     public function category()
    {
        return $this->belongsTo(Category::class);
    }
  public function options()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
    public function galleries()
{
    return $this->hasMany(ProductGallery::class);
}
public function specifications()
{
    return $this->hasMany(ProductSpecification::class);
}
public function reviews()
{
    return $this->hasMany(Review::class);
}
public function tags()
{
    return $this->belongsToMany(Tag::class);
}

}
