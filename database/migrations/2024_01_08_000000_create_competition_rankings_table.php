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
        Schema::create('competition_rankings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained('competitions')->onDelete('cascade');
            $table->foreignId('alliance_id')->constrained('alliances')->onDelete('cascade');
            $table->integer('position')->comment('1=Oro, 2=Plata, 3=Bronce');
            $table->integer('points_awarded');
            $table->timestamps();
            
            $table->unique(['competition_id', 'alliance_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_rankings');
    }
};

