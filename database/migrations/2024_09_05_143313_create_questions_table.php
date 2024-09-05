// database/migrations/xxxx_xx_xx_create_questions_table.php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
public function up()
{
Schema::create('questions', function (Blueprint $table) {
$table->id();
$table->text('question_text');
$table->integer('points')->nullable(); // Points per question
$table->string('image_path')->nullable();
$table->string('audio_path')->nullable();
$table->string('video_path')->nullable();
$table->unsignedBigInteger('course_id');
$table->unsignedBigInteger('lesson_id');
$table->timestamps();

$table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
$table->foreign('lesson_id')->references('id')->on('lessons')->onDelete('cascade');
});

Schema::create('options', function (Blueprint $table) {
$table->id();
$table->unsignedBigInteger('question_id');
$table->text('option_text');
$table->string('image_path')->nullable();
$table->string('audio_path')->nullable();
$table->boolean('is_correct')->default(false);
$table->timestamps();

$table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
});

Schema::create('general_settings', function (Blueprint $table) {
$table->id();
$table->integer('time_limit')->nullable(); // General time limit for all questions
$table->timestamps();
});
}

public function down()
{
Schema::dropIfExists('options');
Schema::dropIfExists('questions');
Schema::dropIfExists('general_settings');
}
}