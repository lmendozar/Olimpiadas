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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->dateTime('match_date');
            $table->string('result_metric')->nullable()
                  ->comment('Formato según tipo de juego: ej. "3-1", "1:05.23"');
            $table->foreignId('winner_id')->nullable()->constrained('alliances')->onDelete('set null');
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};

