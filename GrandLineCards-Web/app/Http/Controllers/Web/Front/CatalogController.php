<?php

namespace App\Http\Controllers\Web\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use GrandLineCards\Catalog\Card\Application\Find\CardFinder;
use GrandLineCards\Catalog\Card\Application\SearchByCriteria\CardsByCriteriaSearcher;
use GrandLineCards\Catalog\Card\Domain\CardId; // For Find
use App\Http\Resources\CardResource;

class CatalogController extends Controller
{
    public function __construct(
        private readonly CardsByCriteriaSearcher $searcher,
        private readonly CardFinder $finder
    ) {}

    public function index(Request $request, ?string $expansion = null, ?string $color = null): Response
    {
        $filters = $request->only(['q', 'color', 'type', 'cost', 'rarity', 'expansion']);
        
        // Merge Route Params into Filters
        if ($expansion) {
            $filters['expansion'] = $expansion;
        }
        if ($color) {
            $filters['color'] = $color;
        }

        return $this->performSearch($request, $filters);
    }

    public function smartIndex(Request $request, string $slug, \App\Services\Catalog\SmartUrlParser $parser): Response
    {
        // 1. Parse Slug
        $parsedFilters = $parser->parse($slug);
        
        // 2. Merge with Query String (Query string overrides slug? Or adds to it?)
        // Usually Query String adds/overrides.
        $requestFilters = $request->only(['q', 'color', 'type', 'cost', 'rarity', 'expansion']);
        
        // Merge strategy: Array merge?
        // Note: Parser returns arrays for color/expansion. Request might be string or array.
        // We normalize everything to our Searcher expectations (usually arrays for IN operators).
        
        $filters = array_merge($requestFilters, array_filter($parsedFilters));
        
        // Generate SEO Metadata
        $metadata = $parser->generateMetadata($parsedFilters);
        
        return $this->performSearch($request, $filters, $metadata);
    }

    private function performSearch(Request $request, array $filters, array $metadata = []): Response
    {
        // Normalize Filters for Searcher
        // ... (Logic from previous index)
        
        $criteriaFilters = [];
        if (!empty($filters['q'])) {
             $criteriaFilters[] = ['field' => 'name', 'operator' => 'CONTAINS', 'value' => $filters['q']];
        }
        if (!empty($filters['color'])) {
             $val = $filters['color'];
             $op = is_array($val) ? 'IN' : '=';
             $criteriaFilters[] = ['field' => 'color', 'operator' => $op, 'value' => $val];
        }
        if (!empty($filters['rarity'])) {
             $val = $filters['rarity'];
             $op = is_array($val) ? 'IN' : '=';
             $criteriaFilters[] = ['field' => 'rarity', 'operator' => $op, 'value' => $val];
        }
        if (!empty($filters['type'])) {
             $val = $filters['type'];
             $op = is_array($val) ? 'IN' : '=';
             $criteriaFilters[] = ['field' => 'type', 'operator' => $op, 'value' => $val];
        }
        if (!empty($filters['expansion'])) {
             $val = $filters['expansion'];
             $op = is_array($val) ? 'IN' : '=';
             $criteriaFilters[] = ['field' => 'expansionId', 'operator' => $op, 'value' => $val];
        }
        if (!empty($filters['cost'])) {
              $criteriaFilters[] = ['field' => 'cost', 'operator' => '=', 'value' => $filters['cost']];
        }

        $limit = 20;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $limit;

        $cardsResponse = $this->searcher->__invoke(
            $criteriaFilters, 
            'id',  
            'asc', 
            $limit,    
            $offset
        );

        $total = $this->searcher->count($criteriaFilters);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $cardsResponse->cards,
            $total,
            $limit,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return Inertia::render('Catalog/Index', [
            'cards' => CardResource::collection($paginator),
            'filters' => $filters,
            'expansions' => \App\Models\Expansion::select('code', 'name')->orderBy('code')->get(),
            'seo' => $metadata // Pass SEO data to view
        ]);
    }

    public function show(string $cardId): Response
    {
        try {
            $cardResponse = $this->finder->__invoke(new CardId($cardId));
        } catch (\GrandLineCards\Catalog\Card\Domain\CardNotExist $e) {
            abort(404);
        }

        $resource = new CardResource($cardResponse);
        // Debugging
        \Illuminate\Support\Facades\Log::info('Card Resource Data:', $resource->resolve());
        
        return Inertia::render('Catalog/CardDetail', [
            'card' => $resource->resolve(),
            'priceHistory' => \App\Models\PricePoint::whereHas('card', function ($q) use ($cardId) {
                $q->where('card_id', $cardId);
            })->orderBy('created_at')->get(),
            'listings' => \App\Models\MarketListing::whereHas('card', function ($q) use ($cardId) {
                $q->where('card_id', $cardId);
            })->where('status', 'active')->with('user')->get(),
            'variants' => $this->getVariants($cardResponse),
            'collectionItem' => \Illuminate\Support\Facades\Auth::check() 
                ? \App\Models\CollectionItem::where('user_id', \Illuminate\Support\Facades\Auth::id())
                    ->whereHas('card', function ($q) use ($cardId) {
                        $q->where('card_id', $cardId);
                    })->first()
                : null,
        ]);
    }

    private function getVariants(\GrandLineCards\Catalog\Card\Application\Find\CardResponse $card): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        // 1. Get current card name (try ES first, then fallback)
        // The DTO has an array of translations. We need to find the name.
        $name = null;
        foreach ($card->translations as $t) {
            if ($t['locale'] === 'es') {
                $name = $t['name'];
                break;
            }
        }
        if (!$name && !empty($card->translations)) {
            $name = $card->translations[0]['name'];
        }

        if (!$name) {
            return CardResource::collection([]);
        }

        // 2. Find other cards with the same name
        $variants = \App\Models\Card::where('card_id', '!=', $card->id)
            ->whereHas('translations', function ($q) use ($name) {
                $q->where('name', $name);
            })
            ->with(['translations'])
            ->get();

        // Convert Eloquent models to DTOs so we can reuse CardResource? 
        // Or just map them manually since CardResource expects a DTO?
        // CardResource expects GrandLineCards\Catalog\Card\Application\Find\CardResponse
        // But checking CardResource code:
        // $card = $this->resource; 
        // It doesn't strictly type check against the class, but it accesses properties like public properties ($card->id).
        // Eloquent models have array access but property access works differently.
        // CardResource access: $card->id, $card->translations (which is a relation on model)
        
        // Let's coerce the Eloquent models into the structure CardResource expects, 
        // or better, let's just make a simple array mapping for variants in the frontend or use a Lightweight resource.
        // Reusing CardResource is risky if it expects the specific DTO class features.
        // Let's look at CardResource again. It accesses:
        // $card->id, $card->translations[0]['name'], $card->imageUrl...
        
        // If I pass an Eloquent model to CardResource:
        // $card->id works.
        // $card->translations is a Collection. $card->translations[0] might work if converted to array or accessed as object.
        // CardResource does: $card->translations[0]['name'].
        // Eloquent collection access by index [0] works and returns a Model. Model['name'] works.
        // So passing Eloquent model to CardResource might mostly work, BUT:
        // $card->type, $card->color directly on model works.
        // $card->expansionId -> Model uses expansion_id (snake_case) usually, but check Card model.
        // Card model: expansion_id. DTO: expansionId.
        // CardResource uses: 'set_name' => $card->expansionId.
        // This will FAIL with Eloquent model.
        
        // Solution: Map Eloquent models to the DTO structure or create a VariantResource.
        // I will map to DTO to reuse CardResource logic.

        $variantDTOs = $variants->map(function ($variant) {
            return new \GrandLineCards\Catalog\Card\Application\Find\CardResponse(
                id: $variant->card_id,
                expansionId: (string) $variant->expansion_id, // Cast just in case
                color: $variant->color,
                type: $variant->type,
                rarity: $variant->rarity,
                cost: $variant->cost,
                power: $variant->power,
                counter: $variant->counter,
                life: $variant->life,
                imageUrl: $variant->image_url,
                attributes: $variant->attributes ?? [],
                translations: $variant->translations->toArray()
            );
        });

        return CardResource::collection($variantDTOs);
    }
}
