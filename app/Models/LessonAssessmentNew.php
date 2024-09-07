<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonAssessmentNew extends Model

{   
    // target-exact-table trusrtissues lol
    protected $table = 'lessons_assessments_new';
    protected $fillable = ['course_id', 'lesson_id', 'questions'];

    protected $casts = [
        'questions' => 'array', 
    ];
}