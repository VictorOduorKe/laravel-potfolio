<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Admin Password &bull; TheTechCraft</title>
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f1527; padding: clamp(14px, 3vw, 24px);">
    <div style="width: 100%; max-width: 460px; background: #ffffff; border-radius: var(--radius-xl); padding: clamp(22px, 5vw, 40px); box-shadow: var(--shadow-lg);">
        <div style="text-align: center; margin-bottom: 28px;">
            <img src="{{ asset('logo.svg') }}" alt="Logo" style="height: 48px; margin-bottom: 16px;" onerror="this.src='{{ asset('logo.png') }}'">
            <h1 style="font-size: 24px; font-weight: 800; color: var(--primary); margin-bottom: 6px;">New Master Password</h1>
            <p style="font-size: 14px; color: var(--text-light); margin: 0; line-height: 1.5;">
                Enter and confirm a strong, secure password for your administrator account.
            </p>
        </div>

        @if(session('status'))
            <div style="padding: 12px 16px; border-radius: var(--radius-sm); background: rgba(52, 139, 203, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 14px; margin-bottom: 20px; line-height: 1.5;">
                {{ session('status') }}
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div style="padding: 12px 16px; border-radius: var(--radius-sm); background: rgba(186, 26, 26, 0.1); border: 1px solid var(--danger); color: var(--danger); font-size: 14px; margin-bottom: 20px; line-height: 1.5;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div style="margin-bottom: 20px;">
                <label class="form-label" for="email">Admin Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="admin@thetechcraft.co.ke" value="{{ old('email', $email ?? '') }}" {{ !empty($email) ? 'readonly' : '' }}>
            </div>

            <div style="margin-bottom: 20px;">
                <label class="form-label" for="password">New Password</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Minimum 8 characters" autofocus>
                <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">
                    Must be at least 8 characters with mixed case, numbers, and symbols.
                </span>
            </div>

            <div style="margin-bottom: 28px;">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required placeholder="Re-enter new password">
            </div>

            <button type="submit" class="btn-hero-primary" style="width: 100%; padding: 14px; font-size: 15px;">
                <span class="material-symbols-outlined" style="font-size: 20px;">verified_user</span> Reset Password &amp; Secure Account
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('admin.login') }}" style="font-size: 13px; color: var(--secondary); font-weight: 600; text-decoration: none;">&larr; Back to Admin Sign In</a>
        </div>
    </div>
</body>
</html>
