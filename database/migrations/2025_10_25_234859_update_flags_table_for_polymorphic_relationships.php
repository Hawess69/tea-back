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
        Schema::table('flags', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['post_id']);
            
            // Drop the existing unique constraint
            $table->dropUnique(['post_id', 'user_id']);
            
            // Rename post_id to flagable_id and add flagable_type for polymorphic relationship
            $table->renameColumn('post_id', 'flagable_id');
            $table->string('flagable_type')->after('flagable_id');
            
            // Add new unique constraint for polymorphic relationship
            $table->unique(['flagable_id', 'flagable_type', 'user_id'], 'flags_flagable_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flags', function (Blueprint $table) {
            // Drop the polymorphic unique constraint
            $table->dropUnique('flags_flagable_user_unique');
            
            // Rename back to post_id
            $table->renameColumn('flagable_id', 'post_id');
            $table->dropColumn('flagable_type');
            
            // Re-add the original foreign key constraint
            $table->foreign('post_id')->references('id')->on('men_posts')->onDelete('cascade');
            
            // Re-add the original unique constraint
            $table->unique(['post_id', 'user_id']);
        });
    }
};