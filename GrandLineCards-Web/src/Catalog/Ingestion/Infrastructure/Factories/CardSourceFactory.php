<?php

namespace Src\Catalog\Ingestion\Infrastructure\Factories;

use InvalidArgumentException;
use Src\Catalog\Ingestion\Domain\Contracts\CardSourceInterface;
use Src\Catalog\Ingestion\Infrastructure\Sources\OfficialSiteScraper;
use Src\Catalog\Ingestion\Infrastructure\Sources\JsonFileSource;

class CardSourceFactory
{
    public function make(string $sourceName): CardSourceInterface
    {
        return match ($sourceName) {
            'official' => app(OfficialSiteScraper::class),
            'json' => app(JsonFileSource::class),
            default => throw new InvalidArgumentException("Unknown card source driver: [{$sourceName}]"),
        };
    }
}
