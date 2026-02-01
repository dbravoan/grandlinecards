<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardTranslation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TranslateController extends Controller
{
    public function index()
    {
        // Find next card pending translation (has EN but missing ES)
        // OR simply missing ES. Assuming all cards should have En.
        
        $card = Card::whereDoesntHave('translations', function ($query) {
            $query->where('locale', 'es');
        })->whereHas('translations', function ($query) {
             $query->where('locale', 'en');
        })->first();

        if (!$card) {
            // If all translated, go to lists or show message
            // For now, redirect to dashboard with message? Or just list.
            return to_route('admin.dashboard')->with('success', 'All cards translated!');
        }

        return to_route('admin.translate.edit', $card->id);
    }

    public function edit(Card $card)
    {
        $card->load(['translations' => function ($q) {
            $q->whereIn('locale', ['en', 'es']);
        }]);

        $en = $card->translations->firstWhere('locale', 'en');
        $es = $card->translations->firstWhere('locale', 'es');

        return Inertia::render('Admin/Translations/Burst', [
            'card' => $card,
            'original' => $en,
            'translation' => $es ?? ['name' => '', 'effect_text' => '', 'trigger_text' => '', 'notes' => ''],
        ]);
    }

    public function update(Request $request, Card $card)
    {
        // Check if this is an AI generation request (mocking query param or body)
        if ($request->boolean('ai')) {
             try {
                $action = app(\Src\Catalog\Application\TranslateCardAction::class);
                $action->execute($card->card_id, force: true);
                
                return back()->with('success', 'AI Translation generated!');
             } catch (\Exception $e) {
                return back()->with('error', 'AI Error: ' . $e->getMessage());
             }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'effect_text' => 'nullable|string',
            'trigger_text' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $action = app(\Src\Catalog\Application\SaveTranslationAction::class);
        $action->execute($card->id, 'es', $validated);

        return to_route('admin.translate.index')->with('success', 'Saved & Verified!');
    }
}
