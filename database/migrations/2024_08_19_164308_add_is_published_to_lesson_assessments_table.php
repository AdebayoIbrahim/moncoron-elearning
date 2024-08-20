<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPublishedToLessonAssessmentsTable extends Migration
{
    public function up()
    {
        Schema::table('lesson_assessments', function (Blueprint $table) {
            $table->boolean('is_published')->default(false); // Add the is_published column
        });
    }

    public function down()
    {
        Schema::table('lesson_assessments', function (Blueprint $table) {
            $table->dropColumn('is_published'); // Remove the column if the migration is rolled back
        });
    }
}
