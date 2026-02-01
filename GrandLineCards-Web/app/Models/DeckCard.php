<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeckCard extends Model
{
    protected $fillable = ['deck_id', 'card_id', 'quantity'];
}
