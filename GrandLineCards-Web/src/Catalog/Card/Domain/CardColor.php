<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Domain;

use GrandLineCards\Shared\Domain\ValueObject\Enum;

/**
 * @method static CardColor red()
 * @method static CardColor green()
 * @method static CardColor blue()
 * @method static CardColor purple()
 * @method static CardColor black()
 * @method static CardColor yellow()
 * @method static CardColor multi()
 * @method static CardColor redGreen()
 * @method static CardColor redBlue()
 * @method static CardColor redBlack()
 * @method static CardColor redPurple()
 * @method static CardColor redYellow()
 * @method static CardColor greenBlack()
 * @method static CardColor greenBlue()
 * @method static CardColor greenPurple()
 * @method static CardColor greenYellow()
 * @method static CardColor blueBlack()
 * @method static CardColor bluePurple()
 * @method static CardColor blueYellow()
 * @method static CardColor purpleBlack()
 * @method static CardColor purpleYellow()
 * @method static CardColor blackYellow()
 */
final class CardColor extends Enum
{
    public const RED    = 'Red';
    public const GREEN  = 'Green';
    public const BLUE   = 'Blue';
    public const PURPLE = 'Purple';
    public const BLACK  = 'Black';
    public const YELLOW = 'Yellow';
    public const MULTI  = 'Multi';
    
    // Dual Colors
    public const RED_GREEN   = 'Red/Green';
    public const RED_BLUE    = 'Red/Blue';
    public const RED_BLACK   = 'Red/Black';
    public const RED_PURPLE  = 'Red/Purple';
    public const RED_YELLOW  = 'Red/Yellow';
    
    public const GREEN_BLACK  = 'Green/Black';
    public const GREEN_BLUE   = 'Green/Blue';
    public const GREEN_PURPLE = 'Green/Purple';
    public const GREEN_YELLOW = 'Green/Yellow';
    
    public const BLUE_BLACK   = 'Blue/Black';
    public const BLUE_PURPLE  = 'Blue/Purple';
    public const BLUE_YELLOW  = 'Blue/Yellow';
    
    public const PURPLE_BLACK  = 'Purple/Black';
    public const PURPLE_YELLOW = 'Purple/Yellow';
    
    public const BLACK_YELLOW = 'Black/Yellow';

    protected function throwExceptionForInvalidValue($value): never
    {
        throw new \InvalidArgumentException(sprintf('The card color <%s> is invalid', $value));
    }
}
