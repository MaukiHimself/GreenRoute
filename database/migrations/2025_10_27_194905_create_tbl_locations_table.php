<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_locations', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('district');
            $table->string('ward');
            $table->string('street')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index('region');
            $table->index('district');
            $table->index('ward');
            $table->index('street');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_locations');
    }
};