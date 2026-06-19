<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'username')) {
                $table->string('username')->nullable()->change();
            }

            if (Schema::hasColumn('products', 'password')) {
                $table->string('password')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'username')) {
                $table->string('username')->nullable(false)->change();
            }

            if (Schema::hasColumn('products', 'password')) {
                $table->string('password')->nullable(false)->change();
            }
        });
    }
};
