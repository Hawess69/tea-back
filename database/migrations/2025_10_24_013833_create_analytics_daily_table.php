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
        Schema::create('analytics_daily', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('new_users')->default(0);
            $table->integer('feed_posts')->default(0);
            $table->integer('men_posts')->default(0);
            $table->integer('red_flags')->default(0);
            $table->integer('green_flags')->default(0);
            $table->integer('total_comments')->default(0);
            $table->json('top_cities')->nullable();
            $table->timestamps();
            
            $table->unique('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_daily');
    }
};
