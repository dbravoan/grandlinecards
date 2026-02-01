<?php
declare(strict_types=1);
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \GrandLineCards\Catalog\Card\Domain\CardRepository::class,
            \GrandLineCards\Catalog\Card\Infrastructure\Persistence\Eloquent\EloquentCardRepository::class
        );
        $this->app->bind(
            \GrandLineCards\DeckBuilder\Deck\Domain\DeckRepository::class,
            \GrandLineCards\DeckBuilder\Deck\Infrastructure\Persistence\Eloquent\EloquentDeckRepository::class
        );
    }
}
