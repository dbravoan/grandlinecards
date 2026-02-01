<?php

namespace Src\Catalog\Ingestion\Infrastructure\Sources;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Src\Catalog\Ingestion\Domain\Contracts\CardSourceInterface;
use Src\Catalog\Ingestion\Domain\DTOs\RawCardData;

class JsonFileSource implements CardSourceInterface
{
    private Collection $data;

    public function __construct(string $filePath)
    {
        if (!File::exists($filePath)) {
            throw new \RuntimeException("JSON file not found: $filePath");
        }

        $json = File::get($filePath);
        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("Invalid JSON in file: " . json_last_error_msg());
        }

        $this->data = collect($decoded)->map(function ($item) {
            return new RawCardData(
                id: $item['id'],
                setId: $item['setId'],
                name: $item['name'],
                rarity: $item['rarity'],
                color: $item['color'],
                type: $item['type'],
                cost: $item['cost'],
                power: $item['power'],
                counter: $item['counter'],
                life: $item['life'],
                attributes: $item['attributes'],
                effectText: $item['effectText'],
                triggerText: $item['triggerText'],
                imageUrl: $item['imageUrl']
            );
        });
    }

    public function fetchBySet(string $setCode): Collection
    {
        return $this->data->filter(fn(RawCardData $card) => $card->setId === $setCode);
    }

    public function fetchOne(string $cardId): ?RawCardData
    {
        return $this->data->first(fn(RawCardData $card) => $card->id === $cardId);
    }
    
    /**
     * Helper to get all available sets in the file
     */
    public function getAvailableSets(): array
    {
        return $this->data->pluck('setId')->unique()->values()->all();
    }
}
