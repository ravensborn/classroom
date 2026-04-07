<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('videos', 'posts');
        Schema::rename('video_attendances', 'post_attendances');
        Schema::rename('video_comments', 'post_comments');

        Schema::table('post_attendances', function (Blueprint $table) {
            $table->renameColumn('video_id', 'post_id');
        });

        Schema::table('post_comments', function (Blueprint $table) {
            $table->renameColumn('video_id', 'post_id');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('file_path', 'video_path');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('video_path')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('video_path')->nullable(false)->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->renameColumn('video_path', 'file_path');
        });

        Schema::table('post_comments', function (Blueprint $table) {
            $table->renameColumn('post_id', 'video_id');
        });

        Schema::table('post_attendances', function (Blueprint $table) {
            $table->renameColumn('post_id', 'video_id');
        });

        Schema::rename('post_comments', 'video_comments');
        Schema::rename('post_attendances', 'video_attendances');
        Schema::rename('posts', 'videos');
    }
};
