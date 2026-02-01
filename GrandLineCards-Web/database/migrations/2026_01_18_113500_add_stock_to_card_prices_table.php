<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('card_prices', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('price');
            $table->boolean('is_foil')->default(false)->after('stock');
            $table->enum('condition', ['NM', 'LP', 'MP', 'HP', 'DMG'])->default('NM')->after('is_foil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('card_prices', function (Blueprint $table) {
            $table->dropColumn(['stock', 'is_foil', 'condition']);
        });
    }
};
