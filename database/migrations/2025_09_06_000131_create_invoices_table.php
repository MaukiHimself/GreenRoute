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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('contractor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()->constrained()->onDelete('set null');
            
            // Invoice details
            $table->date('invoice_date');
            $table->date('due_date');
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft');
            
            // Financial details
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // Service details
            $table->string('service_type');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            
            // Payment tracking
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_method')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['contractor_id', 'invoice_date']);
            $table->index(['client_id', 'status']);
            $table->index('invoice_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
