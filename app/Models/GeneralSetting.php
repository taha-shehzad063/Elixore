<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
     protected $fillable = [
        'heading_0',
    'intro_0',
        'heading',
        'info',
        'heading_1',
        'heading_2',
        'heading_3',
        'intro_3',
        'image', // only if you're uploading an image
    ];

}
