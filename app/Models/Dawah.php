<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dawah extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function lessons()
    {
        return $this->hasMany(DawahLesson::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'dawahs_users', 'dawah_id', 'user_id')
                    ->withPivot('is_teacher', 'role')
                    ->withTimestamps();
    }

    public function teachers()
    {
        return $this->users()->wherePivot('is_teacher', 1);
    }
}
