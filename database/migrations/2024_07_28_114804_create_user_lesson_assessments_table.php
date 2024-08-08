<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLessonAssessmentsTable extends Migration
{
    public function up()
    {
        Schema::create('user_lesson_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->json('answers'); // JSON field to store user's answers
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_lesson_assessments');
    }
}
