<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    protected $table = 'social_links';

    protected $fillable = [
        'platform',
        'url',
        'label',
        'icon',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order', 'asc')->orderBy('id', 'asc');
    }

    public static function supportedPlatforms(): array
    {
        return [
            'GitHub' => ['name' => 'GitHub', 'icon' => 'github'],
            'LinkedIn' => ['name' => 'LinkedIn', 'icon' => 'linkedin'],
            'X' => ['name' => 'X (formerly Twitter)', 'icon' => 'x'],
            'Facebook' => ['name' => 'Facebook', 'icon' => 'facebook'],
            'Instagram' => ['name' => 'Instagram', 'icon' => 'instagram'],
            'YouTube' => ['name' => 'YouTube', 'icon' => 'youtube'],
            'Custom' => ['name' => 'Custom Platform', 'icon' => 'globe'],
        ];
    }

    public static function supportedIcons(): array
    {
        return [
            'github' => 'GitHub (Brand)',
            'linkedin' => 'LinkedIn (Brand)',
            'x' => 'X / Twitter (Brand)',
            'facebook' => 'Facebook (Brand)',
            'instagram' => 'Instagram (Brand)',
            'youtube' => 'YouTube (Brand)',
            'globe' => 'Globe / Website',
            'link' => 'Link / URL',
            'mail' => 'Mail / Direct',
            'terminal' => 'Terminal / Shell',
            'share' => 'Share / Network',
        ];
    }
}
