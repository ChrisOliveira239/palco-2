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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ing_session_id')->constrained('event_sessions')->restrictOnDelete();
            $table->string('ing_hash_code', 64)->unique();
            $table->string('ing_holder_name');
            $table->string('ing_holder_document', 20)->nullable();
            $table->string('ing_holder_email')->nullable();
            $table->foreignId('ing_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('ing_purchased_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
