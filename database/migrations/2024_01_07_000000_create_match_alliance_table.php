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
        Schema::create('match_alliance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->foreignId('alliance_id')->constrained('alliances')->onDelete('cascade');
            $table->integer('position')->nullable()->comment('Para competencias simultáneas: 1=Oro, 2=Plata, 3=Bronce');
            $table->timestamps();
            
            $table->unique(['match_id', 'alliance_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_alliance');
    }
};

