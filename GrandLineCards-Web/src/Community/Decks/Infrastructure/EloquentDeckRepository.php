<?php

namespace Src\Community\Decks\Infrastructure;

use App\Models\Deck as EloquentDeck;
use Src\Community\Decks\Domain\Deck;
use Src\Community\Decks\Domain\DeckRepositoryInterface;
use Src\Shared\Domain\ValueObjects\CardId;
use Illuminate\Support\Str;

class EloquentDeckRepository implements DeckRepositoryInterface
{
    public function save(Deck $deck): void
    {
        $deckData = [
            'id' => $deck->getId(),
            'name' => $deck->getName(),
            'user_id' => $deck->getUserId(),
            'leader_id' => $deck->getLeaderId()->value(),
            'is_public' => $deck->isPublic(),
        ];

        $eloquentDeck = EloquentDeck::updateOrCreate(
            ['id' => $deck->getId()],
            $deckData
        );

        // Sync Cards
        // Format: [card_id => ['quantity' => q]]
        $syncData = [];
        foreach ($deck->getCards() as $cardEntry) {
            $cardId = $cardEntry['cardId']->value();
            $qty = $cardEntry['quantity'];
            $syncData[$cardId] = ['quantity' => $qty];
        }

        $eloquentDeck->cards()->sync($syncData);
    }

    public function find(string $id): ?Deck
    {
        $model = EloquentDeck::with('cards')->find($id);
        if (!$model) return null;
        return $this->toDomain($model);
    }

    public function findByUser(int $userId): array
    {
        return EloquentDeck::where('user_id', $userId)->get()
            ->map(fn($m) => $this->toDomain($m))
            ->toArray();
    }

    public function findPublicLatest(int $limit = 10): array
    {
        return EloquentDeck::where('is_public', true)
            ->latest()
            ->take($limit)
            ->get()
            ->map(fn($m) => $this->toDomain($m))
            ->toArray();
    }

    private function toDomain(EloquentDeck $model): Deck
    {
        $cards = $model->cards->map(function ($card) {
            return [
                'cardId' => new CardId($card->card_id),
                'quantity' => $card->pivot->quantity
            ];
        })->toArray();

        // Reconstruct domain object
        return new Deck(
            $model->id,
            $model->name,
            $model->user_id,
            new CardId($model->leader_id),
            $cards,
            (bool)$model->is_public
        );
    }
}
