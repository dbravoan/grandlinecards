<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'user_id', 'leader_id', 'is_public'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Naively linking to Card model via pivot
    // In DDD strictness we might manage this differently, but good for scaffolding
    public function cards()
    {
        return $this->belongsToMany(Card::class, 'deck_cards', 'deck_id', 'card_id', 'id', 'card_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
