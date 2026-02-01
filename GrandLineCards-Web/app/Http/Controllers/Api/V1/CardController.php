<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use GrandLineCards\Catalog\Card\Application\Find\CardFinder;
use GrandLineCards\Catalog\Card\Application\SearchByCriteria\CardsByCriteriaSearcher;
use GrandLineCards\Catalog\Card\Domain\CardId;

class CardController extends Controller
{
    public function __construct(
        private readonly CardsByCriteriaSearcher $searcher,
        private readonly CardFinder $finder
    ) {}

    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $filters = $request->only(['q', 'color', 'type', 'cost', 'code']);

        // Map request filters to Criteria Filters structure
        $criteriaFilters = [];
        if ($request->has('code') && $request->get('code')) {
             $criteriaFilters[] = ['field' => 'id', 'operator' => '=', 'value' => $request->get('code')];
        }
        if ($request->has('q') && $request->get('q')) {
             $criteriaFilters[] = ['field' => 'name', 'operator' => 'CONTAINS', 'value' => $request->get('q')];
        }
        if ($request->has('color') && $request->get('color')) {
             $criteriaFilters[] = ['field' => 'color', 'operator' => '=', 'value' => $request->get('color')];
        }
         if ($request->has('type') && $request->get('type')) {
             $criteriaFilters[] = ['field' => 'type', 'operator' => '=', 'value' => $request->get('type')];
        }
         if ($request->has('cost') && $request->get('cost')) {
             $criteriaFilters[] = ['field' => 'cost', 'operator' => '=', 'value' => $request->get('cost')];
        }

        $cardsResponse = $this->searcher->__invoke(
            $criteriaFilters, 
            'id',  // Order By
            'asc', // Order Type
            100,    // Limit (API might want different limit)
            0
        );

        // Since execute returns an array of Domain Objects, we map them to Resources
        return CardResource::collection($cardsResponse->cards);
    }

    public function show(string $cardId): JsonResponse
    {
        try {
            $cardResponse = $this->finder->__invoke(new CardId($cardId));
        } catch (\GrandLineCards\Catalog\Card\Domain\CardNotExist $e) {
            return response()->json(['message' => 'Card not found'], 404);
        }

        return response()->json(new CardResource($cardResponse));
    }
}
