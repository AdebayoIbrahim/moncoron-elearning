<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAssessment extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = ['course_id', 'name', 'duration', 'questions'];

    // Automatically cast the 'questions' attribute to an array
    protected $casts = [
        'questions' => 'array',
    ];

    // Define the relationship between CourseAssessment and Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    
    // Define a relationship with the user course assessment submissions
    public function submissions()
    {
        return $this->hasMany(CourseAssessmentSubmission::class);
    }
}
