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
        Schema::table('game_types', function (Blueprint $table) {
            $table->boolean('requires_individual_participants')->default(false)
                  ->comment('Si es verdadero, se deben seleccionar personas específicas para el enfrentamiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_types', function (Blueprint $table) {
            $table->dropColumn('requires_individual_participants');
        });
    }
};

