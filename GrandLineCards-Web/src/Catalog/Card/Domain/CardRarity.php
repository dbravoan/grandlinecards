<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Domain;

use GrandLineCards\Shared\Domain\ValueObject\Enum;

/**
 * @method static CardRarity l()
 * @method static CardRarity c()
 * @method static CardRarity uc()
 * @method static CardRarity r()
 * @method static CardRarity sr()
 * @method static CardRarity sec()
 * @method static CardRarity p()
 */
final class CardRarity extends Enum
{
    public const L   = 'L';
    public const C   = 'C';
    public const UC  = 'UC';
    public const R   = 'R';
    public const SR  = 'SR';
    public const SEC = 'SEC';
    public const P   = 'P';

    protected function throwExceptionForInvalidValue($value): never
    {
        throw new \InvalidArgumentException(sprintf('The card rarity <%s> is invalid', $value));
    }
}
