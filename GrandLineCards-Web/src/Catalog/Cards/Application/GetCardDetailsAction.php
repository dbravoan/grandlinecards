<?php

namespace Src\Catalog\Cards\Application;

use Src\Catalog\Cards\Domain\CardRepositoryInterface;
use Src\Shared\Domain\ValueObjects\CardId;

class GetCardDetailsAction
{
    public function __construct(
        private readonly CardRepositoryInterface $repository
    ) {}

    public function execute(string $cardId): ?array
    {
        $id = new CardId($cardId);
        $card = $this->repository->find($id);

        if (!$card) {
            return null;
        }

        // transforming domain entity to basic array/DTO for the view
        return $card->toArray(); 
    }
}
