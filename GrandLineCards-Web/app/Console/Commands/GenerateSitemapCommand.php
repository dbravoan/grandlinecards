<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Card;
use App\Models\Expansion;
use Illuminate\Support\Str;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml';

    public function handle()
    {
        $this->info('Generating sitemap...');

        $sitemap = Sitemap::create();

        // 1. Static Pages
        $sitemap->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));
        $sitemap->add(Url::create('/tienda')->setPriority(0.8));
        $sitemap->add(Url::create('/academia')->setPriority(0.8));
        $sitemap->add(Url::create('/reglas')->setPriority(0.7));
        $sitemap->add(Url::create('/cartas')->setPriority(0.9));

        // 2. Smart URLs (Filters)
        
        // Sets
        $expansions = Expansion::all();
        foreach ($expansions as $exp) {
            // Slug: op01
            $slug = Str::slug($exp->code);
            $sitemap->add(Url::create("/cartas/{$slug}")->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        }

        // Colors
        $colors = ['rojo', 'verde', 'azul', 'morado', 'negro', 'amarillo'];
        foreach ($colors as $color) {
            $sitemap->add(Url::create("/cartas/{$color}")->setPriority(0.8));
        }
        
        // Rarities
        $rarities = ['lider', 'comun', 'infrecuente', 'rara', 'super-rara', 'secreta', 'promo'];
        foreach ($rarities as $rarity) {
             $sitemap->add(Url::create("/cartas/{$rarity}")->setPriority(0.7));
        }

        // 3. Combinations (Set + Color) - High SEO Value
        $this->info('Adding Set + Color combinations...');
        foreach ($expansions as $exp) {
            $setSlug = Str::slug($exp->code);
            foreach ($colors as $color) {
                // url: /cartas/op01/rojo
                $sitemap->add(Url::create("/cartas/{$setSlug}/{$color}")
                    ->setPriority(0.75)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
            }
        }

        // 3. Cards using Query/Cursor for memory efficiency
        $this->info('Adding cards...');
        Card::orderBy('id')->chunk(500, function ($cards) use ($sitemap) {
            foreach ($cards as $card) {
                // Priority depends on rarity? maybe.
                $sitemap->add(Url::create("/cartas/{$card->card_id}")
                    ->setLastModificationDate($card->updated_at)
                    ->setPriority(0.6));
            }
        });

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $this->info("Sitemap generated at {$path}");
    }
}
