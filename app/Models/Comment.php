<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
     use HasFactory;

     protected $fillable = ['blog_id', 'parent_id', 'author_name', 'comment'];

    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
      // Relationship to get replies of a comment
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }


public function repliesRecursive()
{
    return $this->replies()->with('repliesRecursive');
}
}
