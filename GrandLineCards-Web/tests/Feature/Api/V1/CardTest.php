<?php

namespace Tests\Feature\Api\V1;

use App\Models\Card;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CardTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_cards()
    {
        Card::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/cards');
        

        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'type',
                        'color',
                        // Add other fields as necessary
                    ]
                ]
            ]);
    }

    public function test_can_filter_cards_by_name()
    {
        $card1 = Card::factory()->create(['card_id' => 'OP01-001']);
        $card1->translations()->create(['locale' => 'en', 'name' => 'Luffy', 'effect_text' => 'Effect']);

        $card2 = Card::factory()->create(['card_id' => 'OP01-002']);
        $card2->translations()->create(['locale' => 'en', 'name' => 'Zoro', 'effect_text' => 'Effect']);

        // The Factory 'afterCreating' might have added 'es' translation 'Luffy Test'.
        // We ensure we search for 'Luffy' and it matches the EN one we just added.
        // OR we should clear translations first?
        // Note: Factory might create duplicate translations if not careful, but HasOne/HasMany defaults usually okay.
        
        $response = $this->getJson('/api/v1/cards?q=Luffy');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Luffy');
    }

    public function test_can_filter_cards_by_color()
    {
        Card::factory()->create(['color' => 'Red']);
        Card::factory()->create(['color' => 'Blue']);

        $response = $this->getJson('/api/v1/cards?color=Red');

        $response->assertStatus(200)
             ->assertJsonCount(1, 'data')
             ->assertJsonPath('data.0.color', 'Red');
    }

    public function test_can_show_card()
    {
        $this->withoutExceptionHandling();
        $card = Card::factory()->create(['card_id' => 'OP01-001']);

        $response = $this->getJson('/api/v1/cards/OP01-001');

        $response->assertStatus(200)
            ->assertJsonPath('id', 'OP01-001');
    }

    public function test_show_card_not_found()
    {
        $response = $this->getJson('/api/v1/cards/INVALID-ID');

        $response->assertStatus(404);
    }
}
