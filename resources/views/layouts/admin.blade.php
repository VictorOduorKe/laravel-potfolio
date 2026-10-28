<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard') &bull; TheTechCraft Control Panel</title>
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar Navigation -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <img src="{{ asset('logo.svg') }}" alt="TheTechCraft Logo" style="height: 32px; width: auto;" onerror="this.src='{{ asset('logo.png') }}'">
                <div>
                    <h3 style="color: #ffffff; font-size: 16px; margin: 0; font-weight: 700;">TheTechCraft</h3>
                    <span style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">Admin Portal</span>
                </div>
            </div>

            <nav style="flex: 1; padding: 20px 0; overflow-y: auto;">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">dashboard</span> Dashboard
                </a>
                <a href="{{ route('admin.projects.index') }}" class="admin-nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">layers</span> Projects
                </a>
                <a href="{{ route('admin.skills.index') }}" class="admin-nav-link {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">code</span> Skills Bento
                </a>
                <a href="{{ route('admin.roadmaps.index') }}" class="admin-nav-link {{ request()->routeIs('admin.roadmaps.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">alt_route</span> Roadmap Tree
                </a>
                <a href="{{ route('admin.news.index') }}" class="admin-nav-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">newspaper</span> Tech News
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="admin-nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">reviews</span> Testimonials
                </a>
                <a href="{{ route('admin.messages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">mail</span> Messages
                </a>
                <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">settings</span> System Settings
                </a>
                <a href="{{ route('admin.footer.index') }}" class="admin-nav-link {{ request()->routeIs('admin.footer.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">dock_to_bottom</span> Footer Settings
                </a>
                <a href="{{ route('admin.backup.index') }}" class="admin-nav-link {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">backup</span> Database Backups
                </a>
                <a href="{{ route('admin.logs.index') }}" class="admin-nav-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">terminal</span> Activity Logs
                </a>
            </nav>

            <div style="padding: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <a href="{{ route('home') }}" target="_blank" style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #94a3b8; margin-bottom: 14px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">open_in_new</span> View Live Website
                </a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; background: rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 10px; border-radius: var(--radius-sm); font-size: 13px; font-weight: 600;">
                        <span class="material-symbols-outlined" style="font-size: 18px;">logout</span> Secure Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="admin-main">
            <header class="admin-topbar">
                <h2 style="font-size: 20px; font-weight: 700; color: var(--primary); margin: 0;">@yield('header', 'Dashboard')</h2>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
                        <span style="font-size: 13px; font-weight: 600; color: var(--text);">{{ Auth::guard('admin')->user()->username ?? 'Victor Oduor' }}</span>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <!-- Status alerts -->
                @if(session('success'))
                    <div style="padding: 14px 20px; border-radius: var(--radius-md); background: rgba(19, 115, 51, 0.1); border: 1px solid #137333; color: #137333; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; font-weight: 500;">
                        <span class="material-symbols-outlined">check_circle</span>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div style="padding: 14px 20px; border-radius: var(--radius-md); background: rgba(186, 26, 26, 0.1); border: 1px solid var(--danger); color: var(--danger); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; font-weight: 500;">
                        <span class="material-symbols-outlined">error</span>
                        {{ session('error') }}
                    </div>
                @endif
                @if(session('status'))
                    <div style="padding: 14px 20px; border-radius: var(--radius-md); background: rgba(52, 139, 203, 0.1); border: 1px solid var(--accent); color: var(--accent); margin-bottom: 24px; display: flex; align-items: center; gap: 10px; font-weight: 500;">
                        <span class="material-symbols-outlined">info</span>
                        {{ session('status') }}
                    </div>
                @endif
                @if(isset($errors) && $errors->any())
                    <div style="padding: 14px 20px; border-radius: var(--radius-md); background: rgba(186, 26, 26, 0.1); border: 1px solid var(--danger); color: var(--danger); margin-bottom: 24px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; margin-bottom: 6px;">
                            <span class="material-symbols-outlined">error</span> Please correct the following issues:
                        </div>
                        <ul style="margin: 0; padding-left: 24px; font-size: 14px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
