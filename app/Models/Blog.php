<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
      use HasFactory;
      protected $fillable = ['name', 'slug', 'description', 'tags', 'image'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
   public function tags()
{
    return $this->belongsToMany(Tag::class, 'blog_tag', 'blog_id', 'tag_id')
                ->withTimestamps();
}


}
