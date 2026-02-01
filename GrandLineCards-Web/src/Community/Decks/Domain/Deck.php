<?php

namespace Src\Community\Decks\Domain;

use Src\Shared\Domain\AggregateRoot;
use Src\Shared\Domain\ValueObjects\CardId;

class Deck extends AggregateRoot
{
    public function __construct(
        private readonly ?string $id,
        private string $name,
        private readonly int $userId,
        private readonly CardId $leaderId,
        private array $cards = [], // Array of ['cardId' => CardId, 'quantity' => int]
        private bool $isPublic = false
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'user_id' => $this->userId,
            'leader_id' => $this->leaderId->value(),
            'cards' => array_map(fn($item) => [
                'card_id' => $item['cardId']->value(),
                'quantity' => $item['quantity']
            ], $this->cards),
            'is_public' => $this->isPublic,
        ];
    }
    
    // Domain rules for deck building would go here (e.g., max 4 copies)
}
