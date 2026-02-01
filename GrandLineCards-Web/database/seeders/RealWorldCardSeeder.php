<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RealWorldCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Sets Exists
        $sets = ['OP01', 'OP02', 'OP03', 'ST01'];
        foreach ($sets as $set) {
            DB::table('expansions')->updateOrInsert(
                ['code' => $set],
                ['name' => "Expansion $set", 'release_date' => now()]
            );
        }

        // 2. Seed Leaders (Essential for Deck Building)
        $leaders = [
            [
                'card_id' => 'OP01-001',
                'name' => 'Roronoa Zoro',
                'type' => 'Leader', // Important: Type validation
                'color' => 'Red',
                'power' => 5000,
                'life' => 5,
                'attributes' => json_encode(['Supernovas', 'Straw Hat Crew']),
                'expansion_id' => 'OP01',
                'effect_en' => '[Your Turn] Give all your Characters +1000 Power.',
                'image_url' => 'https://en.onepiece-cardgame.com/images/cardlist/card/OP01-001.png',
            ],
            [
                'card_id' => 'OP01-002',
                'name' => 'Trafalgar Law',
                'type' => 'Leader',
                'color' => 'Red/Green', // Dual Color
                'power' => 5000,
                'life' => 4,
                'attributes' => json_encode(['Supernovas', 'Heart Pirates']),
                'expansion_id' => 'OP01',
                'effect_en' => '[Activate: Main] ... switch ...',
                'image_url' => 'https://en.onepiece-cardgame.com/images/cardlist/card/OP01-002.png',
            ],
            [
                'card_id' => 'OP01-003',
                'name' => 'Monkey D. Luffy',
                'type' => 'Leader',
                'color' => 'Red',
                'power' => 5000,
                'life' => 5,
                'attributes' => json_encode(['Supernovas', 'Straw Hat Crew']),
                'expansion_id' => 'OP01',
                'effect_en' => '[Activate: Main] Give this Leader +1000 Power.',
                'image_url' => 'https://en.onepiece-cardgame.com/images/cardlist/card/OP01-003.png',
            ]
        ];

        // 3. Seed Playable Cards (To form a deck)
        $cards = [];
        // Generate enough generic cards to fill a deck for testing
        for ($i = 4; $i <= 60; $i++) {
            $num = str_pad($i, 3, '0', STR_PAD_LEFT);
            $cards[] = [
                'card_id' => "OP01-$num",
                'name' => "Straw Hat Member #$i",
                'type' => 'Character',
                'color' => 'Red', // Matching Zoro/Luffy
                'power' => 3000,
                'counter' => 1000,
                'expansion_id' => 'OP01',
                'attributes' => json_encode(['Straw Hat Crew']),
                'effect_en' => 'Vanilla effect.',
                'image_url' => "https://en.onepiece-cardgame.com/images/cardlist/card/OP01-$num.png",
                'rarity' => 'C'
            ];
        }

        // Add a Green card to test color validation failure with Red Leader
        $cards[] = [
            'card_id' => "OP01-099",
            'name' => "Isolated Green Unit",
            'type' => 'Character',
            'color' => 'Green',
            'power' => 3000,
            'expansion_id' => 'OP01',
            'attributes' => json_encode(['Land of Wano']),
            'effect_en' => '-',
            'image_url' => "https://en.onepiece-cardgame.com/images/cardlist/card/OP01-099.png",
            'rarity' => 'C'
        ];

        // Insert
        foreach (array_merge($leaders, $cards) as $data) {
            DB::table('cards')->updateOrInsert(
                ['card_id' => $data['card_id']],
                $data
            );
        }
    }
}
