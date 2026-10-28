<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify 2FA Token &bull; TheTechCraft</title>
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #0f1527; padding: 20px;">
    <div style="width: 100%; max-width: 440px; background: #ffffff; border-radius: var(--radius-xl); padding: 40px; box-shadow: var(--shadow-lg);">
        <div style="text-align: center; margin-bottom: 28px;">
            <div style="width: 56px; height: 56px; border-radius: 50%; background: rgba(228, 116, 41, 0.1); color: var(--secondary); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto;">
                <span class="material-symbols-outlined" style="font-size: 28px;">mark_email_read</span>
            </div>
            <h1 style="font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 6px;">2-Factor OTP Verification</h1>
            <p style="font-size: 13px; color: var(--text-light); margin: 0;">
                A 6-digit verification code was sent to <strong style="color: var(--text);">{{ $email }}</strong>. It expires in 15 minutes.
            </p>
        </div>

        @if(session('status'))
            <div style="padding: 12px 16px; border-radius: var(--radius-sm); background: rgba(52, 139, 203, 0.1); border: 1px solid var(--accent); color: var(--accent); font-size: 13px; margin-bottom: 20px;">
                {{ session('status') }}
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div style="padding: 12px 16px; border-radius: var(--radius-sm); background: rgba(186, 26, 26, 0.1); border: 1px solid var(--danger); color: var(--danger); font-size: 13px; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.verify') }}" method="POST">
            @csrf
            <div style="margin-bottom: 24px;">
                <label class="form-label" for="otp" style="text-align: center; display: block;">Enter 6-Digit Code</label>
                <input type="text" id="otp" name="otp" class="form-control" required maxlength="6" pattern="[0-9]{6}" placeholder="123456" autofocus style="text-align: center; font-size: 28px; letter-spacing: 8px; font-family: var(--font-mono); font-weight: 700; color: var(--secondary);">
            </div>

            <button type="submit" class="btn-hero-primary" style="width: 100%; padding: 14px; font-size: 15px; margin-bottom: 16px;">
                <span class="material-symbols-outlined" style="font-size: 20px;">verified_user</span> Verify &amp; Access Dashboard
            </button>
        </form>

        <form action="{{ route('admin.resend-otp') }}" method="POST" style="text-align: center;">
            @csrf
            <button type="submit" style="background: transparent; border: none; color: var(--accent); font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: underline;">
                Did not receive the code? Resend Code
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px;">
            <a href="{{ route('admin.login') }}" style="font-size: 13px; color: var(--text-light); text-decoration: none;">&larr; Back to Email Login</a>
        </div>
    </div>
</body>
</html>
