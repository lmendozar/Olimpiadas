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
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_type_id')->constrained('game_types')->onDelete('cascade');
            $table->date('start_date');
            $table->integer('first_place_points')->default(5);
            $table->integer('second_place_points')->default(3);
            $table->integer('third_place_points')->default(1);
            $table->boolean('is_simultaneous')->default(false)
                  ->comment('Si es verdadero, los lugares se determinan directamente del resultado');
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competitions');
    }
};

