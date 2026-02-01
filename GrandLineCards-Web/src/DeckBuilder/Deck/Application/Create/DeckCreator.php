<?php
declare(strict_types=1);
namespace GrandLineCards\DeckBuilder\Deck\Application\Create;

use GrandLineCards\DeckBuilder\Deck\Domain\CardId;
use GrandLineCards\DeckBuilder\Deck\Domain\CardQuantity;
use GrandLineCards\DeckBuilder\Deck\Domain\Deck;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckCard;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckId;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckName;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckPublicity;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckRepository;
use GrandLineCards\DeckBuilder\Deck\Domain\UserId;
use GrandLineCards\Shared\Domain\ValueObject\Uuid;

final class DeckCreator
{
    public function __construct(private DeckRepository $repository) {}

    public function __invoke(
        string $id,
        string $name,
        int $userId,
        string $leaderId,
        bool $isPublic,
        array $cards // [['id' => 'OP01-001', 'quantity' => 4], ...]
    ): void {
        $deckId = new DeckId($id);
        $deckCards = array_map(function($card) {
            return new DeckCard(
                new CardId($card['id']),
                new CardQuantity((int)$card['quantity'])
            );
        }, $cards);

        $deck = Deck::create(
            $deckId,
            new DeckName($name),
            new UserId($userId),
            new CardId($leaderId),
            new DeckPublicity($isPublic),
            $deckCards
        );

        $this->repository->save($deck);
    }
}
