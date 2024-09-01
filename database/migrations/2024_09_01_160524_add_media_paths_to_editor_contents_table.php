<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaPathsToEditorContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('editor_contents', function (Blueprint $table) {
            $table->string('image_path')->nullable();
            $table->string('video_path')->nullable();
            $table->string('audio_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('editor_contents', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'video_path', 'audio_path']);
        });
    }
}
