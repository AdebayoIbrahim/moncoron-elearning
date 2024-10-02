<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lessonassessmentresults extends Model
{
    use HasFactory;
    protected $table = 'lesson_assessment_result';
    protected $fillable = ['course_id', 'lesson_id', 'answers', 'score', 'student_id', 'status'];


    // relationship-with-course
    public function course()
    {
        return $this->belongsTo(Course::class, 'id');
    }
    // with-lesson
    public function lesson()
    {
        return $this->belongsTo(CourseLesson::class, 'lesson_number');
    }
    // with-lessonassessment
    public function lessonassessment()
    {
        return $this->belongsTo(Lessonassessment::class, 'id');
    }

    // user-relation-to-assessment-results

    public function userAssessments()
    {
        return $this->belongsTo(User::class);
    }
}
