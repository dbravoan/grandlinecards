<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'line_1',
        'line_2',
        'city',
        'postal_code',
        'province',
        'country',
        'phone',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Helper to format as string
    public function toString(): string
    {
        return "{$this->full_name}\n{$this->line_1}" . 
               ($this->line_2 ? "\n{$this->line_2}" : "") . 
               "\n{$this->postal_code} {$this->city}, {$this->country}\n{$this->phone}";
    }
}
