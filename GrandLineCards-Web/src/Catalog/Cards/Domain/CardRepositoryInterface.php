<?php

namespace Src\Catalog\Cards\Domain;

use Src\Shared\Domain\ValueObjects\CardId;

interface CardRepositoryInterface
{
    public function find(CardId $id): ?Card;
    
    /**
     * @return Card[]
     */
    public function search(array $filters): array;
    
    public function save(Card $card): void;
}
