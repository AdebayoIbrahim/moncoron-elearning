<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price'             => 'float',
        'capacity'          => 'int',
        'duration'          => 'int',
        'age_group'         => 'int',
        'is_locked'         => 'bool',
        'all_lessons_paid'  => 'bool'
    ];

    protected $searchable = [
        'name', 'description', 'price', 'type'
    ];

    protected $sortable = [
        'reference', 'name', 'price', 'capacity', 'duration', 'age_group', 'is_locked', 'created_at', 'type'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscriptions::class, 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(CourseLesson::class);
    }

    public function assessments()
    {
        return $this->hasMany(CourseAssessment::class);
    }
    
    public function students()
    {
        return $this->belongsToMany(User::class, 'user_course', 'course_id', 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id')->withPivot('role');
    }

    public function lecturers()
    {
        return $this->users()->wherePivot('role', 'lecturer');
    }

    public function teachers()
    {
        return $this->users()->wherePivot('role', 'teacher');
    }
    public function dawah()
    {
        return $this->hasOne(Dawah::class);
    }
    public function userCourseLessons()
    {
        return $this->hasManyThrough(UserCourseLesson::class, CourseLesson::class, 'course_id', 'lesson_id');
    }

}
