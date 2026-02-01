<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            
            $table->decimal('price', 8, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('source')->default('cardmarket'); // cardmarket, tcgplayer
            
            $table->timestamps();
            
            // Index for fast lookups
            $table->index(['card_id', 'source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_prices');
    }
};
