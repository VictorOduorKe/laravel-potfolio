@extends('layouts.admin')

@section('title', 'System Settings')
@section('header', 'System, SEO & Automation Settings')

@section('content')
<div class="admin-card" style="max-width: 820px;">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 20px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">General Portfolio Settings</h3>

        <div style="margin-bottom: 20px;">
            <label class="form-label" for="site_title">Portfolio Branding Title</label>
            <input type="text" id="site_title" name="site_title" class="form-control" value="{{ $settings['site_title'] ?? 'Victor Oduor | Full Stack Developer' }}">
        </div>

        <div style="margin-bottom: 24px;">
            <label class="form-label" for="admin_notification_email">Admin Contact Notification Email</label>
            <input type="email" id="admin_notification_email" name="admin_notification_email" class="form-control" value="{{ $settings['admin_notification_email'] ?? 'victoroduor723@gmail.com' }}">
            <span style="font-size: 12px; color: var(--text-muted);">Incoming contact submissions will be dispatched to this address.</span>
        </div>

        <!-- SEO, Search Console & Social Sharing -->
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 32px 0 20px 0; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">Search Engine Optimization (SEO) &amp; Social Sharing</h3>

        <div style="margin-bottom: 20px;">
            <label class="form-label" for="seo_title">Default Search &amp; Social Title</label>
            <input type="text" id="seo_title" name="seo_title" class="form-control" value="{{ $settings['seo_title'] ?? 'Victor Oduor | Full Stack Developer & Cybersecurity Specialist' }}">
            <span style="font-size: 12px; color: var(--text-muted);">Default title tag for public pages when no page-specific override is set.</span>
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label" for="seo_description">Default Meta Description</label>
            <textarea id="seo_description" name="seo_description" class="form-control" rows="3">{{ $settings['seo_description'] ?? 'Victor Oduor is a Full Stack Developer and Cybersecurity Specialist based in Mombasa, Kenya, engineering resilient web platforms, secure PHP/React APIs, and M-PESA payment workflows.' }}</textarea>
            <span style="font-size: 12px; color: var(--text-muted);">Optimal length: 150 - 160 characters. Used for Google search snippets and Open Graph social descriptions.</span>
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label" for="seo_og_image">Default Social Preview Image URL</label>
            <input type="text" id="seo_og_image" name="seo_og_image" class="form-control" value="{{ $settings['seo_og_image'] ?? asset('profile.jpg') }}">
            <span style="font-size: 12px; color: var(--text-muted);">Image displayed on Facebook, LinkedIn, Twitter/X, and messaging apps when the portfolio is shared.</span>
        </div>

        <div style="margin-bottom: 24px;">
            <label class="form-label" for="google_search_console_token">Google Search Console Verification Token</label>
            <input type="text" id="google_search_console_token" name="google_search_console_token" class="form-control" placeholder="e.g. dF94jK82_sLmNoPqRsTuVwXyZ" value="{{ $settings['google_search_console_token'] ?? '' }}">
            <span style="font-size: 12px; color: var(--text-muted);">Paste the content attribute from Google Search Console's HTML tag verification method (`&lt;meta name="google-site-verification" content="..."&gt;`).</span>
        </div>

        <div style="background: rgba(52, 139, 203, 0.08); border: 1px solid var(--border-light); border-left: 4px solid var(--secondary); padding: 16px 20px; border-radius: var(--radius-sm); margin-bottom: 32px;">
            <h4 style="font-size: 14px; font-weight: 700; color: var(--primary); margin: 0 0 6px 0;">Google Search Console &amp; Sitemap Reference</h4>
            <p style="font-size: 13px; color: var(--text-light); line-height: 1.5; margin: 0 0 10px 0;">
                Once verified in Google Search Console, submit your XML sitemap URL under <strong>Index &gt; Sitemaps</strong>:
            </p>
            <div style="font-family: var(--font-mono); font-size: 12px; color: var(--primary); background: #ffffff; padding: 8px 12px; border-radius: var(--radius-xs); border: 1px solid var(--border-light); word-break: break-all; overflow-x: auto;">
                https://thetechcraft.co.ke/sitemap.xml
            </div>
        </div>

        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 32px 0 20px 0; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">AI Tech News Bot Configuration</h3>

        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <input type="checkbox" name="news_auto_update_enabled" value="1" {{ ($settings['news_auto_update_enabled'] ?? '1') == '1' ? 'checked' : '' }} style="width: 18px; height: 18px;">
                <span>Enable Autonomous Tech News Generation</span>
            </label>
            <span style="font-size: 12px; color: var(--text-muted); display: block; margin-top: 4px;">When checked, the cron job `php artisan news:auto-generate` will retrieve and post new trending articles automatically.</span>
        </div>

        <div style="margin-bottom: 24px;">
            <label class="form-label" for="news_update_interval_hours">Generation Interval (Hours)</label>
            <input type="number" id="news_update_interval_hours" name="news_update_interval_hours" class="form-control" min="1" max="168" value="{{ $settings['news_update_interval_hours'] ?? '24' }}">
        </div>

        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin: 32px 0 20px 0; border-bottom: 1px solid var(--border-light); padding-bottom: 10px;">cPanel Cron Scheduler Reference</h3>
        <p style="font-size: 13px; color: var(--text-light); line-height: 1.6; margin-bottom: 16px;">
            To automate news generation in your cPanel <strong>Cron Jobs</strong> manager, add this command running once every 24 hours:
        </p>
        <div style="background: #0f1527; color: #38bdf8; padding: 14px 18px; border-radius: var(--radius-sm); font-family: var(--font-mono); font-size: 13px; margin-bottom: 32px; overflow-x: auto;">
            /usr/local/bin/php /home/yourcpanel/laravel-portfolio/artisan news:auto-generate > /dev/null 2>&1
        </div>

        <button type="submit" class="btn-hero-primary" style="padding: 12px 28px;">Save Settings</button>
    </form>
</div>

<div class="admin-card" style="max-width: 820px; margin-top: 24px;">
    <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
        <span class="material-symbols-outlined" style="color: var(--accent);">lock_reset</span>
        Administrator Security &amp; Password Management
    </h3>
    <p style="font-size: 13px; color: var(--text-light); line-height: 1.6; margin-bottom: 16px;">
        Need to update your master credentials? You can request a single-use, time-limited password reset link sent directly to your registered administrator email address (<strong>{{ Auth::guard('admin')->user()->email ?? 'victoroduor723@gmail.com' }}</strong>).
    </p>

    <form action="{{ route('admin.password.send-reset') }}" method="POST" onsubmit="return confirm('Dispatch a secure password-reset link to your administrator email?');">
        @csrf
        <button type="submit" class="btn-hero-secondary" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; font-size: 14px;">
            <span class="material-symbols-outlined" style="font-size: 18px;">mail</span>
            Dispatch Password Reset Link
        </button>
    </form>
</div>
@endsection
