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
        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ses_event_id')->constrained('events')->cascadeOnDelete();
            $table->dateTime('ses_start_at');
            $table->dateTime('ses_end_at')->nullable();
            $table->string('ses_pricing_type', 20)->default('free');
            $table->decimal('ses_price', 8, 2)->nullable();
            $table->unsignedInteger('ses_capacity')->nullable();
            $table->timestamps();

            $table->index(['ses_event_id', 'ses_start_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_sessions');
    }
};
