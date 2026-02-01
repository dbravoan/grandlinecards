<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var \GrandLineCards\Catalog\Card\Application\Find\CardResponse $card */
        $card = $this->resource;

        return [
            'id' => $card->id,
            'card_id' => $card->id, // View expects card_id (string code)
            'name' => $card->translations[0]['name'] ?? 'Unknown',
            'image_url' => $card->imageUrl 
                ? (str_starts_with($card->imageUrl, 'http') ? $card->imageUrl : '/storage/' . $card->imageUrl)
                : '/images/card-back.png',
            'type' => $card->type,
            'color' => $card->color, 
            'cost' => $card->cost,
            'power' => $card->power,
            'counter' => $card->counter,
            'attribute' => implode('/', $card->attributes),
            'rarity' => $card->rarity,
            'set_name' => $card->expansionId,
            'code' => $card->id,
            
            // Translations
            'translations' => $card->translations,

            // Fallbacks for View if it accesses top level
            'effect_text' => $card->translations[0]['effect_text'] ?? '',
            'trigger_text' => $card->translations[0]['trigger_text'] ?? '',
            
            // Prices (Placeholder)
            'prices' => [], 
        ];
    }
}
