<?php

namespace Src\Catalog\Cards\Domain;

use Src\Shared\Domain\ValueObjects\CardId;
use Src\Shared\Domain\AggregateRoot;

class Card extends AggregateRoot
{
    public function __construct(
        private readonly CardId $id, // OP01-001
        private readonly string $expansionId,
        private readonly string $name,
        private readonly string $type, // Character, Leader
        private readonly array $colors,
        private readonly int $cost,
        private readonly ?int $power,
        private readonly ?int $counter,
        private readonly array $attributes, // Slash, Wisdom
        private readonly string $effectEn,
        private readonly string $effectEs,
        private readonly ?string $imageUrl,
        private readonly string $rarity,
        private readonly array $rulings = []
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function rulings(): array
    {
        return $this->rulings;
    }

    public function isLeader(): bool
    {
        return $this->type === 'Leader';
    }

    public function isMultiColor(): bool
    {
        return count($this->colors) > 1;
    }

    public function canBePlayedWith(int $donAvailable): bool
    {
        return $donAvailable >= $this->cost;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'expansion_id' => $this->expansionId,
            'name' => $this->name,
            'type' => $this->type,
            'colors' => $this->colors,
            'cost' => $this->cost,
            'power' => $this->power,
            'counter' => $this->counter,
            'attributes' => $this->attributes,
            'effect_en' => $this->effectEn,
            'effect_es' => $this->effectEs,
            'image_url' => $this->imageUrl,
            'rarity' => $this->rarity,
            'rulings' => $this->rulings,
        ];
    }
}
