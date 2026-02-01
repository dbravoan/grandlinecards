<?php
declare(strict_types=1);
namespace GrandLineCards\DeckBuilder\Deck\Domain;

final class DeckCard
{
    public function __construct(
        private CardId $cardId,
        private CardQuantity $quantity
    ) {}

    public function cardId(): CardId { return $this->cardId; }
    public function quantity(): CardQuantity { return $this->quantity; }
}
