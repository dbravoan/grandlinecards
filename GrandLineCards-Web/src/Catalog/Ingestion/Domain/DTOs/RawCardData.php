<?php

namespace Src\Catalog\Ingestion\Domain\DTOs;

class RawCardData
{
    public function __construct(
        public string $id, // OP01-001
        public string $setId, // OP01
        public string $name,
        public string $rarity, // L, C, SR...
        public string $color, // Red
        public string $type, // Leader
        public ?int $cost,
        public ?int $power,
        public ?int $counter,
        public ?int $life,
        public array $attributes, // ["Straw Hat Crew", "Supernovas"]
        public ?string $effectText,
        public ?string $triggerText,
        public string $imageUrl,
    ) {}
}
