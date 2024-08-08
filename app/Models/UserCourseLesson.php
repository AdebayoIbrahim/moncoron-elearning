<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserCourseLesson extends Model
{
    protected $fillable = ['user_id', 'course_id', 'lesson_id', 'file_path', 'audio_path'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
