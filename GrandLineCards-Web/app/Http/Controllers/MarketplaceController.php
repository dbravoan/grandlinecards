<?php

namespace App\Http\Controllers;

use App\Services\Marketplace\MarketplaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarketplaceController extends Controller
{
    protected MarketplaceService $marketplaceService;

    public function __construct(MarketplaceService $marketplaceService)
    {
        $this->marketplaceService = $marketplaceService;
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.id' => 'required|integer', // Listing ID
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // Assume for now we only support MarketListings in this endpoint
            $order = $this->marketplaceService->createOrderFromCart($request->user(), $request->input('cart'));
            
            // Redirect to Payment
            return to_route('payment.checkout', ['order' => $order->id]);
        } catch (\Exception $e) {
            Log::error("Checkout Error: " . $e->getMessage());
            return back()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }
}
