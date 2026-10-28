@extends('layouts.app')

@section('title', '419 - Page Expired | TheTechCraft')
@section('robots', 'noindex, nofollow, noarchive')

@section('content')
<div class="container-layout" style="min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 20px;">
    <span class="material-symbols-outlined" style="font-size: 80px; color: var(--secondary); margin-bottom: 24px;">history</span>
    <h1 style="font-size: clamp(36px, 6vw, 64px); font-weight: 800; color: var(--primary); margin-bottom: 16px;">419 &bull; Page Expired</h1>
    <p style="font-size: 18px; color: var(--text-light); max-width: 520px; line-height: 1.6; margin-bottom: 36px;">
        Your security session token has expired due to inactivity. Please refresh the page to retrieve a fresh token and try again.
    </p>
    <div style="display: flex; gap: 16px; flex-wrap: wrap; justify-content: center;">
        <button onclick="window.location.reload();" class="btn-hero-primary" style="cursor: pointer; border: none; font-size: 15px;">
            <span class="material-symbols-outlined">refresh</span> Refresh Page
        </button>
        <a href="{{ route('home') }}" class="btn-hero-secondary">
            <span class="material-symbols-outlined">home</span> Return to Portfolio
        </a>
    </div>
</div>
@endsection
