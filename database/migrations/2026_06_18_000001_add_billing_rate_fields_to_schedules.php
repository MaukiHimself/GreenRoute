<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('billing_rate_id')->nullable()->after('client_id')->constrained('billing_rates')->nullOnDelete();
            $table->string('billing_rate_category')->nullable()->after('billing_rate_id');
            $table->string('billing_rate_location')->nullable()->after('billing_rate_category');
            $table->string('billing_rate_frequency')->nullable()->after('billing_rate_location');
            $table->decimal('base_collection_fee', 10, 2)->nullable()->after('billing_rate_frequency');
            $table->decimal('contractor_adjusted_fee', 10, 2)->nullable()->after('base_collection_fee');
            $table->decimal('schedule_price', 10, 2)->nullable()->after('contractor_adjusted_fee');
            $table->text('billing_rate_change_reason')->nullable()->after('schedule_price');
            $table->timestamp('billing_rate_modified_at')->nullable()->after('billing_rate_change_reason');

            $table->index(['billing_rate_id']);
            $table->index(['schedule_price']);
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex(['schedule_price']);
            $table->dropIndex(['billing_rate_id']);
            $table->dropColumn([
                'billing_rate_id',
                'billing_rate_category',
                'billing_rate_location',
                'billing_rate_frequency',
                'base_collection_fee',
                'contractor_adjusted_fee',
                'schedule_price',
                'billing_rate_change_reason',
                'billing_rate_modified_at',
            ]);
        });
    }
};
