<?php

namespace Src\Catalog\Application;

use App\Models\Card;
use App\Models\CardTranslation;
use App\Services\OpenAITranslator;
use Illuminate\Support\Str;

class TranslateCardAction
{
    public function __construct(
        private OpenAITranslator $translator
    ) {}

    public function execute(string $cardId, bool $force = false): CardTranslation
    {
        $card = Card::where('card_id', $cardId)->firstOrFail();
        
        // Check existing Spanish translation
        $translation = $card->translations()->where('locale', 'es')->first();

        if ($translation && !$force && $translation->is_verified) {
            return $translation;
        }

        // Get Original (EN)
        $original = $card->translations()->where('locale', 'en')->first();

        if (!$original) {
            throw new \Exception("Cannot translate card {$cardId}: No English source found.");
        }

        // 1. Pre-processing: Apply simple glossary replacements to source text for better context?
        // Actually, better to let AI handle it via prompt, but we can verify later.
        
        // 2. AI Translation
        $translatedName = $this->translator->translate($original->name);
        $translatedEffect = $this->translator->translate($original->effect_text);
        $translatedTrigger = $original->trigger_text ? $this->translator->translate($original->trigger_text) : null;

        // 3. Save or Update
        return CardTranslation::updateOrCreate(
            ['card_id' => $card->id, 'locale' => 'es'],
            [
                'name' => $translatedName,
                'effect_text' => $translatedEffect,
                'trigger_text' => $translatedTrigger,
                'is_verified' => false, // Always requires human verification after AI
            ]
        );
    }
}
