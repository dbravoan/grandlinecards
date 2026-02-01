<?php
declare(strict_types=1);
namespace GrandLineCards\DeckBuilder\Deck\Domain;

use GrandLineCards\Shared\Domain\ValueObject\BoolValueObject;

final class DeckPublicity extends BoolValueObject
{
    public function isPublic(): bool { return $this->value(); }
}
