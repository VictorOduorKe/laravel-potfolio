@extends('layouts.app')
@inject('seo', 'App\Services\SeoService')

@section('title', 'Latest Updates & Tech News | TheTechCraft - Victor Oduor')
@section('description', 'Explore the latest tech news, architecture updates, cybersecurity insights, and project announcements from Victor Oduor.')
@section('canonical', '/news')

@section('structured_data')
<script type="application/ld+json">
{!! json_encode($seo->getBreadcrumbsSchema([
    ['name' => 'Home', 'url' => $seo->resolveCanonicalUrl('/')],
    ['name' => 'Tech News', 'url' => $seo->resolveCanonicalUrl('/news')]
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<div class="container-layout" style="padding-top: 40px; padding-bottom: 60px;">
    <div style="text-align: center; max-width: 680px; margin: 0 auto 56px auto;" class="reveal">
        <span class="section-tag">Tech &amp; Cybersecurity Feed</span>
        <h1 class="section-title">Latest Updates &amp; Industry News</h1>
        <p class="section-description" style="margin: 0 auto;">
            Autonomous tech journalism powered by Google Gemini with live Google Search grounding. Updated regularly with breakthroughs in software architecture, cloud platforms, AI, and cybersecurity.
        </p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 32px; max-width: 860px; margin: 0 auto;">
        @forelse($articles as $article)
        <article class="bento-card reveal" style="padding: 36px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 12px;">
                <span style="font-size: 13px; font-weight: 700; color: var(--secondary); text-transform: uppercase;">
                    {{ $article->source ?: 'TheTechCraft Feed' }}
                </span>
                <span style="font-size: 13px; color: var(--text-muted);">
                    {{ $article->created_at ? $article->created_at->format('F d, Y') : '' }}
                </span>
            </div>

            <h2 style="font-size: 24px; font-weight: 700; color: var(--primary); margin-bottom: 16px; line-height: 1.3;">
                {{ $article->title }}
            </h2>

            <div style="font-size: 15px; color: var(--text); line-height: 1.7; margin-bottom: 24px; white-space: pre-line;">
                {{ $article->content }}
            </div>

            @if($article->source_url)
            <div style="border-top: 1px solid var(--border-light); padding-top: 16px; display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 13px; color: var(--text-light);">Primary Source Grounding</span>
                <a href="{{ $article->source_url }}" target="_blank" rel="noopener" style="font-size: 14px; font-weight: 600; color: var(--accent); display: flex; align-items: center; gap: 4px;">
                    Read on {{ $article->source ?: 'External Source' }} <span class="material-symbols-outlined" style="font-size: 16px;">open_in_new</span>
                </a>
            </div>
            @endif
        </article>
        @empty
        <div class="bento-card" style="text-align: center; padding: 60px 20px;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--text-muted); margin-bottom: 12px;">feed</span>
            <p style="font-size: 16px; color: var(--text-light);">No articles have been generated or published yet.</p>
        </div>
        @endforelse
    </div>

    <div style="margin-top: 48px; display: flex; justify-content: center;">
        {{ $articles->links() }}
    </div>
</div>
@endsection
