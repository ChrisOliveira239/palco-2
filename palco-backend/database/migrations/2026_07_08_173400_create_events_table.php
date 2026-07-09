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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('eve_title');
            $table->text('eve_synopsis')->nullable();
            $table->string('eve_type', 50);
            $table->string('eve_venue_name');
            $table->foreignId('eve_city_id')->constrained('cities')->restrictOnDelete();
            $table->string('eve_ticket_url')->nullable();
            $table->string('eve_poster_path')->nullable();
            $table->foreignId('eve_created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
