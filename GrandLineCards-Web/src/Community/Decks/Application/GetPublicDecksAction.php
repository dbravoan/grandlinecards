<?php

namespace Src\Community\Decks\Application;

use Src\Community\Decks\Domain\DeckRepositoryInterface;

class GetPublicDecksAction
{
    public function __construct(
        private readonly DeckRepositoryInterface $repository
    ) {}

    public function execute(int $limit = 10): array
    {
        // Here we could add logic like caching or filtering banned decks
        return $this->repository->findPublicLatest($limit);
    }
}
