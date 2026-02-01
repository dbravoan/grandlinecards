<?php

namespace App\Http\Controllers\Web\Front\Profile;

use App\Http\Controllers\Controller;
use App\Models\VaultShipment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VaultShipmentController extends Controller
{
    protected \App\Services\Logistics\LabelService $labelService;

    public function __construct(\App\Services\Logistics\LabelService $labelService)
    {
        $this->labelService = $labelService;
    }

    public function index()
    {
        $user = Auth::user();

        // Items sold but not yet shipped to vault
        // We find OrderItems where the listing belongs to the user AND status is 'awaiting_seller_shipment'
        $itemsToShip = OrderItem::whereHas('marketListing', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->where('status', 'awaiting_seller_shipment')
        ->with(['card', 'order.user']) // Include buyer info?
        ->get();

        // Previous shipments
        $shipments = VaultShipment::where('user_id', $user->id)
            ->with(['items.card'])
            ->latest()
            ->get();

        return Inertia::render('Profile/Shipments/Index', [
            'itemsToShip' => $itemsToShip,
            'shipments' => $shipments,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_ids' => 'required|array',
            'item_ids.*' => 'exists:order_items,id',
            'carrier' => 'required|string',
            'tracking_number' => 'required|string',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user, $validated) {
            // Verify ownership of items
            // Ensure all items belong to listings by this user
            $count = OrderItem::whereIn('id', $validated['item_ids'])
                ->whereHas('marketListing', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->where('status', 'awaiting_seller_shipment')
                ->count();

            if ($count !== count($validated['item_ids'])) {
                abort(403, "Invalid items selected.");
            }

            // Create Shipment
            $shipment = VaultShipment::create([
                'user_id' => $user->id,
                'carrier' => $validated['carrier'],
                'tracking_number' => $validated['tracking_number'],
                'status' => 'pending',
            ]);

            // Update items
            OrderItem::whereIn('id', $validated['item_ids'])->update([
                'vault_shipment_id' => $shipment->id,
                'status' => 'shipped_to_vault',
            ]);
        });

        return redirect()->back()->with('success', 'Shipment created! Send your package to the Grand Line Vault.');
    }

    public function downloadLabel(VaultShipment $shipment)
    {
        if ($shipment->user_id !== Auth::id()) {
            abort(403);
        }
        return $this->labelService->generateVaultLabel($shipment);
    }
}
