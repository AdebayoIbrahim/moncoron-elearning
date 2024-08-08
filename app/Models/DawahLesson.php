<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DawahLesson extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function dawah()
    {
        return $this->belongsTo(Dawah::class);
    }
}
