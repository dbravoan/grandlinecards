<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenAI;
use OpenAI\Client;
use Src\Catalog\Ingestion\Domain\Contracts\CardSourceInterface;
use Src\Catalog\Ingestion\Infrastructure\Sources\OfficialSiteScraper;
use Src\Catalog\Ingestion\Infrastructure\Services\OpenAITranslator;

class IngestionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind OpenAI Client
        $this->app->singleton(Client::class, function () {
            $apiKey = config('services.openai.key') ?? env('OPENAI_API_KEY') ?? 'sk-placeholder';
            return OpenAI::client($apiKey);
        });

        // Bind the default Card Source (can be contextual)
        $this->app->bind(CardSourceInterface::class, OfficialSiteScraper::class);
        
        // Ensure Intervetion Image config or drivers if needed (usually auto-discovered)
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
