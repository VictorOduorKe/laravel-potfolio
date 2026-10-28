@extends('layouts.admin')

@section('title', 'Inquiry from ' . $contact->first_name)
@section('header', 'Message Detail')

@section('content')
<div class="admin-card" style="max-width: 700px;">
    <div style="border-bottom: 1px solid var(--border); padding-bottom: 20px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 20px; font-weight: 800; color: var(--primary); margin: 0 0 6px 0;">
                {{ $contact->first_name }} {{ $contact->last_name }}
            </h3>
            <span style="font-size: 13px; color: var(--text-muted);">
                Received {{ $contact->created_at ? $contact->created_at->format('l, F d, Y at H:i') : '' }} &bull; IP: {{ $contact->ip_address }}
            </span>
        </div>
        <a href="mailto:{{ $contact->email }}?subject={{ urlencode('Re: Portfolio Inquiry') }}" class="btn-hero-primary" style="padding: 10px 20px; font-size: 13px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">reply</span> Reply via Email
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; background: var(--background); padding: 16px; border-radius: var(--radius-sm);">
        <div>
            <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Email</span>
            <div style="font-size: 14px; font-weight: 600; color: var(--accent); margin-top: 2px;">{{ $contact->email }}</div>
        </div>
        <div>
            <span style="font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Phone</span>
            <div style="font-size: 14px; font-weight: 600; color: var(--text); margin-top: 2px;">{{ $contact->phone_number ?: 'Not provided' }}</div>
        </div>
    </div>

    <div style="margin-bottom: 32px;">
        <span style="font-size: 13px; font-weight: 700; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 10px;">Message Body:</span>
        <div style="background: #ffffff; border: 1.5px solid var(--border); border-radius: var(--radius-md); padding: 20px; font-size: 15px; line-height: 1.7; color: var(--text); white-space: pre-wrap;">{{ $contact->message }}</div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <a href="{{ route('admin.messages.index') }}" class="btn-hero-secondary">&larr; Back to Inbox</a>
        <form action="{{ route('admin.messages.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this message permanently?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 10px 18px; border-radius: var(--radius-sm); border: none; font-weight: 600; font-size: 13px;">
                Delete Message
            </button>
        </form>
    </div>
</div>
@endsection
