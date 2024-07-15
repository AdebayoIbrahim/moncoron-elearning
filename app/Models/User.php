<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone', 'address', 'state', 'address', 'locale',
        'country', 'dob',
        'status', 'ref', 'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscriptions::class, 'student_id');
    }

    public function courses()
    {
        return $this->hasManyThrough(Course::class, Subscriptions::class, 'student_id', 'id', 'id', 'course_id');
    }

    // public function courseLessons()
    // {
    //     return $this->belongsToMany(Course_Lessons::class, 'user_course_lessons', 'user_id', 'id')
    //                 ->withPivot('completed', 'course_id')
    //                 ->withTimestamps();
    // }

    public function courseLessons()
    {
        return $this->belongsToMany(Course_Lessons::class, 'user_course_lessons', 'lesson_id')
                    ->withPivot('completed')
                    ->withTimestamps();
    }
    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class);
    }



}




