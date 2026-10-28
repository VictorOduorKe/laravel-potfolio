@extends('layouts.app')

@section('title', '403 - Access Forbidden | TheTechCraft')
@section('robots', 'noindex, nofollow, noarchive')

@section('content')
<div class="container-layout" style="min-height: 60vh; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 60px 20px;">
    <span class="material-symbols-outlined" style="font-size: 80px; color: #f59e0b; margin-bottom: 24px;">lock_person</span>
    <h1 style="font-size: clamp(36px, 6vw, 64px); font-weight: 800; color: var(--primary); margin-bottom: 16px;">403 &bull; Access Forbidden</h1>
    <p style="font-size: 18px; color: var(--text-light); max-width: 520px; line-height: 1.6; margin-bottom: 36px;">
        You do not have administrative or authorization clearance to view this protected resource.
    </p>
    <div style="display: flex; gap: 16px; flex-wrap: wrap; justify-content: center;">
        <a href="{{ route('home') }}" class="btn-hero-primary">
            <span class="material-symbols-outlined">home</span> Return to Portfolio
        </a>
        <a href="{{ route('admin.login') }}" class="btn-hero-secondary">
            <span class="material-symbols-outlined">admin_panel_settings</span> Admin Login
        </a>
    </div>
</div>
@endsection
