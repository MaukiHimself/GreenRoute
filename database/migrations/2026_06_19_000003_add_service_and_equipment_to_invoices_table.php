<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'service_price_id')) {
                $table->foreignId('service_price_id')->nullable()->constrained()->nullOnDelete()->after('schedule_id');
            }

            if (! Schema::hasColumn('invoices', 'product_id')) {
                $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete()->after('service_price_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_price_id');
            $table->dropConstrainedForeignId('product_id');
        });
    }
};
