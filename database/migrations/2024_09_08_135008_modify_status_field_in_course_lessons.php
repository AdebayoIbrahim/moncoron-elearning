<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyStatusFieldInCourseLessons extends Migration
{
    public function up()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            // Change the status field to be an integer and nullable
            $table->integer('status')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('course_lessons', function (Blueprint $table) {
            // Revert the status field if necessary (back to non-nullable)
            $table->integer('status')->nullable(false)->change();
        });
    }
}