<?php

namespace Src\Catalog\Cards\Application;

use Src\Catalog\Cards\Domain\CardRepositoryInterface;

class SearchCardsAction
{
    public function __construct(
        private readonly CardRepositoryInterface $repository
    ) {}

    public function execute(array $filters): array
    {
        return $this->repository->search($filters);
    }
}
