<?php

namespace App\Http\Controllers\Web\Front;

use App\Http\Controllers\Controller;
use App\Models\MarketListing;
use App\Services\Marketplace\MarketplaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuctionController extends Controller
{
    public function __construct(
        private readonly MarketplaceService $marketplaceService
    ) {}

    public function bid(Request $request, MarketListing $listing)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.5',
        ]);

        try {
            $this->marketplaceService->placeBid(Auth::user(), $listing, $request->amount);
        } catch (\Exception $e) {
            return back()->withErrors(['amount' => $e->getMessage()]);
        }

        return back()->with('success', 'Bid placed successfully!');
    }
}
