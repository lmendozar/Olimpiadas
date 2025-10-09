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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string')->comment('string, color, url, json');
            $table->timestamps();
        });

        // Insert default settings
        DB::table('system_settings')->insert([
            [
                'key' => 'system_title',
                'value' => 'Sistema de Gestión de Olimpiadas',
                'type' => 'string',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'system_logo',
                'value' => '',
                'type' => 'url',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'primary_color',
                'value' => '#2563eb',
                'type' => 'color',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'secondary_color',
                'value' => '#059669',
                'type' => 'color',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'accent_color',
                'value' => '#dc2626',
                'type' => 'color',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};

