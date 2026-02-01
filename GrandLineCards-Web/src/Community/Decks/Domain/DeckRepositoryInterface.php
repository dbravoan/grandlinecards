<?php

namespace Src\Community\Decks\Domain;

interface DeckRepositoryInterface
{
    public function save(Deck $deck): void;
    public function find(string $id): ?Deck;
    public function findByUser(int $userId): array;
    /** @return Deck[] */
    public function findPublicLatest(int $limit = 10): array;
}
