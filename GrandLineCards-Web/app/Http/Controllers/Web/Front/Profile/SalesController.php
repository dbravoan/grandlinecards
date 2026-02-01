<?php

namespace App\Http\Controllers\Web\Front\Profile;

use App\Http\Controllers\Controller;
use App\Models\MarketListing;
use App\Models\Order;
use App\Models\Card;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Active Listings
        $activeListings = MarketListing::where('user_id', $user->id)
            ->where('status', 'active')
            ->with('card.translations') // Fix N+1
            ->latest()
            ->get();

        // Sales History (Items that are part of an Order)
        $soldItems = \App\Models\OrderItem::whereHas('marketListing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->whereHas('order', function ($q) {
            $q->where('status', 'paid');
        })
        ->with(['card.translations', 'order']) // Fix N+1
        ->get();

        // 1. Total Revenue
        $totalRevenue = $soldItems->sum(function ($item) {
            return $item->price_per_unit * $item->quantity;
        });

        // 2. Items Sold
        $itemsSold = $soldItems->sum('quantity');

        // 3. Last 30 Days Chart Data
        $salesLast30Days = $soldItems->where('created_at', '>=', now()->subDays(30))
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })
            ->map(function ($dayItems) {
                return $dayItems->sum(function ($item) {
                    return $item->price_per_unit * $item->quantity;
                });
            })
            ->sortKeys();
        
        // Fill missing days with 0
        $chartData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData[$date] = $salesLast30Days[$date] ?? 0;
        }

        // 4. Sold Listings View (Legacy listing based)
        $soldListings = MarketListing::where('user_id', $user->id)
            ->whereIn('status', ['sold_out', 'closed']) 
            ->with(['card.translations']) // Fix N+1
            ->latest()
            ->get();

        return Inertia::render('Profile/Sales/Index', [
            'activeListings' => $activeListings,
            'soldListings' => $soldListings,
            'analytics' => [
                'total_revenue' => $totalRevenue,
                'items_sold' => $itemsSold,
                'average_price' => $itemsSold > 0 ? round($totalRevenue / $itemsSold, 2) : 0,
                'chart_data' => $chartData,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'card_id' => 'required|exists:cards,id',
            'price' => 'required|numeric|min:0.5',
            'condition' => 'required|string|in:NM,LP,MP,HP,D',
            'quantity' => 'required|integer|min:1',
            'is_auction' => 'boolean',
        ]);

        $user = Auth::user();

        MarketListing::create([
            'user_id' => $user->id,
            'card_id' => $validated['card_id'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'quantity' => $validated['quantity'],
            'is_auction' => $validated['is_auction'] ?? false,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Card listed successfully!');
    }

    public function destroy(MarketListing $listing)
    {
        if ($listing->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deleting if not sold
        if ($listing->status !== 'active') {
             return redirect()->back()->with('error', 'Cannot delete a sold listing.');
        }

        $listing->delete();

        return redirect()->back()->with('success', 'Listing removed.');
    }
}
