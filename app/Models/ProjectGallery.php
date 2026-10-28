<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectGallery extends Model
{
    protected $table = 'project_gallery';

    public $timestamps = false; // Has only created_at

    protected $fillable = [
        'project_id',
        'image_url',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get normalized storage image URL
     */
    public function getImageUrlAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (preg_match('#^https?://#i', $value)) {
            $path = parse_url($value, PHP_URL_PATH);
            if (!empty($path) && (str_starts_with($path, '/storage/') || str_starts_with($path, '/uploads/'))) {
                $value = $path;
            } else {
                return $value;
            }
        }

        $clean = ltrim($value, '/');

        if (str_starts_with($clean, 'uploads/')) {
            return asset('storage/' . $clean);
        }

        if (str_starts_with($clean, 'storage/')) {
            return asset($clean);
        }

        return asset('storage/' . $clean);
    }
}
