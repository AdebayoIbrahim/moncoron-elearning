<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $table = "chat_messages";
    protected $fillable = ['user_id', 'lesson_id', 'course_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}