<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Search in Translation (Name/Effect) or Main Card ID
        $cards = Card::query()
            ->with(['translations' => function($q) use ($query) {
                $q->where('locale', 'es')
                  ->orWhere('locale', 'en'); 
            }])
            ->where('card_id', 'LIKE', "%{$query}%")
            ->orWhereHas('translations', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('effect_text', 'LIKE', "%{$query}%");
            })
            ->limit(20)
            ->get();
            
        // Map to simple JSON format for the App
        $results = $cards->map(function ($card) {
            $es = $card->translation('es')->first();
            $en = $card->translation('en')->first();
            
            return [
                'id' => $card->card_id,
                'name' => $es?->name ?? $en?->name ?? $card->card_id,
                'image' => $card->image_url ? url($card->image_url) : null,
                'rarity' => $card->rarity,
                'color' => $card->color,
                'type' => $card->type,
                'power' => $card->power,
            ];
        });

        return response()->json($results);
    }
}
