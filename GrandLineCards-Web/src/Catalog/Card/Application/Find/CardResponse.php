<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Application\Find;

use GrandLineCards\Shared\Domain\Bus\Query\Response;

final class CardResponse implements Response
{
    public function __construct(
        public readonly string $id,
        public readonly string $expansionId,
        public readonly string $color,
        public readonly string $type,
        public readonly string $rarity,
        public readonly ?int $cost,
        public readonly ?int $power,
        public readonly ?int $counter,
        public readonly ?int $life,
        public readonly ?string $imageUrl,
        public readonly array $attributes,
        public readonly array $translations
    ) {}
}
