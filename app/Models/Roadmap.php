<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Roadmap extends Model
{
    protected $table = 'roadmaps';

    public $timestamps = false; // Has only created_at

    protected $fillable = [
        'title',
        'description',
        'status',
        'target_date',
        'parent_id',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
            'is_published' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Roadmap::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Roadmap::class, 'parent_id');
    }
}
