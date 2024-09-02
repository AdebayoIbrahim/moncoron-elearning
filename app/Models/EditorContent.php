<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'image_path',
        'video_path',
        'audio_path',
    ];
}
