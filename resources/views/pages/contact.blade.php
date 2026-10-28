@extends('layouts.app')
@inject('seo', 'App\Services\SeoService')

@section('title', 'Get in Touch & Contact | Victor Oduor - TheTechCraft')
@section('description', 'Get in touch with Victor Oduor for enterprise software engineering, resilient cloud infrastructure, payment integrations, and cybersecurity consulting in Kenya.')
@section('canonical', '/contact')

@section('structured_data')
<script type="application/ld+json">
{!! json_encode($seo->getContactPageSchema(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($seo->getBreadcrumbsSchema([
    ['name' => 'Home', 'url' => $seo->resolveCanonicalUrl('/')],
    ['name' => 'Contact', 'url' => $seo->resolveCanonicalUrl('/contact')]
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<div class="container-layout contact-page-wrapper">
    <!-- Standalone Contact Page Header -->
    <div class="contact-page-header reveal">
        <div class="contact-page-badge">
            <span class="badge-dot"></span>
            Direct Technical Channel
        </div>
        <h1 class="contact-page-title">
            Let's Engineer Your Next High-Impact System
        </h1>
        <p class="contact-page-subtitle">
            Based in Mombasa, Kenya (UTC+3) and collaborating worldwide. Reach out directly using the verified contact details or submit a project brief below.
        </p>
    </div>

    <!-- Reusable Two-Column Component -->
    <x-contact-section :is-standalone="true" />
</div>
@endsection
