<?php

namespace Database\Seeders;

use App\Models\Card;
use Illuminate\Database\Seeder;
use Src\Shared\Domain\ValueObjects\CardId;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure Expansions exist
        $expansionId = DB::table('expansions')->insertGetId([
            'code' => 'OP01',
            'name' => 'Romance Dawn',
            'release_date' => '2022-07-22'
        ]);

        $cards = [
            [
                'card_id' => 'OP01-001',
                'expansion_id' => $expansionId,
                'color' => 'Red',
                'type' => 'Leader',
                'attributes' => json_encode(['Supernovas', 'Straw Hat Crew']),
                'cost' => 5000, // For leader, cost is widely interpretted as Power sometimes or 0
                'power' => 5000,
                'counter' => null,
                'life' => 5,
                'rarity' => 'L',
                'image_url' => 'https://en.onepiece-cardgame.com/images/cardlist/card/OP01-001.png',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'card_id' => 'OP01-002',
                'expansion_id' => $expansionId,
                'color' => 'Red',
                'type' => 'Character',
                'attributes' => json_encode(['Straw Hat Crew']),
                'cost' => 1,
                'power' => 3000,
                'counter' => 1000,
                'life' => null,
                'rarity' => 'C',
                'image_url' => 'https://en.onepiece-cardgame.com/images/cardlist/card/OP01-002.png',
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'card_id' => 'OP01-003',
                'expansion_id' => $expansionId,
                'color' => 'Red',
                'type' => 'Character',
                'attributes' => json_encode(['Supernovas', 'Straw Hat Crew']),
                'cost' => 5,
                'power' => 6000,
                'counter' => null,
                'life' => null,
                'rarity' => 'SR',
                'image_url' => 'https://en.onepiece-cardgame.com/images/cardlist/card/OP01-003.png', // Zoro
                'created_at' => now(), 'updated_at' => now()
            ],
            [
                'card_id' => 'OP01-004', // Sanji
                'expansion_id' => $expansionId,
                'color' => 'Red',
                'type' => 'Character',
                'attributes' => json_encode(['Straw Hat Crew']),
                'cost' => 2,
                'power' => 4000,
                'counter' => 1000,
                'life' => null,
                'rarity' => 'R',
                'image_url' => 'https://en.onepiece-cardgame.com/images/cardlist/card/OP01-004.png',
                'created_at' => now(), 'updated_at' => now()
            ]
        ];

        DB::table('cards')->insertOrIgnore($cards);
        
        // Fetch IDs back to map translations correctly
        $insertedCards = DB::table('cards')->whereIn('card_id', array_column($cards, 'card_id'))->get()->keyBy('card_id');

        // Add Translations for them
        foreach($cards as $c) {
            $dbCard = $insertedCards->get($c['card_id']);
            if (!$dbCard) continue;

            DB::table('card_translations')->insertOrIgnore([
                'card_id' => $dbCard->id, 
                'locale' => 'es',
                'name' => match($c['card_id']) {
                   'OP01-001' => 'Monkey D. Luffy',
                   'OP01-002' => 'Usopp (Mentiroso)',
                   'OP01-003' => 'Roronoa Zoro',
                   'OP01-004' => 'Sanji',
                   default => 'Pirata Desconocido'
                },
                'effect_text' => 'Efecto simulado traducido al español...',
                'created_at' => now(), 'updated_at' => now()
            ]);
        }
    }
}
