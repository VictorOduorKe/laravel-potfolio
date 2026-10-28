@extends('layouts.admin')

@section('title', 'Application Logs')
@section('header', 'Activity &amp; Application Logs')

@section('content')
<div class="admin-card" style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <div>
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 0 0 4px 0;">Live Application Log Stream</h3>
        <span style="font-size: 13px; color: var(--text-muted);">Displaying the last 150 events logged to <code>storage/logs/laravel.log</code>.</span>
    </div>
    <form action="{{ route('admin.logs.clear') }}" method="POST" onsubmit="return confirm('Clear application log?');">
        @csrf
        <button type="submit" class="btn-hero-secondary" style="padding: 8px 16px; font-size: 13px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">delete_sweep</span> Clear Logs
        </button>
    </form>
</div>

<div class="admin-card" style="background: #0f1527; color: #38bdf8; font-family: var(--font-mono); font-size: 12px; line-height: 1.6; max-height: 600px; overflow-y: auto; padding: clamp(14px, 3vw, 20px);">
    @forelse($lines as $line)
        <div style="padding: 4px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); word-break: break-all;">
            {{ $line }}
        </div>
    @empty
        <div style="color: #64748b; text-align: center; padding: 40px;">
            Log file is currently empty.
        </div>
    @endforelse
</div>
@endsection
