<?php

namespace Src\Catalog\Ingestion\Domain\Contracts;

use Illuminate\Support\Collection;

interface CardSourceInterface
{
    /**
     * Fetch all cards for a specific set.
     *
     * @param string $setCode e.g. "OP01"
     * @return Collection<int, \Src\Catalog\Ingestion\Domain\DTOs\RawCardData>
     */
    public function fetchBySet(string $setCode): Collection;
    
    /**
     * Fetch a single card by its ID.
     * 
     * @param string $cardId e.g. "OP01-001"
     */
    public function fetchOne(string $cardId): ?\Src\Catalog\Ingestion\Domain\DTOs\RawCardData;

    /**
     * Fetch available sets from the source.
     * @return array<string, string> map of code => id/value
     */
    public function getAvailableSets(): array;
}
