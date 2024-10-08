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
        Schema::table('lesson_attendance', function (Blueprint $table) {
            $table->string('name')->after('lesson_id');
            $table->foreignId('student_id')->after('lesson_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_attendance', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('student_id');
        });
    }
};