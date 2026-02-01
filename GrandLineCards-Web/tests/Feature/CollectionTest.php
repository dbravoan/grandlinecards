<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\CollectionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_collection()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('profile.collection.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_add_card_to_collection()
    {
        $user = User::factory()->create();
        $card = Card::factory()->create(['card_id' => 'OP01-001']);
        
        $this->actingAs($user);

        $response = $this->post(route('profile.collection.update'), [
            'card_id' => 'OP01-001',
            'quantity' => 2,
            'is_foil' => false
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('collection_items', [
            'user_id' => $user->id,
            'card_id' => $card->id,
            'quantity' => 2,
            'is_foil' => 0
        ]);
    }

    public function test_user_can_remove_card_from_collection()
    {
        $user = User::factory()->create();
        $card = Card::factory()->create(['card_id' => 'OP01-001']);
        
        CollectionItem::create([
            'user_id' => $user->id,
            'card_id' => $card->id,
            'quantity' => 1,
            'is_foil' => false
        ]);

        $this->actingAs($user);

        // Setting quantity to 0 deletes it
        $response = $this->post(route('profile.collection.update'), [
            'card_id' => 'OP01-001',
            'quantity' => 0,
            'is_foil' => false
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseMissing('collection_items', [
            'user_id' => $user->id,
            'card_id' => $card->id
        ]);
    }

    public function test_guest_cannot_access_collection()
    {
        $response = $this->get(route('profile.collection.index'));
        $response->assertRedirect('/login'); // Assuming middleware redirects to login
        // But wait, middleware 'auth:web' usually redirects to login. 
        // Need to check if route is guarded.
        // Yes, it's inside middleware(['auth:web', 'verified']) group in web.php
    }
}
