@extends('layouts.admin')

@section('title', 'Manage Projects')
@section('header', 'Portfolio Projects')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <p style="color: var(--text-light); margin: 0; font-size: 14px;">
        Manage your case studies, systems architecture, live URLs, and repositories.
    </p>
    <a href="{{ route('admin.projects.create') }}" class="btn-hero-primary" style="padding: 10px 20px; font-size: 14px;">
        <span class="material-symbols-outlined" style="font-size: 18px;">add</span> Add New Project
    </a>
</div>

<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div style="overflow-x: auto;">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 60px;">Image</th>
                    <th>Title &amp; Impact Line</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $p)
                <tr>
                    <td>
                        @if($p->image_url)
                            <img src="{{ $p->image_url }}" alt="{{ $p->title }}" style="width: 48px; height: 36px; object-fit: cover; border-radius: 4px; border: 1px solid var(--border);" onerror="this.style.opacity='0.4';">
                        @else
                            <div style="width: 48px; height: 36px; background: var(--surface-variant); border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                <span class="material-symbols-outlined" style="font-size: 20px;">image</span>
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong style="color: var(--primary); font-size: 15px; display: block;">{{ $p->title }}</strong>
                        @if($p->impact_line)
                            <span style="font-size: 12px; color: var(--accent); font-weight: 600;">{{ $p->impact_line }}</span>
                        @endif
                        <div style="display: flex; gap: 10px; margin-top: 4px;">
                            @if($p->hasLiveUrl())
                                <a href="{{ $p->live_url }}" target="_blank" rel="noopener noreferrer" style="font-size: 11px; color: var(--success); font-weight: 600; display: inline-flex; align-items: center; gap: 2px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">launch</span> Live
                                </a>
                            @endif
                            @if($p->hasRepoUrl())
                                <a href="{{ $p->github_repo_url }}" target="_blank" rel="noopener noreferrer" style="font-size: 11px; color: var(--text-muted); font-weight: 500; display: inline-flex; align-items: center; gap: 2px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">code</span> Repo
                                </a>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="status-badge {{ $p->is_published ? 'status-completed' : 'status-planned' }}">
                            {{ $p->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 12px; color: var(--text-muted);">{{ $p->created_at ? $p->created_at->format('M d, Y') : '' }}</span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 8px;">
                            <a href="{{ route('admin.projects.edit', $p) }}" class="btn-hero-secondary" style="padding: 6px 12px; font-size: 12px;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">edit</span> Edit
                            </a>
                            <form action="{{ route('admin.projects.destroy', $p) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 6px 10px; border-radius: var(--radius-sm); border: none;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 48px; color: var(--text-light);">
                        No projects created yet. Click "Add New Project" to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
