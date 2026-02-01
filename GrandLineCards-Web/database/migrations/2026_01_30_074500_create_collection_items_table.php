<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('card_id')->constrained('cards')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->boolean('is_foil')->default(false);
            $table->timestamps();

            // Unique constraint to prevent duplicates for same card+foil combo
            $table->unique(['user_id', 'card_id', 'is_foil']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_items');
    }
};
