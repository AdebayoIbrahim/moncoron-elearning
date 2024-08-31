<?php

// database/migrations/2024_04_27_000000_create_editor_contents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEditorContentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('editor_contents', function (Blueprint $table) {
            $table->id();
            $table->longText('content'); // To store HTML content
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('editor_contents');
    }
}
