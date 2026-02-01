<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Application\Find;

use GrandLineCards\Catalog\Card\Domain\Card;
use GrandLineCards\Catalog\Card\Domain\CardId;
use GrandLineCards\Catalog\Card\Domain\CardRepository;
use GrandLineCards\Catalog\Card\Domain\CardNotExist; // Need to create this exception

final class CardFinder
{
    public function __construct(private CardRepository $repository) {}

    public function __invoke(CardId $id): CardResponse
    {
        $card = $this->repository->find($id);

        if (null === $card) {
            throw new CardNotExist($id);
        }

        return $this->toResponse($card);
    }

    private function toResponse(Card $card): CardResponse
    {
        $data = $card->toPrimitives();
        return new CardResponse(
            $data['id'],
            $data['expansion_id'],
            $data['color'],
            $data['type'],
            $data['rarity'],
            $data['cost'],
            $data['power'],
            $data['counter'],
            $data['life'],
            $data['image_url'],
            $data['attributes'],
            $data['translations']
        );
    }
}
