<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_text',
        'points',
        'image_path',
        'audio_path',
        'video_path',
        'course_id',
        'lesson_id'
    ];

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}