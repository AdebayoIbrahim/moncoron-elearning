<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $table = "chat_messages";
    protected $fillable = ['user_id', 'lesson_number', 'course_id', 'message', 'audio'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_number');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}