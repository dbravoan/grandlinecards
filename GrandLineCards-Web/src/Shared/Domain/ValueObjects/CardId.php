<?php

namespace Src\Shared\Domain\ValueObjects;

use InvalidArgumentException;

class CardId
{
    public function __construct(private readonly string $value)
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException("Invalid Card ID format: <{$value}>. Expected format: OPXX-XXX or similar.");
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    private function isValid(string $value): bool
    {
        // Simple regex for OP/ST/P codes: OP01-001, ST01-001, P-001
        return preg_match('/^[A-Z]{1,3}\d{0,2}-\d{3}$/', $value);
    }
}
