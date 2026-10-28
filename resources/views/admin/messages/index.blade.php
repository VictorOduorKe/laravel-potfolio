@extends('layouts.admin')

@section('title', 'Visitor Inquiries')
@section('header', 'Visitor Inquiries Inbox')

@section('content')
<div class="admin-card" style="padding: 0; overflow: hidden;">
    <div class="admin-table-responsive">
        <table class="data-table">
        <thead>
            <tr>
                <th>Sender</th>
                <th>Contact Info</th>
                <th>Message Excerpt</th>
                <th>Received</th>
                <th style="text-align: right;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($messages as $msg)
            <tr>
                <td>
                    <strong style="color: var(--primary);">{{ $msg->first_name }} {{ $msg->last_name }}</strong>
                </td>
                <td>
                    <a href="mailto:{{ $msg->email }}" style="color: var(--accent); font-weight: 600; display: block;">{{ $msg->email }}</a>
                    @if($msg->phone_number)
                        <span style="font-size: 12px; color: var(--text-muted);">{{ $msg->phone_number }}</span>
                    @endif
                </td>
                <td>
                    <p style="margin: 0; font-size: 13px; color: var(--text);">{{ Str::limit($msg->message, 80) }}</p>
                </td>
                <td>
                    <span style="font-size: 12px; color: var(--text-muted);">{{ $msg->created_at ? $msg->created_at->format('M d, Y H:i') : '' }}</span>
                </td>
                <td style="text-align: right;">
                    <div style="display: inline-flex; gap: 8px;">
                        <a href="{{ route('admin.messages.show', $msg) }}" class="btn-hero-secondary" style="padding: 6px 12px; font-size: 12px;">
                            View
                        </a>
                        <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" onsubmit="return confirm('Delete message?');" style="display: inline;">
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
                <td colspan="5" style="text-align: center; padding: 48px; color: var(--text-light);">
                    Inbox is empty. No messages submitted yet.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

<div style="margin-top: 24px; display: flex; justify-content: center;">
    {{ $messages->links() }}
</div>
@endsection
