<?php
declare(strict_types=1);
namespace GrandLineCards\DeckBuilder\Deck\Domain;

final class Deck
{
    /** @param DeckCard[] $cards */
    public function __construct(
        private DeckId $id,
        private DeckName $name,
        private UserId $userId,
        private CardId $leaderId,
        private DeckPublicity $publicity,
        private array $cards
    ) {}

    public static function create(
        DeckId $id,
        DeckName $name,
        UserId $userId,
        CardId $leaderId,
        DeckPublicity $publicity,
        array $cards
    ): self {
        return new self($id, $name, $userId, $leaderId, $publicity, $cards);
    }

    public function id(): DeckId { return $this->id; }
    public function name(): DeckName { return $this->name; }
    public function userId(): UserId { return $this->userId; }
    public function leaderId(): CardId { return $this->leaderId; }
    public function publicity(): DeckPublicity { return $this->publicity; }
    /** @return DeckCard[] */
    public function cards(): array { return $this->cards; }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id->value(),
            'name' => $this->name->value(),
            'user_id' => $this->userId->value(),
            'leader_id' => $this->leaderId->value(),
            'is_public' => $this->publicity->value(),
            'cards' => array_map(function(DeckCard $card) {
                return [
                    'id' => $card->cardId()->value(),
                    'quantity' => $card->quantity()->value()
                ];
            }, $this->cards)
        ];
    }
}
