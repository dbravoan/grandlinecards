<?php

namespace App\Http\Controllers\Web\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    public function index(Request $request): Response
    {
        // 1. Sealed Products (Mocked for now)
        $sealed = \Illuminate\Support\Facades\Cache::remember('shop.sealed_products', 3600, function () {
            return [
                [
                    'id' => 'prod_1',
                    'name' => 'Romance Dawn (OP-01) Booster Box',
                    'description' => 'Caja sellada con 24 sobres de la primera colección.',
                    'price' => 120.00,
                    'image_url' => 'https://en.onepiece-cardgame.com/images/products/boosters/op01/img_package_01.png',
                    'category' => 'sealed'
                ],
                // Add more mock sealed products here if desired
            ];
        });

        // 2. Singles Filters
        $query = \App\Models\MarketListing::where('status', 'active');

        // Filter by Card Name (Search)
        if ($request->has('q') && $request->filled('q')) {
            $search = $request->get('q');
            $query->whereHas('card.translations', function ($q) use ($search) {
                // Check name in any locale (or specifically ES/EN)
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by Attributes (Color, Rarity)
        if ($request->has('color') || $request->has('rarity') || $request->has('set')) {
            $query->whereHas('card', function ($q) use ($request) {
                if ($request->has('color') && $request->filled('color')) {
                    $q->where('color', 'like', "%{$request->get('color')}%"); // Like for "Read/Green" dual
                }
                if ($request->has('rarity') && $request->filled('rarity')) {
                     $q->where('rarity', $request->get('rarity'));
                }
                // Expansion Filter requires joining expansions or checking card_id prefix if simplified
                if ($request->has('set') && $request->filled('set')) {
                    $q->where('card_id', 'like', "{$request->get('set')}-%");
                }
            });
        }

        // 3. Aggregate Stats
        $singlesStats = $query->select('card_id', \Illuminate\Support\Facades\DB::raw('MIN(price) as min_price'), \Illuminate\Support\Facades\DB::raw('COUNT(*) as listing_count'))
            ->groupBy('card_id')
            ->orderByDesc('listing_count')
            ->paginate(20); 

        // $singlesStats is now a Paginator. 
        // We cannot use ->get() on it. It serves as the collection wrapper.

        // Hydrate
        $cardIds = $singlesStats->pluck('card_id');
        $cards = \App\Models\Card::whereIn('card_id', $cardIds)
            ->with('translations') // Fix N+1
            ->get()
            ->keyBy('card_id');

        $singles = $singlesStats->map(function ($stat) use ($cards) {
            $card = $cards->get($stat->card_id);
            if (!$card) return null;

            return [
                'id' => $card->card_id,
                'name' => $card->translations[0]['name'] ?? 'Unknown',
                'description' => ($card->rarity ?? '-') . ' - ' . ($card->type ?? 'Card'),
                'min_price' => $stat->min_price,
                'listing_count' => $stat->listing_count,
                'image_url' => $card->imageUrl 
                            ? (str_starts_with($card->imageUrl, 'http') ? $card->imageUrl : '/storage/' . $card->imageUrl)
                            : '/images/card-back.png',
                'category' => 'single'
            ];
        })->filter()->values();

        return Inertia::render('Shop/Index', [
            'sealed' => $sealed,
            'singles' => \App\Http\Resources\CardResource::collection($singles), // Just wrapping array? No, singles is a collection of arrays now.
            // Wait, $singles in line 77 was a map() on $singlesStats items.
            // $singlesStats is a Paginator. map() on Paginator returns a Collection (not Paginator).
            // So we lost pagination meta.
            
            // To preserve pagination while transforming items:
            'singles' => $singlesStats->setCollection($singles), 
            // In Laravel Paginator, setCollection replaces the items but keeps meta.
            
            'filters' => $request->only(['q', 'color', 'rarity', 'set']),
        ]);
    }
}
