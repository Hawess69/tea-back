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
        Schema::table('votes', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['post_id']);
            
            // Drop the existing unique constraint
            $table->dropUnique(['post_id', 'user_id']);
            
            // Rename post_id to voteable_id and add voteable_type for polymorphic relationship
            $table->renameColumn('post_id', 'voteable_id');
            $table->string('voteable_type')->after('voteable_id');
            
            // Add new unique constraint for polymorphic relationship
            $table->unique(['voteable_id', 'voteable_type', 'user_id'], 'votes_voteable_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Drop the polymorphic unique constraint
            $table->dropUnique('votes_voteable_user_unique');
            
            // Rename back to post_id
            $table->renameColumn('voteable_id', 'post_id');
            $table->dropColumn('voteable_type');
            
            // Re-add the original foreign key constraint
            $table->foreign('post_id')->references('id')->on('feed_posts')->onDelete('cascade');
            
            // Re-add the original unique constraint
            $table->unique(['post_id', 'user_id']);
        });
    }
};