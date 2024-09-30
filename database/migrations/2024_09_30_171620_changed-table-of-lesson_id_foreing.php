<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lesson_assessment_result', function (Blueprint $table) {

            $table->dropForeign(['lesson_id']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_assessment_result', function (Blueprint $table) {
            $table->foreignId('lesson_id')->constrained('course_lessons')->onDelete('cascade');
        });
    }
};
