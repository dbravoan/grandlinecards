<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Src\Catalog\Ingestion\Application\Actions\IngestCardAction;
use Src\Catalog\Ingestion\Domain\Contracts\CardSourceInterface;
use Src\Catalog\Ingestion\Infrastructure\Sources\OfficialSiteScraper;

class ImportCardsCommand extends Command
{
    protected $signature = 'cards:import {--source=official : The source to scrape} {--set= : Specific set code e.g. OP01} {--force : Force update}';
    protected $description = 'Import One Piece TCG cards using Clean Architecture Ingestor';

    public function handle(
        \Src\Catalog\Ingestion\Infrastructure\Factories\CardSourceFactory $factory,
        \Src\Catalog\Ingestion\Application\Services\IngestionService $service
    ) {
        $sourceName = $this->option('source');
        $setCode = $this->option('set');
        $force = (bool) $this->option('force');

        $this->info("🚀 Starting ingestion from [{$sourceName}]...");

        try {
            // 1. Resolve Source
            $source = $factory->make($sourceName);

            // 2. Delegate to Service
            $service->run($source, $setCode, $force, $this->output);
            
            $this->info("✅ Import completed!");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Fatal Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
