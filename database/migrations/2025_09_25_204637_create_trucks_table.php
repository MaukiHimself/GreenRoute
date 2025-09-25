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
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade');
            $table->string('plate_number');
            $table->string('driver_name');
            $table->string('driver_phone');
            $table->string('truck_type');
            $table->string('status')->default('active');
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->decimal('previous_latitude', 10, 8)->nullable();
            $table->decimal('previous_longitude', 11, 8)->nullable();
            $table->decimal('daily_distance', 8, 2)->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};
