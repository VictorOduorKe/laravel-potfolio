<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $table = 'skills';

    public $timestamps = false; // Has only created_at

    protected $fillable = [
        'name',
        'proficiency',
        'category',
        'description',
        'tags',
        'experience_badge',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'proficiency' => 'integer',
            'is_published' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}
