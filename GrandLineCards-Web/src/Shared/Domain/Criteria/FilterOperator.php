<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Domain\Criteria;

use GrandLineCards\Shared\Domain\ValueObject\Enum;
use InvalidArgumentException;

/**
 * @method static FilterOperator gt()
 * @method static FilterOperator lt()
 * @method static FilterOperator like()
 */
final class FilterOperator extends Enum
{
    public const EQUAL        = '=';
    public const NOT_EQUAL    = '!=';
    public const GT           = '>';
    public const LT           = '<';
    public const GTE          = '>=';
    public const LTE          = '<=';
    public const CONTAINS     = 'CONTAINS';
    public const NOT_CONTAINS = 'NOT_CONTAINS';
    public const STARTS_WITH  = 'STARTS_WITH';
    public const ENDS_WITH    = 'ENDS_WITH';
    public const BETWEEN      = 'BETWEEN';
    public const IN           = 'IN';

    private static array $containing = [self::CONTAINS, self::NOT_CONTAINS];

    public function isContaining(): bool {
        return in_array($this->value(), self::$containing, true);
    }

    protected function throwExceptionForInvalidValue($value): never {
        throw new InvalidArgumentException(sprintf('The filter <%s> is invalid', $value));
    }
}
