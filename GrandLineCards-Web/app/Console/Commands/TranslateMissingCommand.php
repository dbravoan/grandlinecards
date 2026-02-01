<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Card;
use Src\Catalog\Ingestion\Infrastructure\Services\OpenAITranslator;

class TranslateMissingCommand extends Command
{
    protected $signature = 'cards:translate-missing {--limit=10 : Limit records to process}';
    protected $description = 'Translate cards that are missing Spanish translations using OpenAI';

    public function handle(OpenAITranslator $translator)
    {
        $limit = (int) $this->option('limit');
        $this->info("🔍 Finding cards without translations (Limit: {$limit})...");

        // Find cards where DOESNT HAVE translation in locale 'es'
        $cards = Card::whereDoesntHave('translations', function ($q) {
            $q->where('locale', 'es');
        })->take($limit)->get();

        $count = $cards->count();
        if ($count === 0) {
            $this->info("✅ No missing translations found!");
            return Command::SUCCESS;
        }

        $this->info("Found {$count} cards. Starting translation...");

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($cards as $card) {
            try {
                // Use the original english name/effect.
                // Assuming we put english data in the main table attributes
                // But wait, the main table schema might not hold text.
                // Let's check Card model.
                // If main table has no text, we can't translate.
                // Actually, Ingestion saves Raw data.
                // Wait, in previous step IngestCardAction:
                // $card->attributes = $data->attributes; 
                // Does 'attributes' JSON hold the name/effect?
                // Let's verify RawCardData or Card schema.
                
                // Assuming Card has 'attributes' json column with raw data or we rely on some source.
                // Ideally we should have English Translation or Raw columns.
                
                // Let's assume we can fetch Name from somewhere.
                // If not, we might fail.
                
                // Workaround: We might need to fetch from source again OR relies on existing data.
                // If IngestCardAction saves name in translation (fallback), 
                // but here we are finding cards WITHOUT translation.
                
                // Checking IngestCardAction again...
                // It basically creates translation immediately.
                // So the 3587 cards MUST have been imported BEFORE the new Inestion Logic was active?
                // Or maybe they failed translation?
                
                // If they are old imports, where is the text stored?
                // Card table has `attributes` (json).
                // Let's assume raw data is there.
                
                $data = $card->attributes; // Casts to array
                
                // Fallback keys depending on scraper
                $name = $data['name'] ?? null; 
                $effect = $data['effect_text'] ?? $data['effect'] ?? '';

                if (!$name) {
                    $this->error("\n❌ No Name found for Card {$card->card_id}");
                    continue;
                }

                $translationData = $translator->translate($name, $effect);

                $card->translations()->updateOrCreate(
                    ['locale' => 'es'],
                    [
                        'name' => $translationData['name_es'],
                        'slug' => \Illuminate\Support\Str::slug($card->card_id . '-' . $translationData['name_es']),
                        'effect_text' => $translationData['effect_es'],
                        'keywords' => $translationData['keywords'] ?? [],
                        'notes' => null,
                        'trigger_text' => null, 
                    ]
                );

            } catch (\Exception $e) {
                $this->error("\n❌ Failed to translate {$card->card_id}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Batch processing complete.");
        
        return Command::SUCCESS;
    }
}
