<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    public $timestamps = false; // Has only created_at

    protected $fillable = [
        'title',
        'content',
        'published',
        'is_published',
        'source',
        'source_url',
    ];

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'is_published' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}
