<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;

    protected $table = 'certificates';
    protected $fillable = ['student_id', 'course_id', 'reference_id'];

    // add-aone-tom-many-user-relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
