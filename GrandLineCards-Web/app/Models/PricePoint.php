<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricePoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'source',
        'price',
        'currency',
        'url',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
