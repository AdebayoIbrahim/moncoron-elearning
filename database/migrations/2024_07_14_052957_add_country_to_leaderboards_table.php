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
        Schema::table('leaderboards', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('leaderboards', function (Blueprint $table) {
            $table->dropColumn('country');
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
    }
};