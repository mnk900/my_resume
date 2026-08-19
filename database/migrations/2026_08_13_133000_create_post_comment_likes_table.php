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
        if (!Schema::hasColumn('post_comments', 'likes_count')) {
            Schema::table('post_comments', function (Blueprint $table) {
                $table->unsignedInteger('likes_count')->default(0)->after('comment');
            });
        }

        if (!Schema::hasTable('post_comment_likes')) {
            Schema::create('post_comment_likes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_comment_id')->constrained('post_comments')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['post_comment_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_comment_likes');
        if (Schema::hasColumn('post_comments', 'likes_count')) {
            Schema::table('post_comments', function (Blueprint $table) {
                $table->dropColumn('likes_count');
            });
        }
    }
};
