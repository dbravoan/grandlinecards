<?php

namespace App\Http\Controllers\Web\Front\Profile;

use App\Http\Controllers\Controller;
use App\Models\CustomerShipment;
use App\Models\OrderItem;
use App\Services\Marketplace\MarketplaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class CustomerShipmentController extends Controller
{
    protected MarketplaceService $marketplaceService;
    protected \App\Services\Logistics\LabelService $labelService;

    public function __construct(MarketplaceService $marketplaceService, \App\Services\Logistics\LabelService $labelService)
    {
        $this->marketplaceService = $marketplaceService;
        $this->labelService = $labelService;
    }

    public function index()
    {
        $user = Auth::user();

        // Items currently in the vault belonging to this user (Buyer)
        // These are items from orders they placed, where status is 'in_vault'
        // And they haven't been assigned to a CustomerShipment yet.
        $itemsInVault = OrderItem::whereHas('order', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', 'in_vault')
        ->whereNull('customer_shipment_id')
        ->with(['card', 'marketListing.user']) // marketListing.user is the Seller
        ->get();

        // Historical Customer Shipments (Incoming to User)
        $incomingShipments = CustomerShipment::where('user_id', $user->id)
            ->with(['items.card'])
            ->latest()
            ->get();

        return Inertia::render('Profile/Incoming/Index', [
            'itemsInVault' => $itemsInVault,
            'incomingShipments' => $incomingShipments,
        ]);
    }

    public function store(Request $request)
    {
        // Address validation could be here or we use the user's default address.
        // For MVP, we assume the user has set up an address in their profile or we redirect them there.
        // Or we pass the selected address ID.
        
        // Let's assume we use the User's "default" address or just a JSON dump of their primary address if available.
        // For simplicity, let's just grab the first address found or fail.
        
        $user = Auth::user();
        $address = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();

        if (!$address) {
            return redirect()->route('profile.addresses.index')->with('error', 'Please add a shipping address first.');
        }

        try {
            // Address JSON
            $addressJson = json_encode($address->toArray());
            
            $this->marketplaceService->createCustomerShipment($user, $addressJson);

            return redirect()->back()->with('success', 'Consolidated shipment requested! We will process it shortly.');
        } catch (\Exception $e) {
            Log::error("Customer Shipment Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error requesting shipment: ' . $e->getMessage());
        }
    }

    public function downloadLabel(CustomerShipment $shipment)
    {
        if ($shipment->user_id !== Auth::id()) {
            abort(403);
        }
        return $this->labelService->generateCustomerLabel($shipment);
    }
}
