<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Card;
use App\Models\Expansion;
use App\Models\MarketListing;
use App\Models\Order;
use App\Services\Payment\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\TestCase;
use Stripe\Checkout\Session as StripeSession;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed generic required data if any (Roles?)
    }

    public function test_user_can_initiate_checkout_from_cart()
    {
        // 1. Setup
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $card = Card::factory()->create(); // Uses factory we fixed
        
        $listing = MarketListing::create([
            'user_id' => $seller->id,
            'card_id' => $card->card_id, // FK to cards(card_id)
            'price' => 20.00,
            'quantity' => 1, // Only 1 available
            'status' => 'active'
        ]);

        $this->actingAs($buyer);

        // 2. Action: Post to Marketplace Checkout (Cart)
        $response = $this->post(route('marketplace.checkout'), [
            'cart' => [
                ['id' => $listing->id, 'quantity' => 1]
            ]
        ]);

        // 3. Assert: Redirected to Payment Checkout
        // The controller redirects to payment.checkout route, which then redirects to Stripe
        // We need to see if an Order was created first.
        
        $order = Order::first();
        $this->assertNotNull($order);
        $this->assertEquals($buyer->id, $order->user_id);
        $this->assertEquals('pending_payment', $order->status);
        $this->assertEquals(20.00, $order->total_price);
        
        // Assert listing stock reserved (decremented)
        $this->assertEquals(0, $listing->fresh()->quantity);
        $this->assertEquals('sold_out', $listing->fresh()->status); // Since 0 qty

        $response->assertRedirect(route('payment.checkout', $order));
    }

    public function test_stripe_checkout_redirects_to_stripe_url()
    {
        // Mock Stripe
        $mockService = Mockery::mock(PaymentService::class);
        $mockSession = StripeSession::constructFrom(['id' => 'cs_test_123', 'url' => 'https://checkout.stripe.com/test-session']);
        
        $mockService->shouldReceive('createCheckoutSession')
            ->once()
            ->andReturn($mockSession);
            
        $this->app->instance(PaymentService::class, $mockService);

        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 50,
            'status' => 'pending_payment'
        ]);

        $this->actingAs($user);
        $response = $this->post(route('payment.checkout', $order));

        // Inertia redirect is a bit different, checking strictly might require asserting Inertia props or location
        // PaymentController::checkout returns Inertia::location($url) which is a 409 Conflict with X-Inertia-Location header in pure XHR, or 303 in standard?
        // Let's assert status 409 or Header X-Inertia-Location
        
        // response->assertRedirect works for 302/303
        $response->assertRedirect('https://checkout.stripe.com/test-session');
    }

    public function test_webhook_finalizes_order()
    {
        Mail::fake();
        
        // Mock PaymentService handleWebhook
        $mockService = Mockery::mock(PaymentService::class);
        $mockEvent = new \stdClass();
        $mockEvent->type = 'checkout.session.completed';
        $mockEvent->data = new \stdClass();
        $mockEvent->data->object = new \stdClass();
        
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $card = Card::factory()->create();
        
        // Pre-create order
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => 50,
            'status' => 'pending_payment'
        ]);
        
        $listing = MarketListing::create([
            'user_id' => $seller->id,
            'card_id' => $card->card_id,
            'price' => 50,
            'quantity' => 0, // already reserved
            'status' => 'sold_out'
        ]);

        // Create item linked to listing
        $order->items()->create([
             'market_listing_id' => $listing->id,
             'card_id' => $card->card_id,
             'quantity' => 1,
             'price_per_unit' => 50,
             'status' => 'awaiting_payment'
        ]);

        $mockEvent->data->object->metadata = (object) ['order_id' => $order->id];
        
        $mockService->shouldReceive('handleWebhook')->once()->andReturn($mockEvent);
        
        // We partially mock PaymentService - wait, controller uses the service.
        // We need to ensure we don't mock the internal MarketplaceService logic called by Controller?
        // PaymentController calls: $this->marketplaceService->handleOrderPaid($order);
        // We only mock PaymentService.
        
        $this->app->instance(PaymentService::class, $mockService);

        $response = $this->postJson(route('payment.webhook'), ['foo' => 'bar']);
        
        $response->assertOk();
        
        // Check Order Status
        $this->assertEquals('paid', $order->fresh()->status);
        $this->assertEquals('awaiting_seller_shipment', $order->items()->first()->status);
        
        // Check Email
        Mail::assertSent(\App\Mail\ItemSold::class);
    }
}
