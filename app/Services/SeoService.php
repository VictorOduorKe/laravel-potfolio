<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SeoService
{
    public const CACHE_SETTINGS_KEY = 'portfolio_seo_settings';
    public const CACHE_TTL_SECONDS = 86400; // 24 hours

    /**
     * Retrieve cached SEO settings with sensible defaults.
     *
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        try {
            return Cache::remember(self::CACHE_SETTINGS_KEY, self::CACHE_TTL_SECONDS, function () {
                $settings = Setting::where('setting_key', 'like', 'seo_%')
                    ->orWhere('setting_key', 'google_search_console_token')
                    ->pluck('setting_value', 'setting_key')
                    ->toArray();

                $defaultTitle = 'Victor Oduor | Full Stack Developer & Cybersecurity Specialist';
                $defaultDescription = 'Victor Oduor is a Full Stack Developer and Cybersecurity Specialist based in Mombasa, Kenya, engineering resilient web platforms, secure PHP/React APIs, and M-PESA payment workflows.';
                $defaultOgImage = asset('profile.jpg');

                return [
                    'seo_title' => trim($settings['seo_title'] ?? '') ?: $defaultTitle,
                    'seo_description' => trim($settings['seo_description'] ?? '') ?: $defaultDescription,
                    'seo_og_image' => trim($settings['seo_og_image'] ?? '') ?: $defaultOgImage,
                    'google_search_console_token' => trim($settings['google_search_console_token'] ?? ''),
                ];
            });
        } catch (\Throwable $e) {
            Log::warning('Unable to load SEO settings: ' . $e->getMessage());
            return [
                'seo_title' => 'Victor Oduor | Full Stack Developer & Cybersecurity Specialist',
                'seo_description' => 'Victor Oduor is a Full Stack Developer and Cybersecurity Specialist based in Mombasa, Kenya, engineering resilient web platforms, secure PHP/React APIs, and M-PESA payment workflows.',
                'seo_og_image' => asset('profile.jpg'),
                'google_search_console_token' => '',
            ];
        }
    }

    /**
     * Get the production canonical base URL (e.g. https://thetechcraft.co.ke)
     */
    public function getCanonicalBase(): string
    {
        $base = config('app.canonical_url', 'https://thetechcraft.co.ke');
        return rtrim((string) $base, '/');
    }

    /**
     * Resolve any route, URL, or relative path to its canonical production URL.
     * Guarantees no localhost, 127.0.0.1, or development URL appears in canonical tags.
     */
    public function resolveCanonicalUrl(?string $target = null): string
    {
        $canonicalBase = $this->getCanonicalBase();

        if ($target === null || $target === '') {
            $path = request()->path();
            return $path === '/' ? $canonicalBase : $canonicalBase . '/' . ltrim($path, '/');
        }

        // If an absolute URL was provided
        if (str_starts_with($target, 'http://') || str_starts_with($target, 'https://')) {
            $parsed = parse_url($target);
            $path = $parsed['path'] ?? '/';
            $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';
            $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';

            $pathClean = $path === '/' ? '' : '/' . ltrim($path, '/');
            return $canonicalBase . $pathClean . $query . $fragment;
        }

        // Relative path or fragment
        if (str_starts_with($target, '#')) {
            return $canonicalBase . '/' . $target;
        }

        $path = '/' . ltrim($target, '/');
        return $canonicalBase . ($path === '/' ? '' : $path);
    }

    /**
     * Get default page title.
     */
    public function getDefaultTitle(): string
    {
        return $this->getSettings()['seo_title'];
    }

    /**
     * Get default meta description.
     */
    public function getDefaultDescription(): string
    {
        return $this->getSettings()['seo_description'];
    }

    /**
     * Get default Open Graph preview image URL.
     */
    public function getDefaultOgImage(): string
    {
        return $this->getSettings()['seo_og_image'];
    }

    /**
     * Get Google Search Console verification token, if present.
     */
    public function getGoogleVerification(): ?string
    {
        $token = $this->getSettings()['google_search_console_token'];
        return !empty($token) ? $token : null;
    }

    /**
     * Clear SEO settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_SETTINGS_KEY);
    }

    /**
     * Build Person schema for Victor Oduor with live social links.
     *
     * @return array<string, mixed>
     */
    public function getPersonSchema(): array
    {
        $canonicalBase = $this->getCanonicalBase();

        // Retrieve verified active social links from FooterService
        $sameAs = [];
        try {
            /** @var FooterService $footerService */
            $footerService = app(FooterService::class);
            $socials = $footerService->getSocialLinks();
            foreach ($socials as $social) {
                if (!empty($social->url)) {
                    $sameAs[] = $social->url;
                }
            }
        } catch (\Throwable $e) {
            // Silently continue if database unavailable
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            '@id' => $canonicalBase . '/#person',
            'name' => 'Victor Oduor',
            'givenName' => 'Victor',
            'familyName' => 'Oduor',
            'jobTitle' => 'Full Stack Developer & Cybersecurity Specialist',
            'description' => 'Full Stack Software Engineer and Cybersecurity Specialist based in Mombasa, Kenya. Specializes in monolithic & distributed web architectures, secure PHP/React REST APIs, M-PESA payment integration, Linux server operations, and network diagnostics.',
            'url' => $canonicalBase,
            'image' => asset('profile.jpg'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Mombasa',
                'addressRegion' => 'Mombasa County',
                'addressCountry' => 'KE',
            ],
            'knowsAbout' => [
                'PHP',
                'Laravel Framework',
                'React.js',
                'JavaScript / TypeScript',
                'MySQL Database Architecture',
                'RESTful API Development',
                'M-PESA Daraja Fintech Integration',
                'Cybersecurity Fundamentals',
                'Defensive Engineering & OWASP Mitigation',
                'Linux Systems Administration',
                'Networking & Infrastructure (TCP/IP, Routing, DNS)',
                'Cloud Architecture & DevOps',
                'IT Support & Hardware Diagnostics',
                'Artificial Intelligence & Gemini API Automation',
            ],
            'sameAs' => array_values(array_unique($sameAs)),
        ];
    }

    /**
     * Build WebSite schema for TheTechCraft.
     *
     * @return array<string, mixed>
     */
    public function getWebSiteSchema(): array
    {
        $canonicalBase = $this->getCanonicalBase();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => $canonicalBase . '/#website',
            'name' => 'TheTechCraft',
            'headline' => 'TheTechCraft • Software Engineering & Cybersecurity Platform',
            'url' => $canonicalBase,
            'inLanguage' => 'en-US',
            'description' => 'Official engineering portfolio, technical architecture case studies, and cybersecurity insights by Victor Oduor.',
            'publisher' => [
                '@type' => 'Person',
                'name' => 'Victor Oduor',
                'url' => $canonicalBase,
            ],
        ];
    }

    /**
     * Build SoftwareApplication / CreativeWork schema for a published project.
     *
     * @return array<string, mixed>
     */
    public function getProjectSchema(Project $project): array
    {
        $canonicalBase = $this->getCanonicalBase();
        $projectUrl = $this->resolveCanonicalUrl('/projects/' . $project->id);

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'SoftwareApplication',
            '@id' => $projectUrl . '#software',
            'name' => $project->title,
            'headline' => $project->title,
            'description' => $project->description ?: $project->title,
            'url' => $projectUrl,
            'applicationCategory' => 'DeveloperApplication',
            'operatingSystem' => 'Web Browser',
            'author' => [
                '@type' => 'Person',
                'name' => 'Victor Oduor',
                'url' => $canonicalBase,
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'USD',
                'availability' => 'https://schema.org/OnlineOnly',
            ],
        ];

        if ($project->created_at) {
            $schema['dateCreated'] = $project->created_at->toIso8601String();
            $schema['datePublished'] = $project->created_at->toIso8601String();
        }

        if ($project->image_url) {
            $schema['image'] = $project->image_url;
        }

        if ($project->hasLiveUrl()) {
            $schema['sameAs'] = $project->live_url;
        }

        if ($project->hasRepoUrl()) {
            $schema['codeRepository'] = $project->repo_url;
        }

        return $schema;
    }

    /**
     * Build BreadcrumbList schema.
     *
     * @param array<int, array{name: string, url: string}> $crumbs
     * @return array<string, mixed>
     */
    public function getBreadcrumbsSchema(array $crumbs): array
    {
        $elements = [];
        $position = 1;

        foreach ($crumbs as $crumb) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $crumb['name'],
                'item' => $crumb['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    /**
     * Build ContactPage schema.
     *
     * @return array<string, mixed>
     */
    public function getContactPageSchema(): array
    {
        $canonicalBase = $this->getCanonicalBase();
        $contactUrl = $this->resolveCanonicalUrl('/contact');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            '@id' => $contactUrl . '#contactpage',
            'name' => 'Contact Victor Oduor | Full Stack Developer & Cybersecurity Specialist',
            'description' => 'Contact channel to hire or consult Victor Oduor for full-stack engineering, payment workflows, and cybersecurity architecture.',
            'url' => $contactUrl,
            'mainEntity' => [
                '@type' => 'Person',
                'name' => 'Victor Oduor',
                'url' => $canonicalBase,
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'contactType' => 'Technical Consultation & Project Inquiries',
                    'availableLanguage' => ['English', 'Swahili'],
                    'areaServed' => 'Worldwide',
                ],
            ],
        ];
    }
}
