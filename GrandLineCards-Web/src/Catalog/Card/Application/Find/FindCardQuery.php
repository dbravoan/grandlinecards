<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Application\Find;

use GrandLineCards\Shared\Domain\Bus\Query\Query;

final class FindCardQuery implements Query
{
    public function __construct(private string $id) {}

    public function id(): string { return $this->id; }
}
