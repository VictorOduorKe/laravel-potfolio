<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'title',
        'description',
        'technologies',
        'impact_line',
        'image_url',
        'github_repo_url',
        'live_url',
        'github_repo_id',
        'github_sync_last_at',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'github_sync_last_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function gallery(): HasMany
    {
        return $this->hasMany(ProjectGallery::class, 'project_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Get normalized storage image URL
     */
    public function getImageUrlAttribute(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // If it is an external URL
        if (preg_match('#^https?://#i', $value)) {
            $path = parse_url($value, PHP_URL_PATH);
            // If it points to an internal storage/uploads path on some host, normalize to relative
            if (!empty($path) && (str_starts_with($path, '/storage/') || str_starts_with($path, '/uploads/'))) {
                $value = $path;
            } else {
                return $value;
            }
        }

        $clean = ltrim($value, '/');

        // If path points to uploads/ or /uploads/, point to storage/uploads/...
        if (str_starts_with($clean, 'uploads/')) {
            return asset('storage/' . $clean);
        }

        // If path already starts with storage/
        if (str_starts_with($clean, 'storage/')) {
            return asset($clean);
        }

        // Default to storage disk path
        return asset('storage/' . $clean);
    }

    /**
     * Get parsed or auto-detected tech stack tags for the project
     *
     * @return array<int, string>
     */
    public function getTechStackAttribute(): array
    {
        if (!empty($this->technologies)) {
            $tags = array_map('trim', explode(',', (string) $this->technologies));
            return array_values(array_filter($tags, fn ($t) => $t !== ''));
        }

        // Context-aware auto-detection fallback from project description and title
        $known = [
            'PHP', 'Laravel', 'React', 'MySQL', 'Tailwind CSS',
            'Gemini 2.5 Flash', 'Gemini AI', 'REST API', 'JavaScript',
            'TypeScript', 'Python', 'Docker', 'M-PESA', 'Lucide Icons'
        ];

        $combined = ($this->title ?? '') . ' ' . ($this->description ?? '');
        $detected = [];

        foreach ($known as $k) {
            if (stripos($combined, $k) !== false) {
                // Normalize "Gemini 2.5 Flash" vs "Gemini AI"
                if ($k === 'Gemini 2.5 Flash') {
                    $detected[] = 'Gemini AI';
                } elseif ($k === 'Lucide Icons') {
                    $detected[] = 'Lucide React';
                } else {
                    $detected[] = $k;
                }
            }
        }

        return array_values(array_unique($detected));
    }

    /**
     * Check if project has a valid public repository URL
     */
    public function hasRepoUrl(): bool
    {
        return !empty($this->github_repo_url) && filter_var($this->github_repo_url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Check if project has a valid live demo URL
     */
    public function hasLiveUrl(): bool
    {
        return !empty($this->live_url) && filter_var($this->live_url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Optional project status label where existing data supports it
     */
    public function getStatusLabelAttribute(): ?string
    {
        if (!$this->is_published) {
            return 'Draft';
        }

        if ($this->hasLiveUrl()) {
            return 'Live Deployed';
        }

        return 'Completed';
    }
}
