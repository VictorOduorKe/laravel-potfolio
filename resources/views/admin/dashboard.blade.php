@extends('layouts.admin')

@section('title', 'Admin Overview')
@section('header', 'Dashboard Overview')

@section('content')
<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px;">
    <div class="admin-card" style="margin: 0;">
        <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Total Projects</span>
        <div style="font-size: 32px; font-weight: 800; color: var(--primary); margin: 8px 0 4px 0;">{{ $stats['projects_count'] }}</div>
        <span style="font-size: 13px; color: var(--success); font-weight: 600;">{{ $stats['published_projects'] }} Published</span>
    </div>

    <div class="admin-card" style="margin: 0;">
        <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Skills Stack</span>
        <div style="font-size: 32px; font-weight: 800; color: var(--secondary); margin: 8px 0 4px 0;">{{ $stats['skills_count'] }}</div>
        <span style="font-size: 13px; color: var(--text-light);">Bento Competencies</span>
    </div>

    <div class="admin-card" style="margin: 0;">
        <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Roadmap Milestones</span>
        <div style="font-size: 32px; font-weight: 800; color: var(--accent); margin: 8px 0 4px 0;">{{ $stats['roadmaps_count'] }}</div>
        <span style="font-size: 13px; color: var(--text-light);">Hierarchy Nodes</span>
    </div>

    <div class="admin-card" style="margin: 0;">
        <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Tech Articles</span>
        <div style="font-size: 32px; font-weight: 800; color: var(--primary); margin: 8px 0 4px 0;">{{ $stats['news_count'] }}</div>
        <span style="font-size: 13px; color: var(--text-light);">AI Feed Generated</span>
    </div>

    <div class="admin-card" style="margin: 0;">
        <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Inquiries / Messages</span>
        <div style="font-size: 32px; font-weight: 800; color: var(--secondary); margin: 8px 0 4px 0;">{{ $stats['messages_count'] }}</div>
        <a href="{{ route('admin.messages.index') }}" style="font-size: 13px; color: var(--accent); font-weight: 600; text-decoration: underline;">View Inbox</a>
    </div>
</div>

<!-- Quick Action Shortcuts -->
<div class="admin-card" style="margin-bottom: 32px;">
    <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 16px;">Quick Management Shortcuts</h3>
    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <a href="{{ route('admin.projects.create') }}" class="btn-hero-primary" style="padding: 10px 18px; font-size: 13px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span> Add New Project
        </a>
        <form action="{{ route('admin.news.generate') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-hero-secondary" style="padding: 10px 18px; font-size: 13px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">auto_awesome</span> Trigger AI News Bot
            </button>
        </form>
        <a href="{{ route('admin.backup.index') }}" class="btn-hero-secondary" style="padding: 10px 18px; font-size: 13px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">backup</span> Database Backup
        </a>
        <a href="{{ route('admin.settings.index') }}" class="btn-hero-secondary" style="padding: 10px 18px; font-size: 13px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">tune</span> System Settings
        </a>
    </div>
</div>

<!-- Two-Column Recent Activity -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
    <!-- Recent Messages -->
    <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 0;">Recent Visitor Inquiries</h3>
            <a href="{{ route('admin.messages.index') }}" style="font-size: 13px; color: var(--accent); font-weight: 600;">View All</a>
        </div>
        @if($recentMessages->isNotEmpty())
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($recentMessages as $msg)
                <div style="padding: 12px; background: var(--background); border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <strong style="font-size: 14px; color: var(--primary);">{{ $msg->first_name }} {{ $msg->last_name }}</strong>
                        <span style="font-size: 12px; color: var(--text-muted);">{{ $msg->created_at ? $msg->created_at->diffForHumans() : '' }}</span>
                    </div>
                    <span style="font-size: 12px; color: var(--accent); display: block; margin-bottom: 6px;">{{ $msg->email }}</span>
                    <p style="font-size: 13px; color: var(--text-light); margin: 0;">{{ Str::limit($msg->message, 90) }}</p>
                </div>
                @endforeach
            </div>
        @else
            <p style="font-size: 14px; color: var(--text-muted); margin: 0;">No messages received yet.</p>
        @endif
    </div>

    <!-- Recent News Articles -->
    <div class="admin-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 0;">Latest Tech Articles</h3>
            <a href="{{ route('admin.news.index') }}" style="font-size: 13px; color: var(--accent); font-weight: 600;">Manage</a>
        </div>
        @if($recentNews->isNotEmpty())
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @foreach($recentNews as $article)
                <div style="padding: 12px; background: var(--background); border-radius: var(--radius-sm); border: 1px solid var(--border-light);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <strong style="font-size: 14px; color: var(--primary);">{{ Str::limit($article->title, 50) }}</strong>
                        <span style="font-size: 11px; padding: 2px 6px; border-radius: 4px; background: {{ $article->is_published ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' }}; color: {{ $article->is_published ? '#059669' : '#dc2626' }};">
                            {{ $article->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <span style="font-size: 12px; color: var(--text-muted); display: block;">Source: {{ $article->source ?: 'Gemini AI' }}</span>
                </div>
                @endforeach
            </div>
        @else
            <p style="font-size: 14px; color: var(--text-muted); margin: 0;">No articles generated yet.</p>
        @endif
    </div>
</div>
@endsection
