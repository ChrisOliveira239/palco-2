<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_favorites', function (Blueprint $table) {
            $table->foreignId('fav_event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('fav_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->primary(['fav_event_id', 'fav_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_favorites');
    }
};