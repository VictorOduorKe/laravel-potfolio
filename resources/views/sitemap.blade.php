{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- 1. Homepage --}}
    <url>
        <loc>{{ $seo->resolveCanonicalUrl('/') }}</loc>
        <lastmod>{{ $siteLastMod->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- 2. Projects Showcase Index --}}
    <url>
        <loc>{{ $seo->resolveCanonicalUrl('/projects') }}</loc>
        <lastmod>{{ ($projects->first()?->updated_at ?? $siteLastMod)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- 3. Individual Published Project Case Studies --}}
    @foreach($projects as $project)
    <url>
        <loc>{{ $seo->resolveCanonicalUrl('/projects/' . $project->id) }}</loc>
        <lastmod>{{ ($project->updated_at ?? $project->created_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- 4. Tech & Cybersecurity News Feed --}}
    <url>
        <loc>{{ $seo->resolveCanonicalUrl('/news') }}</loc>
        <lastmod>{{ ($latestNews?->created_at ?? now())->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- 5. Contact Page --}}
    <url>
        <loc>{{ $seo->resolveCanonicalUrl('/contact') }}</loc>
        <lastmod>{{ now()->startOfMonth()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
</urlset>
