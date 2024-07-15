<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_Lessons extends Model
{
    use HasFactory;

    // Specify the correct table name
    protected $table = 'course_lessons';

    
    protected $fillable = [
        'course_id',
        'name',
        'description',
        'video',
        'audio',
        'status'
    ];
    

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'user_course_lessons', 'id', 'user_id')
    //                 ->withPivot('completed', 'course_id')
    //                 ->withTimestamps();
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_course_lessons', 'lesson_id')
                    ->withPivot('completed')
                    ->withTimestamps();
    }
}
