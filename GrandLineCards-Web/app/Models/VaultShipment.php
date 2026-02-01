<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaultShipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tracking_number',
        'carrier',
        'status',
        'admin_notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Seller
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
