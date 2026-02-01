<?php

namespace Src\Community\Decks\Domain\Services;

use App\Models\Card;

class DeckValidationService
{
    /**
     * @param string $leaderId
     * @param array<string, int> $mainDeckCards  ['OP01-001' => 4, 'OP01-002' => 2]
     * @throws \Exception
     */
    public function validate(string $leaderId, array $mainDeckCards): void
    {
        // 1. Validate Deck Size (Must be exactly 50 cards)
        $totalCount = array_sum($mainDeckCards);
        if ($totalCount !== 50) {
            throw new \Exception("Main deck must contain exactly 50 cards. Current count: {$totalCount}");
        }

        // 2. Max 4 copies rule
        foreach ($mainDeckCards as $cardId => $quantity) {
            if ($quantity > 4) {
                // Determine restrictions? Some cards might be restricted to 1, but rule of thumb is 4.
                throw new \Exception("You cannot have more than 4 copies of card {$cardId}.");
            }
        }

        // 3. Color Rule
        $leader = Card::where('card_id', $leaderId)->firstOrFail();
        // Leader colors: "Red", "Red/Green"
        $leaderColors = explode('/', $leader->color);

        $cardIds = array_keys($mainDeckCards);
        $cards = Card::whereIn('card_id', $cardIds)->get()->keyBy('card_id');

        foreach ($mainDeckCards as $cardId => $qty) {
            if (!isset($cards[$cardId])) {
                throw new \Exception("Card {$cardId} not found in database.");
            }
            
            $card = $cards[$cardId];
            $cardColors = explode('/', $card->color);
            
            // Check intersection logic: At least one color must match the Leader?
            // One Piece Rules: Main deck cards must have a color included in the Leader's colors.
            $matches = array_intersect($cardColors, $leaderColors);
            
            if (empty($matches)) {
                 throw new \Exception("Card {$card->name} ({$cardId}) color [{$card->color}] does not match Leader [{$leader->color}].");
            }
        }
    }
}
