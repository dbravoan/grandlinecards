<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('decks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('leader_id'); // OP01-001
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });

        Schema::create('deck_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('deck_id')->constrained()->cascadeOnDelete();
            $table->string('card_id');
            $table->integer('quantity');
            $table->timestamps();
            
            $table->unique(['deck_id', 'card_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deck_cards');
        Schema::dropIfExists('decks');
    }
};
