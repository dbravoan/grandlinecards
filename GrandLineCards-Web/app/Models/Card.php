<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Card extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * @property int $id
     * @property string $card_id
     * @property string $image_url
     * @property string|null $imageUrl
     * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CardTranslation[] $translations
     */

    public function getImageUrlAttribute()
    {
        return $this->attributes['image_url'] ?? null;
    }

    protected $casts = [
        'attributes' => 'array',
    ];

    public function expansion(): BelongsTo
    {
        return $this->belongsTo(Expansion::class);
    }

    public function translation(string $locale = 'es'): HasOne
    {
        return $this->hasOne(CardTranslation::class)->where('locale', $locale);
    }

    public function translations()
    {
        return $this->hasMany(CardTranslation::class);
    }

    public function prices()
    {
        return $this->hasMany(CardPrice::class);
    }
}
