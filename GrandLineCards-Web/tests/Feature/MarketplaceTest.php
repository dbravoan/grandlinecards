<?php

namespace Tests\Feature;

use App\Models\Card;
use App\Models\MarketListing;
use App\Models\User;
use App\Models\Order;
use App\Models\VaultShipment;
use App\Models\CustomerShipment;
use App\Models\Address;
use App\Services\Marketplace\MarketplaceService;
use App\Mail\ItemSold;
use App\Mail\ShipmentDispatched;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    private MarketplaceService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(MarketplaceService::class);
    }

    public function test_buyer_can_purchase_listing()
    {
        Mail::fake();

        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $card = Card::factory()->create(['card_id' => 'OP01-001']);

        $listing = MarketListing::create([
            'user_id' => $seller->id,
            'card_id' => $card->card_id,
            'price' => 50.00,
            'quantity' => 2,
            'status' => 'active',
            'listing_type' => 'buy_now' // Assuming default or null in migration
        ]);

        $order = $this->service->purchaseListing($buyer, $listing, 1);

        // Assert Order Created as PENDING
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(50.00, $order->total_price);
        $this->assertEquals('pending_payment', $order->status);

        // Simulate Payment
        $this->service->handleOrderPaid($order);
        $this->assertEquals('paid', $order->fresh()->status);

        // Assert Stock Decremented
        $this->assertEquals(1, $listing->fresh()->quantity);

        // Assert Email Sent to Seller
        // Note: Email is sent during handleOrderPaid now, not purchaseListing
        Mail::assertSent(ItemSold::class, function ($mail) use ($seller) {
            return $mail->hasTo($seller->email);
        });
    }

    public function test_seller_can_ship_to_vault()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $card = Card::factory()->create();
        
        $listing = MarketListing::create([
            'user_id' => $seller->id,
            'card_id' => $card->card_id,
            'price' => 10,
            'quantity' => 1,
            'status' => 'active'
        ]);

        $order = Order::create(['user_id' => $buyer->id, 'total_price' => 10, 'status' => 'paid']);
        $item = $order->items()->create([
            'market_listing_id' => $listing->id,
            'card_id' => $card->card_id,
            'quantity' => 1,
            'price_per_unit' => 10,
            'status' => 'awaiting_seller_shipment'
        ]);

        $shipment = $this->service->createVaultShipment($seller, [$item->id], 'TRACK123');

        $this->assertInstanceOf(VaultShipment::class, $shipment);
        $this->assertEquals('pending', $shipment->status);
        $this->assertEquals('shipped_to_vault', $item->fresh()->status);
    }

    public function test_admin_can_consolidate_shipment_to_buyer()
    {
        Mail::fake();

        $buyer = User::factory()->create();
        $card = Card::factory()->create();
        
        // Setup Address
        $address = Address::create([
            'user_id' => $buyer->id,
            'full_name' => 'John Doe',
            'line_1' => 'Street 1',
            'city' => 'City',
            'postal_code' => '12345',
            'country' => 'Spain',
            'phone' => '123456789',
            'is_default' => true
        ]);
        
        // Mock a seller and listing required for FK
        $seller = User::factory()->create();
        $listing = MarketListing::create([
            'user_id' => $seller->id,
            'card_id' => $card->card_id,
            'price' => 10,
            'quantity' => 1,
            'status' => 'sold_out'
        ]);

        // Item waiting in Vault
        $order = Order::create(['user_id' => $buyer->id, 'total_price' => 10, 'status' => 'paid']);
        $item = $order->items()->create([
            'market_listing_id' => $listing->id,
            'card_id' => $card->card_id,
            'quantity' => 1,
            'price_per_unit' => 10,
            'status' => 'in_vault'
        ]);

        $addressJson = json_encode(['line1' => 'Test Address']);
        $shipment = $this->service->createCustomerShipment($buyer, $addressJson);

        $this->assertInstanceOf(CustomerShipment::class, $shipment);
        $this->assertEquals('processing', $shipment->status);
        $this->assertEquals($shipment->id, $item->fresh()->customer_shipment_id);

        Mail::assertSent(ShipmentDispatched::class, function ($mail) use ($buyer) {
            return $mail->hasTo($buyer->email);
        });
    }
}
