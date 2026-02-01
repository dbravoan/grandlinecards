<?php

namespace Src\Catalog\Ingestion\Application\Actions;

use App\Models\Card;
use App\Models\Expansion;
use Src\Catalog\Ingestion\Domain\DTOs\RawCardData;
use Src\Catalog\Ingestion\Infrastructure\Services\InterventionImageProcessor;
use Src\Catalog\Ingestion\Infrastructure\Services\OpenAITranslator;
use Illuminate\Support\Facades\Log;

class IngestCardAction
{
    public function __construct(
        private InterventionImageProcessor $imageProcessor,
        private OpenAITranslator $translator
    ) {}

    public function execute(RawCardData $data, bool $force = false): void
    {
        // 1. Ensure Expansion Exists
        $expansion = Expansion::firstOrCreate(
            ['code' => $data->setId],
            [
                'name' => $data->setId, // Placeholder until we have full set names or scrape them
                'release_date' => now(), // Placeholder
            ]
        );

        // 2. Find or Init Card
        $card = Card::firstOrNew(['card_id' => $data->id]);
        
        // Check if we need to update
        if ($card->exists && !$force) {
            // Minimal update (price? but price is separate)
            // For now, if it exists and not force, we might skip deep processing
            // But let's verify if image/translation is missing
        }

        // 3. Update Core Data
        $card->expansion_id = $expansion->id;
        $card->color = $data->color;
        $card->type = $data->type;
        $card->attributes = $data->attributes;
        $card->cost = $data->cost;
        $card->power = $data->power;
        $card->counter = $data->counter;
        $card->life = $data->life;
        $card->rarity = $data->rarity;
        
        // 4. Handle Image
        Log::info("Processing Card {$data->id}. ImageURL: " . ($data->imageUrl ?: 'EMPTY'));
        if ($data->imageUrl && ($force || !$card->image_url)) {
            try {
                $localPath = $this->imageProcessor->process($data->imageUrl, $data->id);
                $card->image_url = $localPath;
            } catch (\Exception $e) {
                Log::error("Failed to process image for {$data->id}: " . $e->getMessage());
                // Keep the remote URL or leave null? 
                // We'll leave it as is or log it.
            }
        }

        $card->save();

        // 5. Handle Translation
        // 5. Handle Translation
        // First, ensure 'en' translation exists (Source of Truth)
        $card->translations()->updateOrCreate(
            ['locale' => 'en'],
            [
                'name' => $data->name,
                'slug' => \Illuminate\Support\Str::slug($data->id . '-' . $data->name),
                'effect_text' => $data->effectText,
                'keywords' => [], // English keywords if available in raw data
                'notes' => null,
                'trigger_text' => null, 
            ]
        );

        $existingTranslation = $card->translation('es')->first();
        if ($force || !$existingTranslation) {
            try {
                $translationData = $this->translator->translate($data->name, $data->effectText ?? '');
                
                $card->translations()->updateOrCreate(
                    ['locale' => 'es'],
                    [
                        'name' => $translationData['name_es'] ?? $data->name, // Fallback to EN name
                        'slug' => \Illuminate\Support\Str::slug($data->id . '-' . ($translationData['name_es'] ?? $data->name)),
                        'effect_text' => $translationData['effect_es'] ?? null,
                        'keywords' => $translationData['keywords'] ?? [],
                        'notes' => null, // Manual notes not scraped
                        'trigger_text' => null,
                    ]
                );
            } catch (\Throwable $e) {
                Log::error("Failed to translate {$data->id}: " . $e->getMessage());
            }
        }
    }
}
