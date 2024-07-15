<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dawah extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'age_group',
        'status',
        'lecturer_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'type' => DawahType::class,
        // 'status' => DawahStatus::class,
        // 'age_group' => DawahAgeGroup::class,
    ];

    protected $searchable = [
        'title', 'description'
    ];

    protected $sortable = [
        'title', 'type', 'status', 'created_at'
    ];


}
