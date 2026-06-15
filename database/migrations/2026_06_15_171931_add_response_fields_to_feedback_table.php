<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            if (!Schema::hasColumn('feedback', 'response')) {
                $table->text('response')->nullable()->after('message');
            }
            if (!Schema::hasColumn('feedback', 'responded_at')) {
                $table->timestamp('responded_at')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            if (Schema::hasColumn('feedback', 'response')) {
                $table->dropColumn('response');
            }
            if (Schema::hasColumn('feedback', 'responded_at')) {
                $table->dropColumn('responded_at');
            }
        });
    }
};
