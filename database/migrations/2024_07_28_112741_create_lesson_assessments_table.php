<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonAssessmentsTable extends Migration
{
    public function up()
    {
        Schema::create('lesson_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->json('questions'); // JSON field to store questions and options
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_assessments');
    }
}
