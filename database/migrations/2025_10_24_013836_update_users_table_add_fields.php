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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('email', 150)->nullable()->change();
            $table->string('phone', 20)->nullable();
            $table->string('avatar')->nullable();
            $table->enum('role', ['user', 'moderator', 'admin'])->default('user');
            $table->enum('status', ['active', 'banned', 'pending'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'role', 'status']);
        });
    }
};
