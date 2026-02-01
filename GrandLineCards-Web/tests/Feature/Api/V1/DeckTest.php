<?php

namespace Tests\Feature\Api\V1;

use App\Models\Deck;
use App\Models\User;
use App\Models\Card;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeckTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_public_decks()
    {
        Deck::factory()->count(3)->create(['is_public' => true]);
        Deck::factory()->count(2)->create(['is_public' => false]);

        $response = $this->getJson('/api/v1/decks');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_deck()
    {
        $user = User::factory()->create();
        $leader = Card::factory()->create(['type' => 'Leader', 'card_id' => 'OP01-001']);
        $card1 = Card::factory()->create(['card_id' => 'OP01-002']);

        $deckData = [
            'name' => 'My Awesome Deck',
            'leader_id' => $leader->card_id,
            'is_public' => true,
            'cards' => [
                ['id' => $card1->card_id, 'quantity' => 4],
            ]
        ];

        Passport::actingAs($user);
        
        $response = $this->postJson('/api/v1/decks', $deckData);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id']]);

        $this->assertDatabaseHas('decks', [
            'name' => 'My Awesome Deck',
            'leader_id' => $leader->card_id,
        ]);
    }

    public function test_create_deck_validation_error()
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $response = $this->postJson('/api/v1/decks', [
            'name' => '', // Invalid
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'leader_id', 'cards']);
    }
}
