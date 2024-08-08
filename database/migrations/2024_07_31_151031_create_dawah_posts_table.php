<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDawahPostsTable extends Migration
{
    public function up()
    {
        Schema::create('dawah_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dawah_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dawah_posts');
    }
}
