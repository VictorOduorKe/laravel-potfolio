<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $table = 'testimonials';

    public $timestamps = false; // Has only created_at

    protected $fillable = [
        'client_name',
        'company',
        'content',
        'image_url',
        'rating',
        'is_published',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'rating' => 'integer',
            'display_order' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include published testimonials.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Get author initials for avatar fallback.
     */
    public function getInitialsAttribute(): string
    {
        $name = trim($this->client_name ?? '');
        if (empty($name)) {
            return '?';
        }
        $parts = preg_split('/\s+/', $name);
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
        }
        return strtoupper(substr($name, 0, 2));
    }
}
