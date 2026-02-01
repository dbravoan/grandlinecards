<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Application\SearchByCriteria;

use GrandLineCards\Catalog\Card\Domain\Card;
use GrandLineCards\Catalog\Card\Domain\CardRepository;
use GrandLineCards\Catalog\Card\Application\Find\CardResponse;
use GrandLineCards\Shared\Domain\Criteria\Criteria;
use GrandLineCards\Shared\Domain\Criteria\Filters;
use GrandLineCards\Shared\Domain\Criteria\Order;

final class CardsByCriteriaSearcher
{
    public function __construct(private CardRepository $repository) {}

    public function __invoke(array $filters, ?string $orderBy, ?string $order, ?int $limit, ?int $offset): CardsResponse
    {
        $criteria = new Criteria(
            Filters::fromValues($filters),
            Order::fromValues($orderBy, $order),
            $offset,
            $limit
        );

        $cards = $this->repository->searchByCriteria($criteria);

        return new CardsResponse(array_map(fn(Card $card) => $this->toResponse($card), $cards));
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

    public function count(array $filters): int
    {
        $criteria = new Criteria(
            Filters::fromValues($filters),
            Order::none(),
            null,
            null
        );
        return $this->repository->countByCriteria($criteria);
    }
}
