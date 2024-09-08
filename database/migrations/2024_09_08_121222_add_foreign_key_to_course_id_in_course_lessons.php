<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToCourseIdInCourseLessons extends Migration
{
    public function up()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->foreign('course_id')
                  ->references('id')
                  ->on('courses')
                  ->onDelete('cascade'); // Cascade delete if the course is deleted
        });
    }

    public function down()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });
    }
}