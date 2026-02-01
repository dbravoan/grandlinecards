<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Card;
use App\Services\Translation\AutoTranslator;

class TranslateCardsCommand extends Command
{
    protected $signature = 'cards:translate {--limit=1000} {--force}';
    protected $description = 'Auto-translate cards to Spanish using rule-based engine';

    public function handle(AutoTranslator $translator)
    {
        $limit = $this->option('limit');
        $force = $this->option('force');

        $this->info('Starting translation process...');

        $query = Card::query();
        
        if (!$force) {
            // Only cards MISSING es translation
            $query->whereDoesntHave('translations', function ($q) {
                $q->where('locale', 'es');
            });
        }
        
        $cards = $query->limit($limit)->get();

        $count = $cards->count();
        $this->info("Found {$count} cards to translate.");
        
        if ($count === 0) {
            return;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        foreach ($cards as $card) {
            // Get source (EN)
            $source = $card->translations()->where('locale', 'en')->first();
            
            // If no EN translation, try to use Card model fallback if columns exist, or skip
            // Note: Card table usually has base columns? No, they are in translations mainly. 
            // Checking Card Model earlier: it does NOT have 'name' or 'effect_text' in fillable/guarded, 
            // implies they are in translations table.
            
            if (!$source) {
                // Try to find ANY translation? or just skip
                $source = $card->translations()->first();
            }

            if (!$source) {
                // Log warning and continue
                // $this->warn("Card {$card->card_id} has no source translation.");
                $bar->advance();
                continue;
            }

            $translatedEffect = $translator->translateEffect($source->effect_text ?? '');
            
            // Note: Currently we don't translate Name heavily, but we can later.
            $translatedName = $translator->translateName($source->name); 

            // Update or Create ES
            $translation = $card->translations()->firstOrNew(['locale' => 'es']);
            $translation->name = $translatedName;
            $translation->effect_text = $translatedEffect;
            // Also copy other fields
            $translation->trigger_text = $translator->translateEffect($source->trigger_text ?? '');
            $translation->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Translation complete.');
    }
}
