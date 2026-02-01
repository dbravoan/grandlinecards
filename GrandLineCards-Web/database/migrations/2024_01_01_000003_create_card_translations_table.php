<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            
            $table->string('locale', 2)->index(); // 'es', 'en'
            $table->string('name');
            $table->text('effect_text')->nullable();
            $table->text('trigger_text')->nullable();
            $table->text('notes')->nullable(); // FAQ or specific rulings
            $table->json('keywords')->nullable(); // ["Rush", "Blocker"]
            
            $table->unique(['card_id', 'locale']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_translations');
    }
};
