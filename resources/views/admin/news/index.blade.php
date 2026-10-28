@extends('layouts.admin')

@section('title', 'Manage Tech News')
@section('header', 'Tech News &amp; AI Bot')

@section('content')
<!-- Bot Controls Header -->
<div class="admin-card" style="margin-bottom: 24px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px;">
    <div>
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 0 0 6px 0;">Automated Tech Journalism Bot</h3>
        <p style="font-size: 13px; color: var(--text-light); margin: 0;">
            Status: <strong style="color: {{ $autoUpdateEnabled ? 'var(--success)' : 'var(--danger)' }};">{{ $autoUpdateEnabled ? 'Enabled' : 'Disabled' }}</strong> &bull;
            Interval: {{ $updateInterval }} Hours &bull; Powered by Gemini 2.5 Flash + Google Search Grounding.
        </p>
    </div>
    <form action="{{ route('admin.news.generate') }}" method="POST">
        @csrf
        <button type="submit" class="btn-hero-primary" style="padding: 10px 20px; font-size: 14px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">bolt</span> Fetch &amp; Publish Article Now
        </button>
    </form>
</div>

<!-- Articles Table -->
<div class="admin-card" style="padding: 0; overflow: hidden;">
    <table class="data-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Source</th>
                <th>Date Generated</th>
                <th>Status</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($articles as $article)
            <tr>
                <td>
                    <strong style="color: var(--primary); font-size: 15px; display: block;">{{ $article->title }}</strong>
                    <span style="font-size: 13px; color: var(--text-light);">{{ Str::limit(strip_tags($article->content), 80) }}</span>
                </td>
                <td>
                    <span class="tag-badge">{{ $article->source ?: 'AI Bot' }}</span>
                </td>
                <td>
                    <span style="font-size: 13px; color: var(--text-muted);">{{ $article->created_at ? $article->created_at->format('M d, Y H:i') : '' }}</span>
                </td>
                <td>
                    <span class="status-badge {{ $article->is_published ? 'status-completed' : 'status-planned' }}">
                        {{ $article->is_published ? 'Published' : 'Hidden' }}
                    </span>
                </td>
                <td style="text-align: right;">
                    <form action="{{ route('admin.news.destroy', $article) }}" method="POST" onsubmit="return confirm('Delete article?');" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 4px 8px; border-radius: 4px; border: none;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-light);">
                    No news articles generated yet. Click "Fetch & Publish Article Now" to generate the first one!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 24px; display: flex; justify-content: center;">
    {{ $articles->links() }}
</div>
@endsection
