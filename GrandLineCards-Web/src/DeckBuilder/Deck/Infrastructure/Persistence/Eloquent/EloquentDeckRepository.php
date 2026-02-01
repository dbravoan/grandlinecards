<?php
declare(strict_types=1);
namespace GrandLineCards\DeckBuilder\Deck\Infrastructure\Persistence\Eloquent;

use App\Models\Deck as EloquentModel;
use GrandLineCards\DeckBuilder\Deck\Domain\CardId;
use GrandLineCards\DeckBuilder\Deck\Domain\CardQuantity;
use GrandLineCards\DeckBuilder\Deck\Domain\Deck;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckCard;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckId;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckName;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckPublicity;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckRepository;
use GrandLineCards\DeckBuilder\Deck\Domain\UserId;
use GrandLineCards\Shared\Infrastructure\Persistence\Eloquent\EloquentRepository;

final class EloquentDeckRepository extends EloquentRepository implements DeckRepository
{
    protected function model(): string
    {
        return EloquentModel::class;
    }

    public function save(Deck $deck): void
    {
        $data = $deck->toPrimitives();
        
        $modelData = [
            'id' => $data['id'],
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'leader_id' => $data['leader_id'],
            'is_public' => $data['is_public'],
        ];

        /** @var EloquentModel $model */
        $model = $this->model()::updateOrCreate(
            ['id' => $data['id']],
            $modelData
        );

        // Sync Cards
        $pivotData = [];
        foreach ($data['cards'] as $card) {
             $pivotData[$card['id']] = ['quantity' => $card['quantity']]; 
        }

        $model->cards()->sync($pivotData);
    }

    public function find(DeckId $id): ?Deck
    {
        /** @var EloquentModel|null $model */
        $model = $this->model()::where('id', $id->value())->with('cards')->first();
        if (!$model) return null;

        return $this->toDomain($model);
    }

    private function toDomain(EloquentModel $model): Deck
    {
        $cards = [];
        // Loop through related cards
        foreach ($model->cards as $cardModel) {
             // Pivot data
             $quantity = $cardModel->pivot->quantity;
             $cards[] = new DeckCard(
                 new CardId($cardModel->card_id), // Using string ID
                 new CardQuantity($quantity)
             );
        }

        return new Deck(
            new DeckId($model->id),
            new DeckName($model->name),
            new UserId($model->user_id),
            new CardId($model->leader_id), // Is leader_id in deck table a string or int? 
                                           // Migration check needed.
            new DeckPublicity((bool)$model->is_public),
            $cards
        );
    }
}
