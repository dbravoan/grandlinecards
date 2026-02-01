<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('card_id'); 
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->string('status')->default('active');
            $table->string('listing_type')->default('buy_now'); 
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
        
        Schema::create('vault_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tracking_number')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('customer_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('tracking_number')->nullable();
            $table->text('shipping_address');
            $table->decimal('shipping_cost', 8, 2)->default(0);
            $table->string('status')->default('processing');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_listing_id')->constrained();
            $table->string('card_id');
            $table->integer('quantity');
            $table->decimal('price_per_unit', 10, 2);
            $table->string('status')->default('awaiting_seller_shipment'); 
            $table->foreignId('vault_shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('customer_shipments');
        Schema::dropIfExists('vault_shipments');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('market_listings');
    }
};
