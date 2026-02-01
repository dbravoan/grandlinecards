<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Domain\ValueObject;

abstract class FloatValueObject
{
    public function __construct(protected float $value) {}
    public function value(): float { return $this->value; }
    public function isBiggerThan(FloatValueObject $other): bool { 
        return $this->value() > $other->value(); 
    }
    public function isSmallerThan(FloatValueObject $other): bool { 
        return $this->value() < $other->value(); 
    }
}
