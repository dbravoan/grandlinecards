<?php

namespace App\Http\Controllers\Web\Front\Profile;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CollectionItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $collection = CollectionItem::with(['card.translations'])
            ->where('user_id', $user->id)
            ->get()
            ->groupBy('card.expansion_code'); 
            // Simple grouping for now, can be enhanced with filters later

        return Inertia::render('Profile/Collection/Index', [
            'collection' => $collection,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'card_id' => 'required|string|exists:cards,card_id',
            'quantity' => 'required|integer|min:0',
            'is_foil' => 'boolean',
        ]);

        $user = Auth::user();
        // Resolve Card ID (string) to internal ID (int) if needed?
        // Wait, database uses card_id? 
        // Migration: $table->foreignId('card_id')->constrained('cards');
        // 'cards' table usually has primary key 'id' (int). 'card_id' is string "OP01-001".
        // Let's check Card model PK. Usually id.
        
        $card = Card::where('card_id', $request->card_id)->firstOrFail();

        if ($request->quantity > 0) {
            $item = CollectionItem::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'card_id' => $card->id,
                    'is_foil' => $request->boolean('is_foil'),
                ],
                [
                    'quantity' => $request->quantity
                ]
            );
            $message = 'Colección actualizada.';
        } else {
            // Remove item
            CollectionItem::where([
                'user_id' => $user->id,
                'card_id' => $card->id,
                'is_foil' => $request->boolean('is_foil'),
            ])->delete();
            $message = 'Carta eliminada de la colección.';
        }

        return back()->with('success', $message);
    }
}
