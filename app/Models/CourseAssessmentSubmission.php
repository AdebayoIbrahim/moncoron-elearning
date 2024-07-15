<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAssessmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_assessment_id', 'answers', 'score'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assessment()
    {
        return $this->belongsTo(CourseAssessment::class, 'course_assessment_id');
    }
}
