<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password &bull; TheTechCraft Admin</title>
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f1527; padding: clamp(12px, 3vw, 20px);">
    <div style="width: 100%; max-width: 440px; background: #ffffff; border-radius: var(--radius-xl); padding: clamp(24px, 6vw, 40px); box-shadow: var(--shadow-lg);">
        <div style="text-align: center; margin-bottom: 28px;">
            <img src="{{ asset('logo.svg') }}" alt="Logo" style="height: 48px; margin-bottom: 16px;" onerror="this.src='{{ asset('logo.png') }}'">
            <h1 style="font-size: 24px; font-weight: 800; color: var(--primary); margin-bottom: 6px;">Reset Password</h1>
            <p style="font-size: 14px; color: var(--text-light); margin: 0; line-height: 1.5;">
                Enter your registered administrator email to receive a secure, time-limited password reset link.
            </p>
        </div>

        @if(session('status'))
            <div style="padding: 12px 16px; border-radius: var(--radius-sm); background: rgba(52, 139, 203, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 14px; margin-bottom: 20px; line-height: 1.5;">
                {{ session('status') }}
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div style="padding: 12px 16px; border-radius: var(--radius-sm); background: rgba(186, 26, 26, 0.1); border: 1px solid var(--danger); color: var(--danger); font-size: 14px; margin-bottom: 20px; line-height: 1.5;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST">
            @csrf
            <div style="margin-bottom: 24px;">
                <label class="form-label" for="email">Admin Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="admin@thetechcraft.co.ke" value="{{ old('email') }}" autofocus>
            </div>

            <button type="submit" class="btn-hero-primary" style="width: 100%; padding: 14px; font-size: 15px;">
                <span class="material-symbols-outlined" style="font-size: 20px;">send</span> Send Password Reset Link
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; display: flex; flex-direction: column; gap: 8px;">
            <a href="{{ route('admin.login') }}" style="font-size: 13px; color: var(--secondary); font-weight: 600; text-decoration: none;">&larr; Back to Admin Sign In</a>
            <a href="{{ route('home') }}" style="font-size: 13px; color: var(--text-light); text-decoration: none;">Return to Portfolio</a>
        </div>
    </div>
</body>
</html>
