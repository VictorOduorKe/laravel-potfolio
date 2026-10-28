@extends('layouts.app')
@inject('seo', 'App\Services\SeoService')

@section('title', $project->title . ' | Case Study - Victor Oduor')
@section('description', Str::limit($project->description ?? $project->title, 160))
@section('canonical', '/projects/' . $project->id)
@if($project->image_url)
    @section('og_image', $project->image_url)
@endif

@section('structured_data')
<script type="application/ld+json">
{!! json_encode($seo->getProjectSchema($project), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($seo->getBreadcrumbsSchema([
    ['name' => 'Home', 'url' => $seo->resolveCanonicalUrl('/')],
    ['name' => 'Projects', 'url' => $seo->resolveCanonicalUrl('/projects')],
    ['name' => $project->title, 'url' => $seo->resolveCanonicalUrl('/projects/' . $project->id)]
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<div class="container-layout" style="padding-top: 32px; padding-bottom: 80px;">
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" style="margin-bottom: 28px;">
        <ol style="list-style: none; display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--text-muted); flex-wrap: wrap;">
            <li>
                <a href="{{ route('home') }}" style="color: var(--text-light); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">home</span> Home
                </a>
            </li>
            <li><span style="opacity: 0.5;">/</span></li>
            <li>
                <a href="{{ route('home') }}#projects" style="color: var(--text-light); text-decoration: none;">Projects</a>
            </li>
            <li><span style="opacity: 0.5;">/</span></li>
            <li aria-current="page" style="color: var(--primary); font-weight: 600;">
                {{ Str::limit($project->title, 40) }}
            </li>
        </ol>
    </nav>

    <!-- Project Header & Meta -->
    <header class="reveal active" style="margin-bottom: 40px;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; margin-bottom: 16px;">
            <span class="section-tag" style="margin-bottom: 0;">Project Case Study</span>
            @if($project->status_label)
                <span class="status-badge {{ $project->hasLiveUrl() ? 'status-completed' : 'status-progress' }}" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span>
                    {{ $project->status_label }}
                </span>
            @endif
            @if($project->created_at)
                <span style="font-size: 13px; color: var(--text-muted); display: inline-flex; align-items: center; gap: 4px;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">calendar_today</span>
                    {{ $project->created_at->format('M Y') }}
                </span>
            @endif
        </div>

        <h1 style="font-size: clamp(28px, 4.5vw, 44px); font-weight: 800; color: var(--primary); margin-bottom: 16px; line-height: 1.2;">
            {{ $project->title }}
        </h1>

        @if($project->impact_line)
            <div style="font-size: clamp(16px, 2vw, 19px); font-weight: 600; color: var(--secondary); margin-bottom: 24px; line-height: 1.5; max-width: 860px;">
                {{ $project->impact_line }}
            </div>
        @endif

        <!-- Action Links: Live Demo & Source Code & Back -->
        <div style="display: flex; flex-wrap: wrap; gap: 14px; align-items: center; padding-top: 8px;">
            @if($project->hasLiveUrl())
                <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="btn-hero-primary" aria-label="Open live demo for {{ $project->title }} in a new tab">
                    <span class="material-symbols-outlined" style="font-size: 20px;">launch</span>
                    Live Demo
                </a>
            @endif

            @if($project->hasRepoUrl())
                <a href="{{ $project->github_repo_url }}" target="_blank" rel="noopener noreferrer" class="btn-hero-secondary" aria-label="View source code on GitHub for {{ $project->title }} in a new tab">
                    <span class="material-symbols-outlined" style="font-size: 20px;">code</span>
                    Source Code
                </a>
            @endif

            <a href="{{ route('home') }}#projects" class="btn-hero-secondary" style="margin-left: auto;">
                <span class="material-symbols-outlined" style="font-size: 20px;">arrow_back</span>
                Back to Showcase
            </a>
        </div>
    </header>

    <!-- Main Visual / Showcase Carousel -->
    <section class="reveal active" style="margin-bottom: 48px;">
        @php
            $hasGallery = $project->gallery && $project->gallery->count() > 0;
            $allImages = collect();
            if ($project->image_url) {
                $allImages->push($project->image_url);
            }
            if ($hasGallery) {
                foreach ($project->gallery as $g) {
                    $allImages->push($g->image_url);
                }
            }
        @endphp

        @if($allImages->isNotEmpty())
            <div class="project-gallery-carousel" id="project-detail-gallery" style="background: #0f1527; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border); box-shadow: var(--shadow-md); position: relative;">
                <div class="gallery-viewport" style="width: 100%; aspect-ratio: 16/9; max-height: 540px; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #0b1020;">
                    @foreach($allImages as $idx => $img)
                        <div class="gallery-slide {{ $idx === 0 ? 'active' : '' }}" data-slide="{{ $idx }}" style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: {{ $idx === 0 ? '1' : '0' }}; transition: opacity 0.35s ease-in-out; pointer-events: {{ $idx === 0 ? 'auto' : 'none' }}; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ $img }}" alt="{{ $project->title }} preview screenshot {{ $idx + 1 }}" loading="{{ $idx === 0 ? 'eager' : 'lazy' }}" style="width: 100%; height: 100%; object-fit: contain; background: #0f1527;" onerror="this.style.opacity='0.6';">
                        </div>
                    @endforeach
                </div>

                @if($allImages->count() > 1)
                    <!-- Carousel Controls -->
                    <button type="button" class="carousel-arrow carousel-prev" id="gallery-prev-btn" aria-label="Previous screenshot" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 44px; height: 44px; border-radius: 50%; background: rgba(15, 21, 39, 0.75); backdrop-filter: blur(8px); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);">
                        <span class="material-symbols-outlined" style="font-size: 24px;">chevron_left</span>
                    </button>
                    <button type="button" class="carousel-arrow carousel-next" id="gallery-next-btn" aria-label="Next screenshot" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 44px; height: 44px; border-radius: 50%; background: rgba(15, 21, 39, 0.75); backdrop-filter: blur(8px); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2); cursor: pointer; display: flex; align-items: center; justify-content: center; transition: var(--transition);">
                        <span class="material-symbols-outlined" style="font-size: 24px;">chevron_right</span>
                    </button>

                    <!-- Indicators -->
                    <div style="position: absolute; bottom: 16px; left: 0; right: 0; display: flex; justify-content: center; gap: 8px; z-index: 5;">
                        @foreach($allImages as $idx => $img)
                            <button type="button" class="gallery-dot {{ $idx === 0 ? 'active' : '' }}" data-target="{{ $idx }}" aria-label="Go to slide {{ $idx + 1 }}" style="width: 10px; height: 10px; border-radius: 50%; border: none; background: {{ $idx === 0 ? 'var(--secondary)' : 'rgba(255, 255, 255, 0.4)' }}; cursor: pointer; transition: var(--transition); padding: 0;"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div style="aspect-ratio: 21/9; max-height: 320px; background: var(--surface-variant); border-radius: var(--radius-lg); border: 1px solid var(--border); display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--text-muted); gap: 12px;">
                <span class="material-symbols-outlined" style="font-size: 56px; color: var(--primary);">terminal</span>
                <span style="font-size: 15px; font-weight: 600;">System Architecture &amp; Code Implementation</span>
            </div>
        @endif
    </section>

    <!-- Two-Column Layout: Details & Sidebar -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 48px; align-items: start;">
        <!-- Left: In-depth Description & Architecture -->
        <article class="reveal active" style="flex: 2;">
            <div class="bento-card" style="padding: 36px; margin-bottom: 32px;">
                <h2 style="font-size: 22px; font-weight: 700; color: var(--primary); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span class="material-symbols-outlined" style="color: var(--secondary); font-size: 24px;">description</span>
                    System Overview &amp; Implementation Details
                </h2>

                <div style="font-size: 16px; color: var(--text); line-height: 1.8; white-space: pre-line;">
                    {{ $project->description }}
                </div>
            </div>

            @if($project->impact_line)
                <div class="bento-card" style="padding: 28px; border-left: 4px solid var(--secondary); margin-bottom: 32px;">
                    <h3 style="font-size: 16px; font-weight: 700; text-transform: uppercase; color: var(--secondary); letter-spacing: 0.5px; margin-bottom: 8px;">
                        Core Impact &amp; Value Delivery
                    </h3>
                    <p style="font-size: 15px; color: var(--text-light); line-height: 1.6; margin: 0;">
                        {{ $project->impact_line }}
                    </p>
                </div>
            @endif
        </article>

        <!-- Right: Technical Specification Sidebar -->
        <aside class="reveal active" style="flex: 1;">
            <div class="bento-card" style="padding: 32px; margin-bottom: 32px;">
                <h2 style="font-size: 18px; font-weight: 700; color: var(--primary); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="color: var(--accent); font-size: 22px;">stacks</span>
                    Technology Stack
                </h2>

                @if(!empty($project->tech_stack))
                    <div style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 24px;">
                        @foreach($project->tech_stack as $tech)
                            <span class="tag-badge" style="background: var(--surface-variant); color: var(--primary); font-size: 13px; font-weight: 600; padding: 6px 14px;">
                                {{ $tech }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 20px;">
                        Full-stack software engineering stack.
                    </p>
                @endif

                <div style="border-top: 1px solid var(--border); padding-top: 20px;">
                    <h3 style="font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin-bottom: 12px;">
                        Project Availability &amp; Access
                    </h3>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 14px;">
                        @if($project->hasLiveUrl())
                            <li style="display: flex; align-items: center; gap: 10px; font-size: 14px;">
                                <span class="material-symbols-outlined" style="color: var(--success); font-size: 20px;">check_circle</span>
                                <span style="font-weight: 600;">Live Demo:</span>
                                <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" style="color: var(--accent); font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 180px;">
                                    {{ preg_replace('#^https?://#', '', rtrim($project->live_url, '/')) }}
                                </a>
                            </li>
                        @else
                            <li style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: var(--text-muted);">
                                <span class="material-symbols-outlined" style="font-size: 20px;">cloud_off</span>
                                <span>Deployment: Internal / Private Environment</span>
                            </li>
                        @endif

                        @if($project->hasRepoUrl())
                            <li style="display: flex; align-items: center; gap: 10px; font-size: 14px;">
                                <span class="material-symbols-outlined" style="color: var(--primary); font-size: 20px;">code</span>
                                <span style="font-weight: 600;">Repository:</span>
                                <a href="{{ $project->github_repo_url }}" target="_blank" rel="noopener noreferrer" style="color: var(--accent); font-weight: 600;">
                                    GitHub Source
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Inquire / Contact Callout -->
            <div class="bento-card" style="padding: 28px; background: linear-gradient(145deg, #ffffff 0%, var(--surface-variant) 100%);">
                <h3 style="font-size: 17px; font-weight: 700; color: var(--primary); margin-bottom: 8px;">
                    Interested in similar architecture?
                </h3>
                <p style="font-size: 14px; color: var(--text-light); line-height: 1.5; margin-bottom: 16px;">
                    I design and implement custom systems, AI automations, and secure APIs tailored to high-reliability requirements.
                </p>
                <a href="{{ route('home') }}#contact" class="btn-hero-primary" style="width: 100%; justify-content: center; padding: 12px 20px; font-size: 14px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">mail</span>
                    Get in Touch
                </a>
            </div>
        </aside>
    </div>

    <!-- Other Projects Section -->
    @if($otherProjects->isNotEmpty())
        <section style="margin-top: 80px; padding-top: 48px; border-top: 1px solid var(--border);">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
                <div>
                    <span class="section-tag" style="margin-bottom: 6px;">Discover More</span>
                    <h2 style="font-size: 26px; font-weight: 800; color: var(--primary); margin: 0;">Other Engineering Projects</h2>
                </div>
                <a href="{{ route('home') }}#projects" class="btn-hero-secondary" style="padding: 8px 18px; font-size: 14px;">
                    View All Projects
                </a>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
                @foreach($otherProjects as $other)
                    <div class="project-compact-card">
                        <div class="card-image-wrapper">
                            @if($other->image_url)
                                <img src="{{ $other->image_url }}" alt="{{ $other->title }}" loading="lazy" class="card-thumb" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
                                <div class="card-thumb card-thumb-placeholder" style="display: none;">
                                    <span class="material-symbols-outlined">terminal</span>
                                </div>
                            @else
                                <div class="card-thumb card-thumb-placeholder">
                                    <span class="material-symbols-outlined">terminal</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">{{ $other->title }}</h3>
                            <p class="card-excerpt">{{ Str::limit($other->description, 100) }}</p>
                            <div class="card-actions">
                                <a href="{{ route('projects.show', $other) }}" class="btn-card-details">
                                    View Details <span class="material-symbols-outlined">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Project detail gallery slider logic
    const gallery = document.getElementById('project-detail-gallery');
    if (!gallery) return;

    const slides = gallery.querySelectorAll('.gallery-slide');
    const dots = gallery.querySelectorAll('.gallery-dot');
    const prevBtn = document.getElementById('gallery-prev-btn');
    const nextBtn = document.getElementById('gallery-next-btn');

    if (slides.length <= 1) return;

    let currentIndex = 0;

    const showSlide = (index) => {
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;
        currentIndex = index;

        slides.forEach((slide, i) => {
            if (i === currentIndex) {
                slide.style.opacity = '1';
                slide.style.pointerEvents = 'auto';
                slide.classList.add('active');
            } else {
                slide.style.opacity = '0';
                slide.style.pointerEvents = 'none';
                slide.classList.remove('active');
            }
        });

        dots.forEach((dot, i) => {
            if (i === currentIndex) {
                dot.style.background = 'var(--secondary)';
                dot.classList.add('active');
            } else {
                dot.style.background = 'rgba(255, 255, 255, 0.4)';
                dot.classList.remove('active');
            }
        });
    };

    if (prevBtn) {
        prevBtn.addEventListener('click', () => showSlide(currentIndex - 1));
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => showSlide(currentIndex + 1));
    }

    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            const target = parseInt(dot.getAttribute('data-target'), 10);
            showSlide(target);
        });
    });

    // Keyboard navigation
    gallery.setAttribute('tabindex', '0');
    gallery.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            showSlide(currentIndex - 1);
        } else if (e.key === 'ArrowRight') {
            showSlide(currentIndex + 1);
        }
    });

    // Touch swipe support
    let touchStartX = 0;
    gallery.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    gallery.addEventListener('touchend', (e) => {
        const touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 40) {
            if (diff > 0) {
                showSlide(currentIndex + 1);
            } else {
                showSlide(currentIndex - 1);
            }
        }
    }, { passive: true });
});
</script>
@endpush
