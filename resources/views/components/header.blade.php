<header class="site-header">
    <div class="container-layout site-header-inner" style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
        <!-- Brand Logo -->
        <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 5px; text-decoration: none; min-width: 0; flex-shrink: 1;">
            <img src="{{ asset('logo.svg') }}" alt="TheTechCraft Logo" style="height: 38px; width: auto; flex-shrink: 0;" onerror="this.src='{{ asset('logo.png') }}'">
            <span style="font-family: var(--font-heading); background-color: var(--primary); font-weight: 800; font-size: 20px; border-radius: 25%; padding: 5px; color: var(--surface); letter-spacing: -0.5px; white-space: nowrap;">The</span>
            <span style="font-family: var(--font-heading); font-weight: 800; font-size: 20px; color: var(--secondary); letter-spacing: -0.5px; white-space: nowrap;">Tech</span>
            <span style="font-family: var(--font-heading); font-weight: 800; font-size: 20px; color: var(--accent); letter-spacing: -0.5px; white-space: nowrap;">Craft</span>
        </a>

        <!-- Desktop Navigation -->
        <nav style="display: flex; align-items: center; gap: 8px;" class="desktop-nav">
            <a href="{{ route('home') }}#projects" class="nav-link">Projects</a>
            <a href="{{ route('home') }}#skills" class="nav-link">Skills</a>
            <a href="{{ route('home') }}#roadmap" class="nav-link">Roadmap</a>
            <a href="{{ route('news') }}" class="nav-link {{ request()->routeIs('news') ? 'active' : '' }}">News</a>
            <a href="{{ route('home') }}#contact" class="btn-hero-primary" style="padding: 9px 20px; font-size: 14px; margin-left: 12px;">Contact Me</a>
        </nav>

        <!-- Mobile Menu Toggle Button -->
        <button id="mobile-menu-toggle" type="button" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobile-drawer" class="mobile-toggle">
            <span class="material-symbols-outlined" style="font-size: 28px;">menu</span>
        </button>
    </div>
</header>

<!-- Mobile Navigation Drawer -->
<div id="mobile-drawer-backdrop" class="hidden" style="position: fixed; inset: 0; background: rgba(15, 21, 39, 0.6); backdrop-filter: blur(4px); z-index: 105;"></div>
<div id="mobile-drawer" class="hidden" style="position: fixed; top: 0; right: 0; bottom: 0; width: 280px; max-width: calc(100vw - 32px); background: #ffffff; z-index: 110; box-shadow: -4px 0 24px rgba(0,0,0,0.15); display: flex; flex-direction: column; padding: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; border-bottom: 1px solid var(--border); padding-bottom: 16px;">
        <span style="font-weight: 800; font-family: var(--font-heading); color: var(--primary);">TheTechCraft</span>
        <button id="mobile-drawer-close" type="button" aria-label="Close navigation menu" style="background: transparent; border: none; color: var(--text-light); padding: 8px; min-width: 44px; min-height: 44px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer;">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>
    <nav style="display: flex; flex-direction: column; gap: 12px;">
        <a href="{{ route('home') }}#projects" class="nav-link" style="font-size: 16px; padding: 12px 16px;">Projects</a>
        <a href="{{ route('home') }}#skills" class="nav-link" style="font-size: 16px; padding: 12px 16px;">Skills</a>
        <a href="{{ route('home') }}#roadmap" class="nav-link" style="font-size: 16px; padding: 12px 16px;">Roadmap</a>
        <a href="{{ route('news') }}" class="nav-link" style="font-size: 16px; padding: 12px 16px;">News Feed</a>
        <a href="{{ route('home') }}#contact" class="btn-hero-primary" style="margin-top: 20px; text-align: center;">Get In Touch</a>
    </nav>
</div>

<style>
.mobile-toggle {
    display: none;
    background: transparent;
    border: none;
    padding: 8px;
    color: var(--primary);
    cursor: pointer;
    flex-shrink: 0;
    min-width: 44px;
    min-height: 44px;
    align-items: center;
    justify-content: center;
}
@media (max-width: 768px) {
    .desktop-nav { display: none !important; }
    .mobile-toggle { display: inline-flex !important; }
}
.hidden { display: none !important; }
</style>
