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
        Schema::create('game_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->enum('result_metric', ['tiempo', 'goles', 'sets', 'contador'])
                  ->comment('tiempo=Time(s), goles=Goals, sets=Sets, contador=Counter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_types');
    }
};

