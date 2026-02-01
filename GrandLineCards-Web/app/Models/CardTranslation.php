<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardTranslation extends Model
{
    protected $guarded = [];
    
    protected $casts = [
        'keywords' => 'array',
    ];
}
