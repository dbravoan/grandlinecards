<?php

namespace App\Services\Meta;

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Support\Facades\DB;

class MetaService
{
    /**
     * Get the most popular leaders based on public deck usage.
     */
    public function getTopLeaders(int $limit = 5): array
    {
        // 1. Aggregate counts by leader_id from public decks
        $leaderStats = Deck::where('is_public', true)
            ->select('leader_id', DB::raw('count(*) as total'))
            ->groupBy('leader_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        if ($leaderStats->isEmpty()) {
            return [];
        }

        // 2. Fetch Card details for these leaders
        $leaderIds = $leaderStats->pluck('leader_id');
        $cards = Card::whereIn('card_id', $leaderIds)
            ->with(['translations']) // Eager load to avoid N+1 and ensure data
            ->get()
            ->keyBy('card_id');

        // 3. Merge data
        $results = [];
        foreach ($leaderStats as $stat) {
            $card = $cards->get($stat->leader_id);
            if ($card) {
                // Safe translation access
                $translation = $card->translations->firstWhere('locale', 'es') ?? $card->translations->first();
                $name = $translation ? $translation->name : 'Unknown';

                $results[] = [
                    'card' => [
                        'id' => $card->card_id,
                        'name' => $name,
                        'image_url' => $card->imageUrl 
                            ? (str_starts_with($card->imageUrl, 'http') ? $card->imageUrl : '/storage/' . $card->imageUrl)
                            : '/images/card-back.png',
                        'color' => $card->color,
                    ],
                    'usage_count' => $stat->total,
                ];
            }
        }

        return $results;
    }

    /**
     * Get trending cards (most included in public decks).
     */
    public function getTrendingCards(int $limit = 10): array
    {
        // 1. Aggregate counts from deck_cards, filtering by public decks
        $cardStats = DB::table('deck_cards')
            ->join('decks', 'deck_cards.deck_id', '=', 'decks.id')
            ->where('decks.is_public', true)
            ->select('deck_cards.card_id', DB::raw('count(*) as frequency'))
            ->groupBy('deck_cards.card_id')
            ->orderByDesc('frequency')
            ->limit($limit)
            ->get();

        if ($cardStats->isEmpty()) {
            return [];
        }

        // 2. Fetch Card details
        $cardIds = $cardStats->pluck('card_id');
        $cards = Card::whereIn('card_id', $cardIds)
            ->with(['translations']) // Eager load
            ->get()
            ->keyBy('card_id');

        // 3. Merge
        $results = [];
        foreach ($cardStats as $stat) {
            $card = $cards->get($stat->card_id);
            if ($card) {
                // Safe translation
                $translation = $card->translations->firstWhere('locale', 'es') ?? $card->translations->first();
                $name = $translation ? $translation->name : 'Unknown';

                $results[] = [
                    'card' => [
                        'id' => $card->card_id,
                        'name' => $name,
                        'image_url' => $card->imageUrl 
                            ? (str_starts_with($card->imageUrl, 'http') ? $card->imageUrl : '/storage/' . $card->imageUrl)
                            : '/images/card-back.png',
                        'color' => $card->color,
                        'rarity' => $card->rarity,
                    ],
                    'frequency' => $stat->frequency,
                ];
            }
        }

        return $results;
    }
}
