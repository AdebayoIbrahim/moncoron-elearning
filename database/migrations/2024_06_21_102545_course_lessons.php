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
        //
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->id();
            $table->id('course_id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('video')->nullable();
            $table->string('audio')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('course_lessons');
    }
};
