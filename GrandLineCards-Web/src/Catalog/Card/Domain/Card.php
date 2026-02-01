<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Domain;

final class Card
{
    public function __construct(
        private CardId $id,
        private ExpansionId $expansionId,
        private CardColor $color,
        private CardType $type,
        private CardRarity $rarity,
        private ?CardCost $cost,
        private ?CardPower $power,
        private ?CardCounter $counter,
        private ?CardLife $life,
        private ?CardImageUrl $imageUrl,
        private array $attributes, // Array of CardAttribute
        private array $translations // Array of CardTranslation
    ) {}

    public static function create(
        CardId $id,
        ExpansionId $expansionId,
        CardColor $color,
        CardType $type,
        CardRarity $rarity,
        ?CardCost $cost,
        ?CardPower $power,
        ?CardCounter $counter,
        ?CardLife $life,
        ?CardImageUrl $imageUrl,
        array $attributes,
        array $translations
    ): self {
        return new self(
            $id,
            $expansionId,
            $color,
            $type,
            $rarity,
            $cost,
            $power,
            $counter,
            $life,
            $imageUrl,
            $attributes,
            $translations
        );
    }

    public function id(): CardId { return $this->id; }
    public function expansionId(): ExpansionId { return $this->expansionId; }
    public function color(): CardColor { return $this->color; }
    public function type(): CardType { return $this->type; }
    public function rarity(): CardRarity { return $this->rarity; }
    public function cost(): ?CardCost { return $this->cost; }
    public function power(): ?CardPower { return $this->power; }
    public function counter(): ?CardCounter { return $this->counter; }
    public function life(): ?CardLife { return $this->life; }
    public function imageUrl(): ?CardImageUrl { return $this->imageUrl; }
    /** @return CardAttribute[] */
    public function attributes(): array { return $this->attributes; }
    /** @return CardTranslation[] */
    public function translations(): array { return $this->translations; }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id->value(),
            'expansion_id' => $this->expansionId->value(), // mapping to be handled by repo
            'color' => $this->color->value(),
            'type' => $this->type->value(),
            'rarity' => $this->rarity->value(),
            'cost' => $this->cost?->value(),
            'power' => $this->power?->value(),
            'counter' => $this->counter?->value(),
            'life' => $this->life?->value(),
            'image_url' => $this->imageUrl?->value(),
            'attributes' => array_map(fn(CardAttribute $a) => $a->value(), $this->attributes),
            'translations' => array_map(fn(CardTranslation $t) => $t->toPrimitives(), $this->translations),
        ];
    }
}
