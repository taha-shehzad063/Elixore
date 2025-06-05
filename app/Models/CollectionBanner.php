<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionBanner extends Model
{
    protected $table = 'collection_banner';

    protected $fillable = [
        'title',
        'heading',
        'button_url',
        'button_text',
        'sale_text',
        'image',
    ];
}
