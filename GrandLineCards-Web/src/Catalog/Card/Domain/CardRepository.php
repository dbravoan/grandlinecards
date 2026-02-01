<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Domain;

use GrandLineCards\Shared\Domain\Criteria\Criteria;

interface CardRepository
{
    public function save(Card $card): void;
    public function find(CardId $id): ?Card;
    public function searchByCriteria(Criteria $criteria): array;
    public function countByCriteria(Criteria $criteria): int;
}
