<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Src\Catalog\Ingestion\Application\Actions\IngestCardAction;
use Src\Catalog\Ingestion\Infrastructure\Sources\JsonFileSource;

class ImportJsonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cards:import-json {file : The path to the JSON file to import} {--force : Force update existing cards}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import cards from a JSON file (produced by Python scraper)';

    /**
     * Execute the console command.
     */
    public function handle(IngestCardAction $ingestAction)
    {
        $filePath = $this->argument('file');
        $force = $this->option('force');

        if (!file_exists($filePath)) {
            $this->error("File not found: $filePath");
            return Command::FAILURE;
        }

        $this->info("Loading data from $filePath...");
        
        try {
            $source = new JsonFileSource($filePath);
            $sets = $source->getAvailableSets();
            
            $this->info("Found " . count($sets) . " sets in file.");
            
            foreach ($sets as $setCode) {
                $this->info("Processing Set: $setCode");
                $cards = $source->fetchBySet($setCode);
                
                $bar = $this->output->createProgressBar(count($cards));
                $bar->start();
                
                foreach ($cards as $cardData) {
                    $ingestAction->execute($cardData, $force);
                    $bar->advance();
                }
                
                $bar->finish();
                $this->newLine();
            }
            
            $this->info("Import completed successfully.");
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error("Error during import: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
