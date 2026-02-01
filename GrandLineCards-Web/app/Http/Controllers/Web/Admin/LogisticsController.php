<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\VaultShipment;
use App\Models\CustomerShipment;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\Marketplace\MarketplaceService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class LogisticsController extends Controller
{
    public function __construct(
        private MarketplaceService $marketplaceService
    ) {}

    public function index()
    {
        // 1. Incoming Shipments (Pending receipt)
        $incoming = VaultShipment::where('status', 'pending')
            ->with(['user', 'items.card'])
            ->latest()
            ->get();

        // 2. Vault Inventory (Grouped by Buyer)
        // We want to see which users have items 'in_vault' ready to be shipped.
        $vaultInventory = OrderItem::where('status', 'in_vault')
            ->with(['order.user', 'card'])
            ->get()
            ->groupBy(function ($item) {
                return $item->order->user_id;
            });

        // Transform for view
        $readyToShip = [];
        foreach ($vaultInventory as $userId => $items) {
            $user = $items->first()->order->user;
            $readyToShip[] = [
                'user' => $user,
                'items' => $items,
                'total_items' => $items->count(),
            ];
        }

        return Inertia::render('Admin/Logistics/Index', [
            'incoming' => $incoming,
            'readyToShip' => $readyToShip,
        ]);
    }

    public function receive(VaultShipment $shipment)
    {
        $this->marketplaceService->receiveVaultShipment($shipment);

        return redirect()->back()->with('success', 'Shipment received. Items are now in the Vault.');
    }

    public function createCustomerShipment(User $user, Request $request)
    {
        // Fetch User's Default Address
        $addressObj = $user->addresses()->where('is_default', true)->first();
        
        // Fallback to any address if no default
        if (!$addressObj) {
            $addressObj = $user->addresses()->first();
        }

        if (!$addressObj) {
            return redirect()->back()->with('error', 'User has no shipping address saved. Cannot ship.');
        }
        
        $address = [
            'full_name' => $addressObj->full_name,
            'line1' => $addressObj->line_1 . ($addressObj->line_2 ? ' ' . $addressObj->line_2 : ''),
            'city' => $addressObj->city,
            'postal_code' => $addressObj->postal_code,
            'country' => $addressObj->country,
            'phone' => $addressObj->phone
        ];

        try {
            $this->marketplaceService->createCustomerShipment($user, json_encode($address));
            return redirect()->back()->with('success', 'Consolidated shipment created for user.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
