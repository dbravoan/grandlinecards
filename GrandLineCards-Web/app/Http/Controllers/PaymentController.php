<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payment\PaymentService;
use App\Services\Marketplace\MarketplaceService; // To trigger emails/state changes
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;
    protected MarketplaceService $marketplaceService;

    public function __construct(PaymentService $paymentService, MarketplaceService $marketplaceService)
    {
        $this->paymentService = $paymentService;
        $this->marketplaceService = $marketplaceService;
    }

    // Called from Frontend when user clicks "Pay" for a pending order or "Buy now"
    // Ideally "Buy Now" creates a pending order then calls this.
    public function checkout(Order $order)
    {
        // Authorization check
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status === 'paid') {
            return redirect()->route('orders.index')->with('error', 'Order already paid.');
        }

        try {
            $session = $this->paymentService->createCheckoutSession($order);
            return Inertia::location($session->url);
        } catch (\Exception $e) {
            Log::error("Stripe Checkout Error: " . $e->getMessage());
            return back()->with('error', 'Unable to initiate payment.');
        }
    }

    public function success(Request $request)
    {
        // Simple success page
        return Inertia::render('Shop/PaymentSuccess', [
            'session_id' => $request->get('session_id')
        ]);
    }

    public function cancel()
    {
        return Inertia::render('Shop/PaymentCancel');
    }

    public function webhook(Request $request)
    {
        try {
            $event = $this->paymentService->handleWebhook($request);
            
            if ($event->type == 'checkout.session.completed') {
                $session = $event->data->object;
                $orderId = $session->metadata->order_id;
                
                $order = Order::find($orderId);
                if ($order && $order->status !== 'paid') {
                    // Mark as paid
                    $order->update(['status' => 'paid']);
                    
                    // Trigger Marketplace Post-Payment logic (Emails, etc.)
                    // We might need to refactor MarketplaceService to expose "onOrderPaid"
                    // For now, let's assume MarketplaceService::purchaseListing did the emails optimistically
                    // OR we move email dispatch here.
                    // The Implementation Plan said: Webhook -> Order status = 'paid', ItemSold email sent.
                    
                    // Let's rely on MarketplaceService to handle "Post Payment" actions
                    $this->marketplaceService->handleOrderPaid($order);
                }
            }
            
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error("Stripe Webhook Error: " . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
