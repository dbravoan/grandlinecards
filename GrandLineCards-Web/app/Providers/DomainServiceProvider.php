<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Catalog\Cards\Domain\CardRepositoryInterface;
use Src\Catalog\Cards\Infrastructure\EloquentCardRepository;
use Src\Community\Decks\Domain\DeckRepositoryInterface;
use Src\Community\Decks\Infrastructure\EloquentDeckRepository;

class DomainServiceProvider extends ServiceProvider
{
    public $bindings = [
        CardRepositoryInterface::class => EloquentCardRepository::class,
        DeckRepositoryInterface::class => EloquentDeckRepository::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        //
    }
}
