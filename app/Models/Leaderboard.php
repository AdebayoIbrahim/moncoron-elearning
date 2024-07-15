<?php

Class Leaderboard extends Model
{
    protected $fillable = ['user_id', 'points', 'country', 'course_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}