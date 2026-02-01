<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Card;
use Illuminate\Support\Facades\Storage;

class AuditDatabaseCommand extends Command
{
    protected $signature = 'db:audit';
    protected $description = 'Audit database for missing images and translations';

    public function handle()
    {
        $this->info("🔍 Starting Database Audit...");

        $totalCards = Card::count();
        $this->info("Total Cards: {$totalCards}");

        $missingImages = 0;
        $missingTranslations = 0;

        $bar = $this->output->createProgressBar($totalCards);
        $bar->start();

        foreach (Card::cursor() as $card) {
            // Check Image
            $imagePath = str_replace('/storage/', '', $card->image_url);
            if (!$card->image_url || !Storage::disk('public')->exists($imagePath)) {
                $missingImages++;
            }

            // Check Translation
            if (!$card->translations()->where('locale', 'es')->exists()) {
                $missingTranslations++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Count', 'Status'],
            [
                ['Missing Images', $missingImages, $missingImages > 0 ? '❌ ACTION REQUIRED' : '✅'],
                ['Missing Translations (ES)', $missingTranslations, $missingTranslations > 0 ? '❌ ACTION REQUIRED' : '✅'],
            ]
        );

        return Command::SUCCESS;
    }
}
