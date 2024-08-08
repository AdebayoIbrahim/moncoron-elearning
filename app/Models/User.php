<?php

namespace App\Models;

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
        'phone',
        'address',
        'state',
        'locale',
        'country',
        'dob',
        'status',
        'ref',
        'role'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscriptions::class, 'student_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id')->withPivot('role');
    }

    public function courseLessons()
    {
        return $this->belongsToMany(CourseLesson::class, 'user_course_lessons', 'user_id', 'lesson_id')
                    ->withPivot('completed')
                    ->withTimestamps();
    }

    public function leaderboard()
    {
        return $this->hasOne(Leaderboard::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function isAdmin()
    {
        // Assuming you have a 'role' attribute in your users table
        // and 'admin' is the value for admin users
        return $this->role === 'admin';
    }

    public function assignedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id')->withPivot('role');
    }

    public function teachingCourses()
    {
        return $this->assignedCourses()->wherePivot('role', 'teacher');
    }

    public function lecturingCourses()
    {
        return $this->assignedCourses()->wherePivot('role', 'lecturer');
    }

    public function dawahs()
    {
        return $this->belongsToMany(Dawah::class, 'dawahs_users', 'user_id', 'dawah_id')
                    ->withPivot('is_teacher')
                    ->withTimestamps();
    }
}
