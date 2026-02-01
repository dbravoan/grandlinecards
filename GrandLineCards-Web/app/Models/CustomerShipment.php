<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracking_number',
        'carrier',
        'shipping_address',
        'shipping_cost',
        'status',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'shipping_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Buyer
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
