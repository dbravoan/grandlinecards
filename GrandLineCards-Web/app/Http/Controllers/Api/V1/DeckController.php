<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use GrandLineCards\DeckBuilder\Deck\Application\Create\DeckCreator;
use GrandLineCards\DeckBuilder\Deck\Domain\DeckRepository;

class DeckController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly DeckRepository $deckRepository,
        private readonly DeckCreator $deckCreator
        // private readonly DeckValidationService $validationService // Pending migration
    ) {}

    public function index()
    {
        $decks = \App\Models\Deck::where('is_public', true)->latest()->get();
        return $this->successResponse($decks, 'Public decks retrieved successfully.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'leader_id' => 'required|string',
            'cards' => 'required|array',
            'cards.*.id' => 'required|string',
            'cards.*.quantity' => 'required|integer|min:1',
            'is_public' => 'boolean'
        ]);

        // Validation Service migration skipped for now
        /*
        $cardsForValidation = [];
        foreach ($request->cards as $cardData) {
            $cardsForValidation[$cardData['id']] = $cardData['quantity'];
        }
        $this->validationService->validate($request->leader_id, $cardsForValidation);
        */

        $userId = $request->user() ? $request->user()->id : 1; 
        $deckId = (string) Str::uuid();

        // Cards format expected by Creator: [['id' => '...', 'quantity' => ...]]
        // Request has same format roughly, check structure.
        // Request: cards: [{id, quantity}]
        
        $this->deckCreator->__invoke(
            $deckId,
            $request->name,
            $userId,
            $request->leader_id,
            $request->boolean('is_public'),
            $request->cards
        );

        // Return simplified response as Domain Deck doesn't have toArray directly compatible?
        // Actually Deck has toArray (toPrimitives).
        // Fetch deck to return? Or construct response.
        return $this->successResponse(['id' => $deckId], 'Deck created successfully.', 201);
    }
}
