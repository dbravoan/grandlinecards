<?php

namespace Src\Catalog\Cards\Infrastructure;

use App\Models\Card as EloquentCard; // Assuming we map to the standard eloquent model or a specific one
use Src\Catalog\Cards\Domain\Card;
use Src\Catalog\Cards\Domain\CardRepositoryInterface;
use Src\Shared\Domain\ValueObjects\CardId;

class EloquentCardRepository implements CardRepositoryInterface
{
    public function find(CardId $id): ?Card
    {
        $eloquentModel = EloquentCard::where('card_id', $id->value())->first();

        if (!$eloquentModel) {
            return null;
        }

        return $this->toDomain($eloquentModel);
    }

    public function search(array $filters): array
    {
        $query = EloquentCard::query();

        if (!empty($filters['q'])) {
            $term = '%' . $filters['q'] . '%';
            $query->where(function ($q) use ($term) {
                $q->where('card_id', 'like', $term)
                  ->orWhere('name', 'like', $term);
                  // Future: Add search in translations
            });
        }

        if (!empty($filters['color'])) {
            $query->where('color', $filters['color']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['cost'])) {
            $query->where('cost', $filters['cost']);
        }

        $results = $query->paginate(20); // Basic pagination

        return array_map(fn($model) => $this->toDomain($model), $results->items());
    }

    public function save(Card $card): void
    {
        // Logic to persist domain entity to DB
    }

    private function toDomain(EloquentCard $model): Card
    {
        return new Card(
            new CardId($model->card_id),
            (string) $model->expansion_id,
            (string) $model->name,
            $model->type,
            [$model->color], // Assuming simple color for now
            $model->cost ?? 0,
            $model->power,
            $model->counter,
            $model->attributes ?? [],
            (string) $model->effect_en, // Mapping eloquent fields
            (string) $model->effect_es,
            $model->image_url,
            $model->rarity,
            $model->rulings ?? [] // Assuming Eloquent model has this accessor or attribute
        );
    }
}
