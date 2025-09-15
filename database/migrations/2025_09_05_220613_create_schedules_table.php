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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->date('pickup_date');
            $table->time('pickup_time');
            $table->string('pickup_location');
            $table->text('pickup_address');
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('zip_code', 10);
            $table->enum('service_type', ['collection', 'disposal', 'both'])->default('collection');
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->text('notes')->nullable();
            $table->decimal('estimated_duration', 4, 2)->nullable(); // in hours
            $table->timestamps();
            
            $table->index(['contractor_id', 'pickup_date']);
            $table->index(['client_id', 'pickup_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
