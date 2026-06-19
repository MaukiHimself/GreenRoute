<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_prices')) {
            return;
        }

        Schema::create('service_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade');
            $table->string('service_type'); // regular_pickup, bulk_collection, hazardous_waste, recycling, organic_waste, construction_debris
            $table->string('waste_type')->nullable(); // general, recyclable, organic, electronic, medical, industrial
            $table->string('category')->nullable(); // residential, commercial, industrial
            $table->string('volume_tier')->nullable(); // small, medium, large, extra_large, container
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('TZS');
            $table->text('description')->nullable();
            $table->text('includes')->nullable(); // what's included in this price
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['contractor_id', 'service_type', 'is_active']);
            $table->unique(['contractor_id', 'service_type', 'waste_type', 'volume_tier', 'category'], 'sp_unique_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_prices');
    }
};
