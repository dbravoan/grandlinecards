<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Src\Catalog\Application\UpdateInventoryAction;

class InventoryController extends Controller
{
    public function index()
    {
        $cards = Card::with(['prices'])->paginate(20);

        return Inertia::render('Admin/Inventory', [
            'cards' => $cards
        ]);
    }

    public function update(Request $request, Card $card, UpdateInventoryAction $action)
    {
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $action->execute($card->id, $validated['price'], $validated['stock']);

        return back()->with('success', 'Inventory updated successfully.');
    }
}
