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
        });

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique('products_username_unique');
            });
        } catch (\Throwable) {
            //
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'username')) {
                $table->string('username')->nullable(false)->change();
            }
        });

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->unique('username');
            });
        } catch (\Throwable) {
            //
        }
    }
};
