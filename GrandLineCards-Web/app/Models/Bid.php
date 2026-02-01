<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MarketListing;
use App\Models\User;

class Bid extends Model
{
    protected $fillable = ['market_listing_id', 'user_id', 'amount'];

    public function marketListing()
    {
        return $this->belongsTo(MarketListing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
