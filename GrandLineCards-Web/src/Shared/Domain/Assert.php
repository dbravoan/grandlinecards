<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Domain;

use InvalidArgumentException;

final class Assert
{
    public static function arrayOf(array $classes, array $items): void {
        foreach ($items as $item) {
            self::instanceOf($classes, $item);
        }
    }

    public static function instanceOf(array $classes, $item): void {
        $is_instance = false;
        foreach ($classes as $class) {
            if ($item instanceof $class) {
                $is_instance = true;
                break;
            }
        }
        if (!$is_instance) {
            throw new InvalidArgumentException(
                sprintf('The object <%s> is not an instance of any of the allowed types:  [%s]', 
                    $item::class, implode(', ', $classes))
            );
        }
    }
}
