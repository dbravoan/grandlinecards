<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_id',
        'quantity',
        'is_foil',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'is_foil' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
