<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    
    /**
     * Get the name in the current locale.
     */
    public function getNameAttribute($value)
    {
        // Decode if it's a string, or use as is if array (due to cast)
        // Actually cast handles it. 
        // We can add a helper to get specific locale
        $locale = app()->getLocale();
        return $this->name[$locale] ?? $this->name['es'] ?? '';
    }
}
