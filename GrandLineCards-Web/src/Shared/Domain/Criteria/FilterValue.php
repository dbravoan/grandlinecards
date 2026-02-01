<?php
declare(strict_types=1);
namespace GrandLineCards\Shared\Domain\Criteria;

final class FilterValue
{
    public function __construct(protected mixed $value) {}
    public function value(): mixed { return $this->value; }
}
