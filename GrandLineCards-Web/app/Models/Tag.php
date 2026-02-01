<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
    ];

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    // Future Event relation
    // public function events(): MorphToMany
    // {
    //     return $this->morphedByMany(Event::class, 'taggable');
    // }
    
     /**
     * Get the name in the current locale.
     */
    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? $this->name['es'] ?? '';
    }
}
