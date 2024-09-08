<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToCourseLessonsTable extends Migration
{
    public function up()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->string('image')->nullable()->after('audio'); // Add image field
        });
    }

    public function down()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
}