<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('reference')
                ->unique();
            $table->mediumText('name');
            $table->string('image')->nullable();
            $table->string('slug')->unique();
            $table->boolean('all_lessons_paid')->default(false);
            $table->text('description')->nullable();
            $table->float('price')->default(0);
            $table->unsignedInteger('capacity')->default(0);
            $table->unsignedInteger('duration')->default(0)
                ->comment('Determines the days required to access or study.');
            $table->unsignedInteger('age_group')
                ->default(0)
                ->comment('0 for all, 1 for below 18 and 2 for 18 above');
            $table->boolean('is_locked')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
