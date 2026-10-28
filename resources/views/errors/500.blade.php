@extends('layouts.app')

@section('title', '500 - Server Error | TheTechCraft')
@section('robots', 'noindex, nofollow, noarchive')

@section('content')
<div class="container-layout" style="min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 20px;">
    <span class="material-symbols-outlined" style="font-size: 80px; color: #ef4444; margin-bottom: 24px;">error_outline</span>
    <h1 style="font-size: clamp(36px, 6vw, 64px); font-weight: 800; color: var(--primary); margin-bottom: 16px;">500 &bull; Server Error</h1>
    <p style="font-size: 18px; color: var(--text-light); max-width: 540px; line-height: 1.6; margin-bottom: 36px;">
        We encountered an unexpected internal issue while processing your request. Our technical team has been logged of this event, and we are working to resolve it promptly.
    </p>
    <div style="display: flex; gap: 16px; flex-wrap: wrap; justify-content: center;">
        <a href="{{ route('home') }}" class="btn-hero-primary">
            <span class="material-symbols-outlined">home</span> Return to Portfolio
        </a>
        <a href="{{ route('contact') }}" class="btn-hero-secondary">
            <span class="material-symbols-outlined">mail</span> Contact Support
        </a>
    </div>
</div>
@endsection
