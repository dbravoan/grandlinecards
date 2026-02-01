<?php

namespace Src\Catalog\Ingestion\Application\Services;

use Illuminate\Console\View\Components\Factory as ConsoleOutput;
use Src\Catalog\Ingestion\Application\Actions\IngestCardAction;
use Src\Catalog\Ingestion\Domain\Contracts\CardSourceInterface;
use Illuminate\Support\Facades\Log;

class IngestionService
{
    public function __construct(
        private IngestCardAction $ingestAction
    ) {}

    public function run(
        CardSourceInterface $source, 
        ?string $specificSetCode = null, 
        bool $force = false,
        $output = null
    ): void
    {
        // Determine sets
        $setsToProcess = [];
        if ($specificSetCode) {
            $setsToProcess[] = $specificSetCode;
        } else {
            if ($output) $output->info("🔄 No set specified. Fetching all available sets...");
            
            $availableSets = $source->getAvailableSets();
            
            // Normalize Map vs List
            if (array_keys($availableSets) !== range(0, count($availableSets) - 1)) {
                $setsToProcess = array_keys($availableSets);
            } else {
                $setsToProcess = $availableSets;
            }
            
            if ($output) $output->info("Found " . count($setsToProcess) . " sets to process.");
        }

        foreach ($setsToProcess as $currentSetCode) {
            if ($output) $output->info("📡 Fetching set: " . $currentSetCode);
            
            try {
                $cards = $source->fetchBySet($currentSetCode);
                $count = $cards->count();
                
                if ($output) {
                    $output->info("📦 Found {$count} cards. Processing...");
                    $bar = $output->createProgressBar($count);
                    $bar->start();
                }

                foreach ($cards as $rawCard) {
                    try {
                        $this->ingestAction->execute($rawCard, $force);
                    } catch (\Exception $e) {
                        $msg = "❌ Failed to ingest {$rawCard->id}: " . $e->getMessage();
                        Log::error($msg);
                        if ($output) $output->error("\n" . $msg);
                    }
                    if ($output) $bar->advance();
                }

                if ($output) {
                    $bar->finish();
                    $output->newLine();
                }
                
            } catch (\Exception $e) {
                $msg = "❌ Failed to fetch set {$currentSetCode}: " . $e->getMessage();
                Log::error($msg);
                if ($output) $output->error($msg);
            }
        }
    }
}
