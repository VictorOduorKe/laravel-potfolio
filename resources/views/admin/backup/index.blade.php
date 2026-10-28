@extends('layouts.admin')

@section('title', 'Database Backups')
@section('header', 'Database Backup Manager')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 340px; gap: 32px; align-items: start;">
    <!-- Left: Existing Backups List -->
    <div class="admin-card" style="padding: 0; overflow: hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Filename</th>
                    <th>Size</th>
                    <th>Created</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($files as $file)
                <tr>
                    <td>
                        <strong style="color: var(--primary); font-family: var(--font-mono); font-size: 13px;">{{ $file['name'] }}</strong>
                    </td>
                    <td>
                        <span class="tag-badge">{{ $file['size'] }}</span>
                    </td>
                    <td>
                        <span style="font-size: 13px; color: var(--text-muted);">{{ $file['modified'] }}</span>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: inline-flex; gap: 8px;">
                            <a href="{{ route('admin.backup.download', $file['name']) }}" class="btn-hero-secondary" style="padding: 6px 12px; font-size: 12px;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">download</span> Download
                            </a>
                            <form action="{{ route('admin.backup.destroy', $file['name']) }}" method="POST" onsubmit="return confirm('Delete this SQL dump?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 6px 8px; border-radius: 4px; border: none;">
                                    <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 36px; color: var(--text-light);">
                        No database backups generated yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Right: Trigger Backup Card -->
    <div class="admin-card">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 12px;">Generate Snapshot</h3>
        <p style="font-size: 13px; color: var(--text-light); line-height: 1.6; margin-bottom: 20px;">
            Generates a complete, self-contained SQL dump of all portfolio tables including schema definitions and database rows.
        </p>
        <form action="{{ route('admin.backup.create') }}" method="POST">
            @csrf
            <button type="submit" class="btn-hero-primary" style="width: 100%; padding: 12px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">database</span> Create New SQL Backup
            </button>
        </form>
    </div>
</div>
@endsection
