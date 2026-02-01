<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Card::query();

        if ($request->filled('q')) {
            $query->where('card_id', 'like', '%' . $request->q . '%')
                  ->orWhere('attributes', 'like', '%' . $request->q . '%'); // Very basic JSON search
        }

        if ($request->filled('expansion')) {
            $query->where('expansion_id', $request->expansion);
        }

        $cards = $query->paginate(20)->withQueryString();

        return Inertia::render('Admin/Catalog/Index', [
            'cards' => $cards,
            'filters' => $request->only(['q', 'expansion']),
        ]);
    }
}
