<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardPrice extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_foil' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
