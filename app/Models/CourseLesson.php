<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    use HasFactory;

    // Specify the correct table name


    protected $fillable = [
        'course_id',
        'name',
        'description',
        'video',
        'audio',
        'status',
        'image'
    ];


    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'user_course_lessons', 'lesson_id')
            ->withPivot('completed')
            ->withTimestamps();
    }
    public function userCourseLessons()
    {
        return $this->hasMany(UserCourseLesson::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'lesson_id');
    }
    // add-on-tone-attendance-relation
    public function attendance()
    {
        return $this->hasOne(lesson_attendance::class, 'lesson_id');
    }
}
