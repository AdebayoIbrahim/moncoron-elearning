<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCourseIdColumnInCourseLessons extends Migration
{
    public function up()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->change(); 
        });
    }

    public function down()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            $table->bigInteger('course_id')->change(); // Revert back if necessary
        });
    }
}