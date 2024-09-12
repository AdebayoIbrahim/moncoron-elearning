<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lessonassessment extends Model
{
    use HasFactory;
    protected $table = "lesson_assessments";

    protected $fillable = ['course_id','lesson_id', 'questions'];

    // relationship-with-course
    public function course() {
        $relation = $this->belongsTo(Course::class,'course_id');
        return $relation;
    }

    // relationship-with-assessments

    public function lesson() {
        return $this->belongsTo(CourseLesson::class, 'lesson_number');
    }
}