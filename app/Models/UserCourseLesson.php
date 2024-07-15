<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourseLesson extends Model
{
    use HasFactory;

    protected $table = 'user_course_lessons';

    protected $fillable = ['user_id', 'course_id', 'lesson_id', 'completed'];
}
