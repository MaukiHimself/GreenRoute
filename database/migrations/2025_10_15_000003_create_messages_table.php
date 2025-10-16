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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contractor_id');
            $table->unsignedBigInteger('client_id');
            $table->enum('sender_type', ['contractor', 'client']); // Who sent the message
            $table->text('message');
            $table->string('message_type')->nullable(); // pickup_schedule, invoice, custom, etc.
            $table->enum('status', ['sent', 'delivered', 'failed', 'read'])->default('sent');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['contractor_id', 'client_id']);
            $table->index(['sender_type', 'status']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
