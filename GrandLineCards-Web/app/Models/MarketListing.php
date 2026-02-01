<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_id',
        'price',
        'condition',
        'description',
        'quantity',
        'is_auction',
        'auction_end_at',
        'status',
    ];

    protected $casts = [
        'is_auction' => 'boolean',
        'auction_end_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function highestBid()
    {
        return $this->hasOne(Bid::class)->latest('amount');
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
