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

        Schema::create('track_points', function (Blueprint $table) {
            $table->id(); // Jedinečný identifikátor pro každý bod
            $table->integer('user_id')->unsigned(); // Uživatel, který vytvořil bod
            $table->foreignId('result_id')->constrained();
            $table->double('latitude', 15, 8); // Zeměpisná šířka
            $table->double('longitude', 15, 8); // Zeměpisná délka
            $table->bigInteger('time')->unsigned()->nullable(); // Časová značka
            $table->integer('cadence')->unsigned()->nullable(); // Časová značka
            $table->float('altitude')->nullable(); // Nadmořská výška
            //$table->float('speed')->nullable(); // Rychlost
            //$table->integer('heart_rate')->nullable(); // Srdeční tep
            $table->unique(['user_id', 'time']);
            $table->timestamps(); // Vytvoří created_at a updated_at sloupce
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_points');
    }
};
