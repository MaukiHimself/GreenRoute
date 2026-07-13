<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * System feedback: clients AND contractors report issues / suggestions about
 * the GreenRoute platform itself, and admins read and reply. Kept entirely
 * separate from the existing client↔contractor `feedback` table.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role'); // client | contractor
            $table->string('category')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('open'); // open | responded | resolved
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('responded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_feedback');
    }
};
