<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Domain;

use GrandLineCards\Shared\Domain\DomainError;

final class CardNotExist extends DomainError
{
    private string $id;

    public function __construct(CardId $id)
    {
        $this->id = $id->value();
        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'card_not_exist';
    }

    protected function errorMessage(): string
    {
        return sprintf('The card <%s> does not exist', $this->id);
    }
}
