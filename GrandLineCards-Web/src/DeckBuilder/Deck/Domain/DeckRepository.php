<?php
declare(strict_types=1);
namespace GrandLineCards\DeckBuilder\Deck\Domain;

interface DeckRepository
{
    public function save(Deck $deck): void;
    public function find(DeckId $id): ?Deck;
    // public function findPublicLatest(): array; // Removed for now, focus on core
}
