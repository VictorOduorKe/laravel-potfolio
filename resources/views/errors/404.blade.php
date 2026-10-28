@extends('layouts.app')

@section('title', '404 - Page Not Found | TheTechCraft')
@section('robots', 'noindex, nofollow, noarchive')

@section('content')
<div class="container-layout" style="min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 20px;">
    <span class="material-symbols-outlined" style="font-size: 80px; color: var(--secondary); margin-bottom: 24px;">explore_off</span>
    <h1 style="font-size: clamp(36px, 6vw, 64px); font-weight: 800; color: var(--primary); margin-bottom: 16px;">404 &bull; Page Not Found</h1>
    <p style="font-size: 18px; color: var(--text-light); max-width: 520px; line-height: 1.6; margin-bottom: 36px;">
        The system route you requested does not exist or has been relocated as part of our architecture migration.
    </p>
    <div style="display: flex; gap: 16px;">
        <a href="{{ route('home') }}" class="btn-hero-primary">
            <span class="material-symbols-outlined">home</span> Return to Portfolio
        </a>
        <a href="{{ route('news') }}" class="btn-hero-secondary">
            Read Tech News
        </a>
    </div>
</div>
@endsection
