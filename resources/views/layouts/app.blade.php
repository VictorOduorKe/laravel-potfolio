@inject('seo', 'App\Services\SeoService')
@php
    $pageTitle = trim($__env->yieldContent('title')) ?: $seo->getDefaultTitle();
    $pageDescription = trim($__env->yieldContent('description')) ?: $seo->getDefaultDescription();
    $explicitCanonical = trim($__env->yieldContent('canonical'));
    $pageCanonical = $seo->resolveCanonicalUrl($explicitCanonical ?: null);
    $pageOgImage = trim($__env->yieldContent('og_image')) ?: $seo->getDefaultOgImage();
    $pageOgType = trim($__env->yieldContent('og_type')) ?: 'website';
    $robotsDirective = trim($__env->yieldContent('robots')) ?: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    $googleVerification = $seo->getGoogleVerification();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robotsDirective }}">
    <link rel="canonical" href="{{ $pageCanonical }}">

    @if($googleVerification)
    <meta name="google-site-verification" content="{{ $googleVerification }}">
    @endif

    <!-- Open Graph / Facebook -->
    <meta property="og:site_name" content="TheTechCraft">
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="{{ $pageOgType }}">
    <meta property="og:url" content="{{ $pageCanonical }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $pageOgImage }}">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $pageCanonical }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageOgImage }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts & DNS Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Structured Data JSON-LD Injections -->
    @yield('structured_data')

    <!-- Progressive enhancement: content is fully visible by default -->

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    @include('components.header')

    <main id="main-content" style="padding-top: 80px;">
        @yield('content')
    </main>

    @include('components.footer')

    @include('components.chat-widget')

    <!-- Flash message toast if session has status -->
    <div id="toast-container" class="toast-container">
        @if(session('success'))
            <div class="toast toast-success">
                <span class="material-symbols-outlined">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="toast toast-error">
                <span class="material-symbols-outlined">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <!-- Vendor Scripts -->
    <script src="{{ asset('js/vendor/marked.min.js') }}" defer></script>
    <script src="{{ asset('js/vendor/purify.min.js') }}" defer></script>
    <script src="{{ asset('js/vendor/highlight.min.js') }}" defer></script>

    <!-- Application Script -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
