<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first(); 
        // Ensure Categories exist
        $catMeta = Category::firstOrCreate(['slug' => 'meta'], ['name' => 'Meta Analysis']);
        $catGuide = Category::firstOrCreate(['slug' => 'guides'], ['name' => 'Guides']);
        $catNews = Category::firstOrCreate(['slug' => 'news'], ['name' => 'News']);
        
        $articles = [
            [
                'title' => 'Meta Analysis: Sakazuki Dominance',
                'cat' => $catMeta->id,
                'img' => '/images/news/sakazuki.jpg'
            ],
            // ... I will generate the titles dynamically below to save space here
        ];

        $titles = [
            ['Top 10 Budget Decks for OP-06', $catGuide->id],
            ['Full Spoiler List: Memorial Collection', $catNews->id],
            ['Card Spotlight: Roronoa Zoro Rush', $catGuide->id],
            ['The Art of Mulligans', $catGuide->id],
            ['Gecko Moria: The New King?', $catMeta->id],
            ['Understanding Triggers', $catGuide->id],
            ['2026 Roadmap Revealed', $catNews->id],
            ['Best Custom Playmats', $catNews->id],
            ['Interview with World Champion', $catNews->id],
            ['Why Red/Purple Law is Tier 1', $catMeta->id],
            ['Belo Betty Aggro Guide', $catGuide->id],
            ['Defensive Strategies', $catGuide->id],
            ['Market Watch: Nami Alt Art', $catNews->id],
            ['Official Rulings Update', $catNews->id],
            ['Store Championship Prep', $catGuide->id],
            ['Spotting Fake Cards', $catGuide->id],
            ['Lore: Wano Arc', $catNews->id],
            ['Enel Control Viability', $catMeta->id],
            ['Deck Profile: Perona', $catMeta->id],
            ['Pre-Release Guide', $catGuide->id]
        ];

        foreach ($titles as $index => $data) {
            $title = $data[0];
            $catId = $data[1];
            
            // Create Post (Base)
            $post = Post::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'category_id' => $catId,
                    // 'user_id' => $admin->id ?? 1, // Deleted column
                    'status' => 'published',
                    'published_at' => now()->subDays($index * 2), // Spread out over time
                    'image_path' => '/images/card-back.png', 
                ]
            );

            // Create Translation if not exists
            if ($post->translations()->where('locale', 'es')->doesntExist()) {
                $post->translations()->create([
                    'locale' => 'es',
                    'title' => $title,
                    'excerpt' => "Descubre todo sobre \"$title\". Analizamos estrategias, precios y mecánicas de juego.",
                    'content' => $this->generateSeoContent($title),
                ]);
            }
        }
    }

    private function generateSeoContent($title)
    {
        return "
            <h2>Introducción</h2>
            <p>Bienvenido a nuestro análisis profundo sobre <strong>$title</strong>. En el mundo evolutivo de One Piece TCG, mantenerse a la vanguardia es esencial.</p>
            <h2>Análisis Estratégico</h2>
            <p>Ya seas un coleccionista o un jugador competitivo, entender los matices de $title es clave.</p>
            <h3>Puntos Clave</h3>
            <ul>
                <li>Dominar los conceptos básicos.</li>
                <li>Técnicas avanzadas para veteranos.</li>
                <li>Implicaciones de mercado y tendencias.</li>
            </ul>
            <h2>Conclusión</h2>
            <p>Gracias por leer nuestra guía sobre $title. Sigue atento a Grand Line Cards para más actualizaciones.</p>
        ";
    }
}
