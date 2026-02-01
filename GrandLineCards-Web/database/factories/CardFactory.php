<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'card_id' => 'OP0' . $this->faker->numberBetween(1, 9) . '-' . str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
            // We assume an Expansion exists or we create one.
            // Since we don't have ExpansionFactory yet, let's create it on fly or depend on seeder?
            // Safer: Create an expansion instance if none provided.
            'expansion_id' => \App\Models\Expansion::factory(),
            'type' => 'Character',
            'cost' => 5,
            'power' => 5000,
            'life' => null,
            'attributes' => json_encode(['Strike']), // JSON column
            'color' => 'Red',
            'rarity' => 'C',
            'image_url' => 'cards/OP01/OP01-001.png',
            // 'is_alternative_art' removed as it's not in schema
        ];
    }
    
    public function configure()
    {
        return $this->afterCreating(function (\App\Models\Card $card) {
            $card->translations()->create([
                'locale' => 'es', 
                'name' => 'Test Card',
                'effect_text' => 'Effect text',
            ]);
        });
    }
}
