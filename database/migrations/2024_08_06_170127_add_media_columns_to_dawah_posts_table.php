<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaColumnsToDawahPostsTable extends Migration
{
    public function up()
    {
        Schema::table('dawah_posts', function (Blueprint $table) {
            $table->string('image')->nullable()->after('content');
            $table->string('video')->nullable()->after('image');
            $table->string('audio')->nullable()->after('video');
        });
    }

    public function down()
    {
        Schema::table('dawah_posts', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('video');
            $table->dropColumn('audio');
        });
    }
}
