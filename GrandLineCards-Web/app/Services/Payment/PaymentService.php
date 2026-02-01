<?php

namespace App\Services\Payment;

use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\Webhook;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe Checkout Session for an Order.
     */
    public function createCheckoutSession(Order $order): Session
    {
        // Line items construction
        $lineItems = [];
        
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $item->card_id . ' - ' . ($item->card->name ?? 'Card'), // Fallback if no name relation loaded
                        // 'images' => [$item->card->image_url], // Optional
                    ],
                    'unit_amount' => (int) ($item->price_per_unit * 100), // In cents
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Add shipping if any (Future feature, for now flat rate or included)

        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ],
            'customer_email' => $order->user->email,
        ]);
    }

    /**
     * Handle Stripe Webhook.
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        
        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, config('services.stripe.webhook_secret')
            );
        } catch(\UnexpectedValueException $e) {
            throw new Exception('Invalid payload');
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            throw new Exception('Invalid signature');
        }

        return $event;
    }
}
