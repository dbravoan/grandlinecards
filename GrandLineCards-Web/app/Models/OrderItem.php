<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'market_listing_id',
        'card_id',
        'quantity',
        'price_per_unit',
        'vault_shipment_id',
        'customer_shipment_id',
        'status',
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function marketListing()
    {
        return $this->belongsTo(MarketListing::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function vaultShipment()
    {
        return $this->belongsTo(VaultShipment::class);
    }

    public function customerShipment()
    {
        return $this->belongsTo(CustomerShipment::class);
    }
}
