<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expansion_id')->constrained()->cascadeOnDelete();
            
            $table->string('card_id')->unique(); // OP01-001
            $table->string('color')->index(); // Red, Green, Red/Green, etc.
            $table->string('type'); // Leader, Character, Event, Stage
            $table->json('attributes')->nullable(); // Slash, Strike, Wisdom (Array)
            
            $table->integer('cost')->nullable();
            $table->integer('power')->nullable();
            $table->integer('counter')->nullable();
            $table->integer('life')->nullable(); // For Leaders
            
            $table->enum('rarity', ['L', 'C', 'UC', 'R', 'SR', 'SEC', 'P']);
            $table->string('image_url')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
