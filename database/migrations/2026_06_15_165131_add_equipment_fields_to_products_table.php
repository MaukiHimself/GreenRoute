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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 12, 2)->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'unit')) {
                $table->string('unit')->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'specifications')) {
                $table->text('specifications')->nullable()->after('unit');
            }
            if (!Schema::hasColumn('products', 'category')) {
                $table->string('category')->nullable()->after('specifications');
            }
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('category');
            }
            if (!Schema::hasColumn('products', 'is_available')) {
                $table->boolean('is_available')->default(true)->after('image');
            }
            if (!Schema::hasColumn('products', 'contractor_id')) {
                $table->foreignId('contractor_id')->nullable()->constrained('users')->nullOnDelete()->after('id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $columns = ['name', 'description', 'price', 'unit', 'specifications', 'category', 'image', 'is_available', 'contractor_id'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
