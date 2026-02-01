<?php
declare(strict_types=1);
namespace GrandLineCards\Catalog\Card\Infrastructure\Persistence\Eloquent;

use App\Models\Card as EloquentModel;
use App\Models\Expansion as EloquentExpansion;
use GrandLineCards\Catalog\Card\Domain\Card;
use GrandLineCards\Catalog\Card\Domain\CardAttribute;
use GrandLineCards\Catalog\Card\Domain\CardColor;
use GrandLineCards\Catalog\Card\Domain\CardCost;
use GrandLineCards\Catalog\Card\Domain\CardCounter;
use GrandLineCards\Catalog\Card\Domain\CardId;
use GrandLineCards\Catalog\Card\Domain\CardImageUrl;
use GrandLineCards\Catalog\Card\Domain\CardLife;
use GrandLineCards\Catalog\Card\Domain\CardPower;
use GrandLineCards\Catalog\Card\Domain\CardRarity;
use GrandLineCards\Catalog\Card\Domain\CardRepository;
use GrandLineCards\Catalog\Card\Domain\CardType;
use GrandLineCards\Catalog\Card\Domain\ExpansionId;
use GrandLineCards\Shared\Domain\Criteria\Criteria;
use GrandLineCards\Shared\Infrastructure\Persistence\Eloquent\EloquentCriteriaConverter;
use GrandLineCards\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;

final class EloquentCardRepository extends EloquentRepository implements CardRepository
{
    protected function model(): string
    {
        return EloquentModel::class;
    }

    public function save(Card $card): void
    {
        $data = $card->toPrimitives();
        
        // Handle Expansion Lookup
        $expansionCode = $data['expansion_id'];
        $expansion = EloquentExpansion::where('code', $expansionCode)->first();
        if ($expansion) {
            $data['expansion_id'] = $expansion->id;
        } else {
            // Should validation happen before? Or throw exception?
            // For now assuming expansion exists or we fail.
            // Or maybe create new expansion? No, that's Expansion Context duty.
            throw new \RuntimeException("Expansion with code <$expansionCode> not found");
        }

        // Use updateOrCreate or specific logic
        $this->model()::updateOrCreate(
            ['card_id' => $data['id']],
            $data
        );
    }

    public function find(CardId $id): ?Card
    {
        /** @var EloquentModel|null $model */
        $model = $this->model()::where('card_id', $id->value())->with(['expansion', 'translations'])->first();
        return $model ? $this->toDomain($model) : null;
    }

    public function searchByCriteria(Criteria $criteria): array
    {
        // Get array of Filter objects
        $filters = $criteria->filters()->filters();
        $nameFilter = null;

        // Custom handle for 'name' filter (Translation search)
        foreach ($filters as $key => $filter) {
            // $filter is Filter object
            if ($filter->field()->value() === 'name') {
                $nameFilter = $filter;
                unset($filters[$key]);
                break;
            }
        }

        // Reconstruct Criteria without name filter
        // First create Filters collection
        $filtersCollection = new \GrandLineCards\Shared\Domain\Criteria\Filters(array_values($filters));
        
        $modifiedCriteria = new Criteria(
            $filtersCollection,
            $criteria->order(),
            $criteria->offset(),
            $criteria->limit()
        );

        $converter = new EloquentCriteriaConverter($modifiedCriteria, $this->hydrator());
        $query = $this->newQuery()->with(['expansion', 'translations']);

        if ($nameFilter) {
            // Apply name filter on translations
            $value = $nameFilter->value()->value();
            $query->whereHas('translations', function($q) use ($value) {
                $q->where('name', 'like', "%{$value}%");
            });
        }

        $models = $converter->convert($query)->get();

        return $models->map(fn($model) => $this->toDomain($model))->toArray();
    }

    public function countByCriteria(Criteria $criteria): int
    {
        $converter = new EloquentCriteriaConverter($criteria, $this->hydrator());
        $query = $this->newQuery();
        // Remove limit/offset from criteria for count? 
        // Criteria object contains limit/offset, which the converter applies?
        // Let's check EloquentCriteriaConverter.
        // If converter applies limit/offset, we need to strip them or avoid applying them for count.
        // Actually, converter `convert` method applies everything.
        // We should check `EloquentCriteriaConverter` again. 
        // Best approach: Criteria has offset/limit as separate properties.
        // We should create a clone of Criteria without limit/offset? Or modify Converter?
        
        // Simpler: The Converter applies limit/offset if they exist in Criteria.
        // So for counting, we should pass a Criteria with null limit/offset.
        
        $countCriteria = new Criteria(
            $criteria->filters(),
            $criteria->order(),
            null, // Offset
            null  // Limit
        );
        
        $converter = new EloquentCriteriaConverter($countCriteria, $this->hydrator());
        return $converter->convert($query)->count();
    }

    private function toDomain(EloquentModel $model): Card
    {
        // Expansion code from relation
        $expansionCode = $model->expansion ? $model->expansion->code : 'UNKNOWN'; 
        
        $translations = [];
        foreach ($model->translations as $translation) {
            $translations[] = new \GrandLineCards\Catalog\Card\Domain\CardTranslation(
                new \GrandLineCards\Catalog\Card\Domain\Locale($translation->locale),
                new \GrandLineCards\Catalog\Card\Domain\CardName($translation->name),
                $translation->effect_text ? new \GrandLineCards\Catalog\Card\Domain\CardEffect($translation->effect_text) : null,
                $translation->trigger_text ? new \GrandLineCards\Catalog\Card\Domain\CardTrigger($translation->trigger_text) : null
            );
        }

        return new Card(
            new CardId($model->card_id),
            new ExpansionId($expansionCode),
            new CardColor($model->color), // Enum validation might fail if DB has invalid val
            new CardType($model->type),
            new CardRarity($model->rarity),
            $model->cost !== null ? new CardCost($model->cost) : null,
            $model->power !== null ? new CardPower($model->power) : null,
            $model->counter !== null ? new CardCounter($model->counter) : null,
            $model->life !== null ? new CardLife($model->life) : null,
            $model->image_url !== null ? new CardImageUrl($model->image_url) : null,
            array_map(fn($attr) => new CardAttribute($attr), is_string($model->attributes) ? json_decode($model->attributes, true) : ($model->attributes ?? [])), 
            $translations
        );
    }

    private function hydrator(): array
    {
        return [
            'id' => 'card_id',
            'expansionId' => 'expansion_id', // This might need join logic in criteria if searching by expansion code
            'name' => 'name', // Where is name? Ah, translations? or is it in card table?
            // Checking card table again: it has NO NAME?
            // card_id, color, type, attributes, cost, power, counter, life, rarity, image_url.
            // Name is in `card_translations` table?
            // app/Models/Card.php has `translations` relation.
            // If Card Aggregate includes Name, I need to handle translations.
        ];
    }
}
