<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expansions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // OP-01, OP-02
            $table->string('name'); // Romance Dawn
            $table->date('release_date');
            $table->boolean('is_legal')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expansions');
    }
};
