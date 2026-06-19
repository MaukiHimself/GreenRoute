<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contractor_billing_rate_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contractor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('billing_rate_id')->nullable()->constrained('billing_rates')->nullOnDelete();
            $table->foreignId('old_billing_rate_id')->nullable()->constrained('billing_rates')->nullOnDelete();
            $table->foreignId('new_billing_rate_id')->nullable()->constrained('billing_rates')->nullOnDelete();
            $table->decimal('old_fee', 10, 2)->nullable();
            $table->decimal('new_fee', 10, 2)->nullable();
            $table->string('old_rate_label', 500)->nullable();
            $table->string('new_rate_label', 500)->nullable();
            $table->string('action', 80)->default('changed');
            $table->text('reason')->nullable();
            $table->timestamps();

            $table->index(['contractor_id', 'created_at']);
            $table->index(['schedule_id', 'created_at']);
            $table->index(['billing_rate_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contractor_billing_rate_changes');
    }
};
