<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Application\SearchByCriteria;

use GrandLineCards\Shared\Domain\Bus\Query\Response;

final class CardsResponse implements Response
{
    public function __construct(public readonly array $cards) {}
}
