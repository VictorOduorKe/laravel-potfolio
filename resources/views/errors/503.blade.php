@extends('layouts.app')

@section('title', '503 - Service Unavailable | TheTechCraft')
@section('robots', 'noindex, nofollow, noarchive')

@section('content')
<div class="container-layout" style="min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 20px;">
    <span class="material-symbols-outlined" style="font-size: 80px; color: var(--accent); margin-bottom: 24px;">construction</span>
    <h1 style="font-size: clamp(36px, 6vw, 64px); font-weight: 800; color: var(--primary); margin-bottom: 16px;">503 &bull; Service Unavailable</h1>
    <p style="font-size: 18px; color: var(--text-light); max-width: 520px; line-height: 1.6; margin-bottom: 36px;">
        Our platform is temporarily offline undergoing planned system maintenance and updates. We will be back online shortly.
    </p>
    <div style="display: flex; gap: 16px; flex-wrap: wrap; justify-content: center;">
        <button onclick="window.location.reload();" class="btn-hero-primary" style="cursor: pointer; border: none; font-size: 15px;">
            <span class="material-symbols-outlined">refresh</span> Try Again
        </button>
    </div>
</div>
@endsection
