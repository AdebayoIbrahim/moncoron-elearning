<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lesson_attendance extends Model
{
    use HasFactory;

    protected $table = "lesson_attendance";

    protected $fillable = ['course_id', 'lesson_id', 'name', 'student_id'];
}
