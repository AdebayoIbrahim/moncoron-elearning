<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLessonAssessment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_id', 'lesson_id', 'answers', 'score','time_taken'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
