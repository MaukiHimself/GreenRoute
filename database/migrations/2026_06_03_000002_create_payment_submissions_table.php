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
        Schema::create('payment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('contractor_id');

            // Payment details
            $table->string('payer_name');
            $table->decimal('amount_submitted', 15, 2);
            $table->string('payment_method'); // e.g., 'vodacom_mpesa', 'airtel_money', etc.

            // Submission tracking
            $table->enum('status', ['pending_approval', 'approved', 'rejected'])->default('pending_approval');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Receipt information
            $table->string('receipt_number')->nullable();
            $table->string('receipt_path')->nullable();
            $table->timestamp('receipt_issued_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['invoice_id', 'status']);
            $table->index(['client_id', 'contractor_id']);
            $table->index('status');
            $table->index('submitted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_submissions');
    }
};
