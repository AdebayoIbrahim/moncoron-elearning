<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorContent extends Model
{
    use HasFactory;

    protected $fillable = ['content'];
}