<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DawahPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'dawah_id', 
        'user_id', 
        'title', 
        'content', 
        'type', 
        'image', 
        'video', 
        'audio', 
        'attachment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
