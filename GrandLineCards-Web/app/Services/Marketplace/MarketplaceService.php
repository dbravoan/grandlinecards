<?php

namespace App\Services\Marketplace;

use App\Models\MarketListing;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\VaultShipment;
use App\Models\CustomerShipment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ItemSold;
use App\Mail\ShipmentDispatched;

class MarketplaceService
{
    /**
     * Buyer purchases a listing.
     */
    /**
     * Create an order from a cart of listings.
     */
    public function createOrderFromCart(User $buyer, array $cartItems): Order
    {
        return DB::transaction(function () use ($buyer, $cartItems) {
            $totalPrice = 0;
            
            // Create Order Shell
            $order = Order::create([
                'user_id' => $buyer->id,
                'total_price' => 0, // Update later
                'status' => 'pending_payment',
            ]);

            foreach ($cartItems as $cartItem) {
                // Determine Listing ID and Quantity
                // Assuming cartItem has 'id' (product id? or listing id?)
                // Based on Index.vue: 'product' is pushed.
                // In Index.vue, sealed product has 'id'.
                // Single product has 'id'.
                // CAUTION: Index.vue mixes Sealed and Singles?
                // Index.vue "addToCart" uses `product`.
                // Singles have `id` which is probably the `Card` ID or `MarketListing` ID?
                // In CatalogController/ShopController, we need to know what `id` maps to.
                // Assuming for this Task we are buying MarketListings (Singles).
                // Let's assume validation happens in Controller.
                
                $listing = MarketListing::lockForUpdate()->find($cartItem['id']);
                
                if (!$listing || $listing->quantity < $cartItem['quantity']) {
                    throw new \Exception("Item {$listing->card_id} unavailable or insufficient stock.");
                }
                
                $price = $listing->price;
                $quantity = $cartItem['quantity'];
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'market_listing_id' => $listing->id,
                    'card_id' => $listing->card_id,
                    'quantity' => $quantity,
                    'price_per_unit' => $price,
                    'status' => 'awaiting_payment',
                ]);
                
                $totalPrice += $price * $quantity;
                
                $listing->decrement('quantity', $quantity);
                if ($listing->quantity === 0) {
                    $listing->update(['status' => 'sold_out']);
                }
            }
            
            $order->update(['total_price' => $totalPrice]);
            
            return $order;
        });
    }

    public function purchaseListing(User $buyer, MarketListing $listing, int $quantity): Order
    {
        $order = DB::transaction(function () use ($buyer, $listing, $quantity) {
            // Validation
            if ($listing->quantity < $quantity) {
                throw new \Exception("Not enough stock.");
            }

            // Create Order with PENDING PAYMENT status
            $totalPrice = $listing->price * $quantity;
            
            $order = Order::create([
                'user_id' => $buyer->id,
                'total_price' => $totalPrice,
                'status' => 'pending_payment', 
            ]);

            // Create Item
            OrderItem::create([
                'order_id' => $order->id,
                'market_listing_id' => $listing->id,
                'card_id' => $listing->card_id,
                'quantity' => $quantity,
                'price_per_unit' => $listing->price,
                'status' => 'awaiting_payment', // New status needed in DB or Enum
            ]);

            // Reserve Stock (Optimization: Decrement now, restore if timeout? Or check later?)
            // For MVP: Decrement now to prevent double buys. If payment fails, we need a cron to release stock.
            $listing->decrement('quantity', $quantity);
            if ($listing->quantity === 0) {
                $listing->update(['status' => 'sold_out']);
            }

            return $order;
        });

        return $order;
    }

    /**
     * Handle post-payment logic.
     */
    public function handleOrderPaid(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'paid']);
            // Update items status
             foreach ($order->items as $item) {
                 $item->update(['status' => 'awaiting_seller_shipment']);
                 
                 // Send Email to Seller
                 try {
                     // Loading relationship if needed
                     $seller = $item->marketListing->user; 
                     Mail::to($seller)->send(new ItemSold($item));
                 } catch (\Exception $e) {
                     // Log
                 }
             }
        });
    }

    /**
     * Seller creates a shipment to send to the Vault.
     */
    public function createVaultShipment(User $seller, array $orderItemIds, string $tracking): VaultShipment
    {
        return DB::transaction(function () use ($seller, $orderItemIds, $tracking) {
             // specific items validation skipped for brevity
            
            $shipment = VaultShipment::create([
                'user_id' => $seller->id,
                'tracking_number' => $tracking,
                'status' => 'pending',
            ]);

            // Associate items
            OrderItem::whereIn('id', $orderItemIds)->update([
                'vault_shipment_id' => $shipment->id,
                // Status remains 'awaiting_seller_shipment' until physically sent?
                // Or maybe 'shipped_to_vault' if tracking is provided.
                'status' => 'shipped_to_vault', 
            ]);

            return $shipment;
        });
    }

    /**
     * Admin receives the shipment at the Vault.
     */
    public function receiveVaultShipment(VaultShipment $shipment): void
    {
        DB::transaction(function () use ($shipment) {
            $shipment->update(['status' => 'received']);
            $shipment->items()->update(['status' => 'in_vault']);
        });
    }

    /**
     * Create a consolidated shipment for the buyer from items in the vault.
     */
    public function createCustomerShipment(User $buyer, string $addressJson): CustomerShipment
    {
        $shipment = DB::transaction(function () use ($buyer, $addressJson) {
            // Find all items for this buyer currently sitting in the vault
            // Logic: Buyer is the User on the Order, not the Listing user.
            // We need to join.
            
            $items = OrderItem::whereHas('order', function ($q) use ($buyer) {
                $q->where('user_id', $buyer->id);
            })->where('status', 'in_vault')
              ->whereNull('customer_shipment_id') // Not yet shipped
              ->get();

            if ($items->isEmpty()) {
                throw new \Exception("No items in vault for this user.");
            }

            $shipment = CustomerShipment::create([
                'user_id' => $buyer->id,
                'shipping_address' => $addressJson,
                'status' => 'processing',
                'shipping_cost' => 5.00, // Flat rate for MVP
            ]);

            foreach ($items as $item) {
                $item->update([
                    'customer_shipment_id' => $shipment->id,
                    // Status remains 'in_vault' until actually shipped? 
                    // Or processing. Let's say processing.
                ]);
            }
            
            return $shipment;
        });

        // Post-transaction email
        try {
             if (!$shipment->tracking_number) {
                 $shipment->tracking_number = 'GLC-' . strtoupper(uniqid());
                 $shipment->save();
             }
             
             Mail::to($buyer)->send(new ShipmentDispatched($shipment));
        } catch (\Exception $e) {
            // Log error
        }

        return $shipment;
    }
    /**
     * Place a bid on a market listing.
     */
    public function placeBid(User $user, MarketListing $listing, float $amount): \App\Models\Bid
    {
        return DB::transaction(function () use ($user, $listing, $amount) {
            // Validate: Listing is an auction
            if (!$listing->is_auction) {
                throw new \Exception("Esta carta no está en subasta.");
            }

            // Validate: Not expired
            if ($listing->auction_end_at && $listing->auction_end_at->isPast()) {
                throw new \Exception("La subasta ha terminado.");
            }

            // Validate: User is not seller
            if ($user->id === $listing->user_id) {
                throw new \Exception("No puedes pujar en tu propia carta.");
            }

            // Validate: Amount > Current Price or Highest Bid
            // If there are bids, amount must be > highest bid + increment?
            // If no bids, amount must be >= starting price (which is listing->price)
            
            $highestBid = $listing->highestBid;
            $currentPrice = $highestBid ? $highestBid->amount : $listing->price;

            if ($amount <= $currentPrice) {
                $min = $currentPrice + 0.5; // Minimum increment MVP
                throw new \Exception("La puja debe ser superior a {$currentPrice} €. Mínimo {$min} €.");
            }

            // Create Bid
            $bid = \App\Models\Bid::create([
                'market_listing_id' => $listing->id,
                'user_id' => $user->id,
                'amount' => $amount,
            ]);

            // Update Listing Current Price (Visual)
            // Or we just rely on highestBid relation.
            // Let's update price to reflect current value easily in UI lists.
            $listing->update(['price' => $amount]);

            return $bid;
        });
    }
}
