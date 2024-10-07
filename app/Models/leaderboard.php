<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class leaderboard extends Model
{
    use HasFactory;

    protected $table = "leaderboards";
    protected $fillable = ['student_id', 'points'];

    // on-to-one-user-relation
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    // scope-all-leaderboards-also-globals
    public function scopeAllleaderboards()
    {
        return leaderboard::all();
    }

    // scope-local-leaderboards`
    public function scopeLocal($query)
    {
        // get-user-country-or-region
        $user_region = auth()->user()->country;
        $user_data = $query->where('student_id', $user_region);
        return $user_data;
    }
}
