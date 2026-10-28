@extends('layouts.app')
@inject('seo', 'App\Services\SeoService')

@section('title', 'Victor Oduor | Full Stack Developer & Cybersecurity Specialist')
@section('description', 'Victor Oduor is a Full Stack Developer and Cybersecurity Specialist building resilient web platforms, secure PHP/React APIs, and M-PESA payment workflows in Kenya.')
@section('canonical', '/')

@section('structured_data')
<script type="application/ld+json">
{!! json_encode($seo->getPersonSchema(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($seo->getWebSiteSchema(), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')
<div class="container-layout">
    <!-- =========================================================================
         1. HERO SECTION
         ========================================================================= -->
    <section id="hero" style="padding: 60px 0 40px 0; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 48px;">
        <div style="flex: 1 1 500px;" class="reveal">
            <div class="hero-badge">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--secondary);"></span>
                Available for High-Impact Engineering Roles &amp; Contracts
            </div>
            <h1 class="hero-title">
                Engineering Resilient Platforms &amp; Secure Web Systems
            </h1>
            <p class="hero-subtitle">
                I'm <strong style="color: var(--primary);">Victor Oduor</strong>, a Full Stack Software Engineer and Cybersecurity Specialist based in Mombasa, Kenya. I architect robust monolithic &amp; distributed web applications, high-throughput payment automation workflows, and AI-grounded interfaces.
            </p>
            <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                <a href="#projects" class="btn-hero-primary">
                    <span class="material-symbols-outlined" style="font-size: 20px;">layers</span>
                    View Selected Projects
                </a>
                <a href="#contact" class="btn-hero-secondary">
                    <span class="material-symbols-outlined" style="font-size: 20px;">send</span>
                    Contact Me
                </a>
            </div>
        </div>

        <!-- Profile Avatar & Tech Highlights -->
        <div style="flex: 0 1 380px; text-align: center;" class="reveal">
            <div style="position: relative; display: inline-block;">
                <div style="width: 280px; height: 280px; border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; overflow: hidden; border: 4px solid #ffffff; box-shadow: var(--shadow-lg); background: var(--primary);">
                    <img src="{{ asset('profile.jpg') }}" alt="Victor Oduor - Full Stack Software Engineer and Cybersecurity Specialist" width="280" height="280" fetchpriority="high" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <!-- Floating badge 1 -->
                <div style="position: absolute; bottom: 12px; left: -16px; background: #ffffff; padding: 10px 16px; border-radius: var(--radius-md); box-shadow: var(--shadow-md); border: 1px solid var(--border); display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="color: var(--secondary); font-size: 20px;">verified</span>
                    <span style="font-size: 13px; font-weight: 700; color: var(--primary);">Kenya &bull; UTC+3</span>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         2. SYSTEM IMPACT STATS STRIP
         ========================================================================= -->
    <section class="stats-strip reveal">
        <div class="stat-item">
            <div class="stat-number" data-counter="12" data-suffix="+">12+</div>
            <div class="stat-label">Production Systems Architected</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-counter="99.9" data-suffix="%" data-decimals="1">99.9%</div>
            <div class="stat-label">Operational Uptime Standard</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-counter="40" data-suffix="%">40%</div>
            <div class="stat-label">Average API Latency Reduction</div>
        </div>
        <div class="stat-item">
            <div class="stat-number" data-counter="24" data-suffix="/7">24/7</div>
            <div class="stat-label">Autonomous Health Monitoring</div>
        </div>
    </section>

    <!-- =========================================================================
         3. PROJECTS SECTION
         ========================================================================= -->
    <section id="projects" style="padding: 60px 0;">
        <div class="projects-section-header reveal">
            <div style="text-align: center; max-width: 720px; margin: 0 auto 32px auto;">
                <span class="section-tag">Case Studies &amp; Software Work</span>
                <h2 class="section-title">Engineered For Reliability &amp; Scale</h2>
                <p class="section-description" style="margin: 0 auto;">
                    Real-world software platforms engineered with high-volume database pipelines, resilient security standards, and seamless human experiences.
                </p>
            </div>

            <!-- View Options & Carousel Toolbar -->
            <div class="project-showcase-toolbar">
                <div class="project-counter-badge">
                    <span class="pulse-dot-sm"></span>
                    <span>{{ $allProjects->count() }} Featured {{ Str::plural('Project', $allProjects->count()) }}</span>
                </div>

                <div class="project-view-controls" role="group" aria-label="Project layout view modes">
                    <button type="button" class="btn-view-toggle active" id="view-mode-grid" aria-pressed="true" title="Grid Layout">
                        <span class="material-symbols-outlined" style="font-size: 18px;">grid_view</span>
                        <span class="btn-text">Grid</span>
                    </button>
                    <button type="button" class="btn-view-toggle" id="view-mode-carousel" aria-pressed="false" title="Carousel Slider Layout">
                        <span class="material-symbols-outlined" style="font-size: 18px;">view_carousel</span>
                        <span class="btn-text">Carousel</span>
                    </button>
                </div>

                <!-- Carousel Navigation (visible when carousel mode active) -->
                <div class="project-carousel-nav" id="project-carousel-controls" style="display: none;" aria-label="Carousel navigation">
                    <button type="button" class="btn-carousel-ctrl" id="proj-prev-btn" aria-label="Previous project">
                        <span class="material-symbols-outlined" style="font-size: 20px;">chevron_left</span>
                    </button>
                    <span class="carousel-position-text" id="proj-counter-text" aria-live="polite">1 / {{ max($allProjects->count(), 1) }}</span>
                    <button type="button" class="btn-carousel-ctrl" id="proj-next-btn" aria-label="Next project">
                        <span class="material-symbols-outlined" style="font-size: 20px;">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Projects Showcase Container -->
        <div class="project-showcase-wrapper" id="project-showcase-wrapper">
            <div class="project-grid-layout" id="project-cards-container">
                @forelse($allProjects as $index => $project)
                    <x-project-card :project="$project" :stagger-index="$index" :featured="$loop->first" />
                @empty
                    <div class="empty-projects-card reveal" style="text-align: center; padding: 48px 24px; background: var(--surface); border-radius: var(--radius-lg); border: 1px solid var(--border); grid-column: 1 / -1;">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: var(--text-muted);">folder_off</span>
                        <p style="margin-top: 12px; color: var(--text-light); font-size: 15px;">
                            No projects currently published. Check back soon for updates.
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- =========================================================================
         4. SKILLS BENTO SHOWCASE (REFINED DARK TECHNICAL SURFACES)
         ========================================================================= -->
    <section id="skills" class="skills-showcase-section" aria-labelledby="skills-heading">
        <div style="text-align: center; margin-bottom: 36px;" class="reveal">
            <span class="section-tag">Technical Competencies &amp; Systems</span>
            <h2 id="skills-heading" class="section-title">Engineered Around Core Domains</h2>
            <p class="section-description" style="margin: 0 auto;">
                A battle-tested technical stack honed across complex business workflows, multi-tenant databases, payment automation, and security-critical implementations.
            </p>
        </div>

        <!-- Domain Filter & Search Toolbar -->
        <div class="skills-filter-toolbar reveal" role="toolbar" aria-label="Skill domains and search">
            <div class="skills-tab-list" role="tablist" aria-label="Filter competencies by domain">
                <button type="button" class="btn-skill-filter active" data-filter="all" role="tab" aria-selected="true">
                    <span>All Domains</span>
                    <span class="filter-count">{{ $totalSkillsCount ?? 46 }}</span>
                </button>
                @foreach($skillsBento as $group)
                    <button type="button" class="btn-skill-filter" data-filter="{{ $group['id'] }}" role="tab" aria-selected="false">
                        <span>{{ $group['filter_tag'] }}</span>
                        <span class="filter-count">{{ $group['skills']->count() }}</span>
                    </button>
                @endforeach
            </div>

            <div class="skills-search-wrapper">
                <span class="material-symbols-outlined skills-search-icon" aria-hidden="true">search</span>
                <input type="text"
                       id="skills-search-input"
                       class="skills-search-input"
                       placeholder="Find technology or skill..."
                       aria-label="Filter technologies or capabilities"
                       autocomplete="off">
            </div>
        </div>

        <!-- Bento Grid System -->
        <div class="skills-bento-grid">
            @forelse($skillsBento as $index => $group)
            <article class="bento-skill-card reveal {{ $group['col_span'] }} {{ $group['accent'] }}"
                     data-domain="{{ $group['id'] }}"
                     style="--stagger-index: {{ $index }};">
                <!-- Card Header -->
                <div class="bento-card-header">
                    <div class="bento-title-meta">
                        <div class="bento-icon-box" aria-hidden="true">
                            <span class="material-symbols-outlined" style="font-size: 22px;">{{ $group['icon'] }}</span>
                        </div>
                        <div>
                            <h3 class="bento-card-title">{{ $group['title'] }}</h3>
                            <p class="bento-card-tagline">{{ $group['tagline'] }}</p>
                        </div>
                    </div>
                    <span class="bento-count-pill">{{ $group['skills']->count() }} {{ Str::plural('Skill', $group['skills']->count()) }}</span>
                </div>

                <!-- Skills Badges / Tiles -->
                @if($group['id'] === 'architecture')
                    <div class="bento-architecture-grid">
                        <div>
                            <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 15px; color: #fbbf24;">architecture</span>
                                Architectural Disciplines &amp; Patterns
                            </div>
                            <div class="bento-tiles-grid" role="list">
                                @foreach($group['skills']->filter(fn($s) => stripos($s->category, 'system') !== false) as $skill)
                                    @php
                                        $isCore = ($skill->experience_badge && stripos($skill->experience_badge, 'core') !== false) || ($skill->proficiency >= 95);
                                        $tooltip = $skill->description ?: ($skill->tags ? "Keywords: {$skill->tags}" : null);
                                    @endphp
                                    <div class="skill-tile {{ $isCore ? 'is-core' : '' }}"
                                         role="listitem"
                                         tabindex="0"
                                         @if($tooltip) data-tooltip="{{ $tooltip }}" @endif
                                         title="{{ $skill->name }}{{ $skill->proficiency ? " ({$skill->proficiency}%)" : '' }}{{ $skill->description ? " — {$skill->description}" : '' }}">
                                        <div class="skill-tile-icon">
                                            <x-tech-icon :name="$skill->name" :size="18" />
                                        </div>
                                        <span class="skill-tile-name">{{ $skill->name }}</span>
                                        @if($skill->experience_badge)
                                            <span class="skill-tile-badge">{{ $skill->experience_badge }}</span>
                                        @elseif($isCore)
                                            <span class="skill-tile-badge badge-core">Core</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-bottom: 10px; display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="font-size: 15px; color: #34d399;">verified</span>
                                Validated Production Solutions
                            </div>
                            <div class="bento-tiles-grid" role="list">
                                @foreach($group['skills']->filter(fn($s) => stripos($s->category, 'project') !== false) as $skill)
                                    @php
                                        $tooltip = $skill->description ?: ($skill->tags ? "Keywords: {$skill->tags}" : null);
                                    @endphp
                                    <div class="skill-tile is-production"
                                         role="listitem"
                                         tabindex="0"
                                         @if($tooltip) data-tooltip="{{ $tooltip }}" @endif
                                         title="{{ $skill->name }}{{ $skill->proficiency ? " ({$skill->proficiency}%)" : '' }}{{ $skill->description ? " — {$skill->description}" : '' }}">
                                        <div class="skill-tile-icon">
                                            <x-tech-icon :name="$skill->name" :size="18" />
                                        </div>
                                        <span class="skill-tile-name">{{ $skill->name }}</span>
                                        <span class="skill-tile-badge" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: #34d399;">Deployed</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bento-tiles-grid" role="list">
                        @foreach($group['skills'] as $skill)
                            @php
                                $isCore = ($skill->experience_badge && stripos($skill->experience_badge, 'core') !== false) ||
                                          ($skill->proficiency >= 95 && in_array($skill->name, ['Laravel', 'PHP (structured PHP)', 'React', 'MySQL', 'M-PESA Daraja API integration', 'Git & GitHub version control']));
                                $tooltip = $skill->description ?: ($skill->tags ? "Keywords: {$skill->tags}" : null);
                            @endphp
                            <div class="skill-tile {{ $isCore ? 'is-core' : '' }}"
                                 role="listitem"
                                 tabindex="0"
                                 @if($tooltip) data-tooltip="{{ $tooltip }}" @endif
                                 title="{{ $skill->name }}{{ $skill->proficiency ? " ({$skill->proficiency}%)" : '' }}{{ $skill->description ? " — {$skill->description}" : '' }}">
                                <div class="skill-tile-icon">
                                    <x-tech-icon :name="$skill->name" :size="18" />
                                </div>
                                <span class="skill-tile-name">{{ $skill->name }}</span>

                                @if($skill->experience_badge)
                                    <span class="skill-tile-badge">{{ $skill->experience_badge }}</span>
                                @elseif($isCore)
                                    <span class="skill-tile-badge badge-core">Core</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </article>
            @empty
            <div class="bento-skill-card span-12" style="text-align: center; padding: 48px;">
                <p style="color: #94a3b8;">Technical competencies stack updating...</p>
            </div>
            @endforelse
        </div>
    </section>

    <!-- =========================================================================
         5. HIERARCHICAL ROADMAP TREE
         ========================================================================= -->
    <section id="roadmap" style="padding: 60px 0;">
        <div style="text-align: center; margin-bottom: 48px;" class="reveal">
            <span class="section-tag">Continuous Evolution</span>
            <h2 class="section-title">Engineering Roadmap &amp; Milestones</h2>
            <p class="section-description" style="margin: 0 auto;">
                Transparent visibility into upcoming architectural targets, ongoing projects, and planned cybersecurity enhancements.
            </p>
        </div>

        <div class="roadmap-container reveal">
            <div style="border-bottom: 1px solid #1e293b; padding-bottom: 12px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444;"></span>
                    <span style="width: 12px; height: 12px; border-radius: 50%; background: #f59e0b;"></span>
                    <span style="width: 12px; height: 12px; border-radius: 50%; background: #10b981;"></span>
                    <span style="font-size: 13px; color: #94a3b8; margin-left: 8px;">roadmap.sh &mdash; live branch</span>
                </div>
                <span style="font-size: 12px; color: #64748b;">TREE VIEW</span>
            </div>

            @forelse($roadmapTree as $root)
            <div class="tree-node">
                <div style="display: flex; align-items: baseline; gap: 8px;">
                    <span style="color: #38bdf8;">└──</span>
                    <strong style="color: #f8fafc; font-size: 15px;">{{ $root->title }}</strong>
                    @php
                        $statusClass = match(strtolower($root->status)) {
                            'completed' => 'status-completed',
                            'in progress', 'in-progress', 'progress' => 'status-progress',
                            default => 'status-planned'
                        };
                    @endphp
                    <span class="status-badge {{ $statusClass }}">{{ $root->status }}</span>
                    @if($root->target_date)
                        <span style="font-size: 12px; color: #64748b;">[{{ $root->target_date->format('M Y') }}]</span>
                    @endif
                </div>
                @if($root->description)
                    <p style="margin: 4px 0 8px 32px; font-size: 13px; color: #94a3b8; font-family: var(--font-sans);">{{ $root->description }}</p>
                @endif

                <!-- Children Sub-milestones -->
                @if($root->children && $root->children->isNotEmpty())
                    <div style="margin-left: 32px;">
                        @foreach($root->children as $child)
                        <div style="display: flex; align-items: baseline; gap: 8px; margin: 6px 0;">
                            <span style="color: #64748b;">├──</span>
                            <span style="color: #cbd5e1; font-size: 14px;">{{ $child->title }}</span>
                            @php
                                $childStatusClass = match(strtolower($child->status)) {
                                    'completed' => 'status-completed',
                                    'in progress', 'in-progress', 'progress' => 'status-progress',
                                    default => 'status-planned'
                                };
                            @endphp
                            <span class="status-badge {{ $childStatusClass }}">{{ $child->status }}</span>
                        </div>
                        @if($child->description)
                            <p style="margin: 2px 0 6px 24px; font-size: 12px; color: #64748b; font-family: var(--font-sans);">{{ $child->description }}</p>
                        @endif
                        @endforeach
                    </div>
                @endif
            </div>
            @empty
            <p style="color: #64748b;">No active roadmap items posted.</p>
            @endforelse
        </div>
    </section>

    <!-- =========================================================================
         6. LATEST TECH NEWS PREVIEW
         ========================================================================= -->
    @if($latestNews->isNotEmpty())
    <section id="news" style="padding: 60px 0;">
        <div style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 40px;" class="reveal">
            <div>
                <span class="section-tag">Autonomous Knowledge Feed</span>
                <h2 class="section-title">Latest Tech Insights &amp; Updates</h2>
            </div>
            <a href="{{ route('news') }}" class="btn-hero-secondary" style="padding: 10px 20px; font-size: 14px;">
                View All Articles <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
            </a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">
            @foreach($latestNews as $article)
            <div class="bento-card reveal" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <span style="font-size: 12px; font-weight: 700; color: var(--secondary);">{{ $article->source ?: 'AI Tech Feed' }}</span>
                        <span style="font-size: 12px; color: var(--text-muted);">{{ $article->created_at ? $article->created_at->diffForHumans() : '' }}</span>
                    </div>
                    <h3 style="font-size: 19px; font-weight: 700; margin-bottom: 12px; color: var(--primary);">{{ $article->title }}</h3>
                    <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin-bottom: 20px;">
                        {{ Str::limit(strip_tags($article->content), 180) }}
                    </p>
                </div>
                @if($article->source_url)
                    <a href="{{ $article->source_url }}" target="_blank" rel="noopener" style="font-size: 13px; font-weight: 600; color: var(--accent); display: flex; align-items: center; gap: 4px;">
                        Read Full Story on {{ $article->source ?: 'Source' }} <span class="material-symbols-outlined" style="font-size: 16px;">open_in_new</span>
                    </a>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <!-- =========================================================================
         7. TESTIMONIALS & FEEDBACK
         ========================================================================= -->
    <section id="testimonials" class="testimonials-carousel-section" aria-label="Client Feedback and Testimonials">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            <div style="text-align: center; margin-bottom: 40px;" class="reveal">
                <span class="section-tag">Client Feedback</span>
                <h2 class="section-title">What Collaborators Say</h2>
                <p class="section-description" style="max-width: 620px; margin: 0 auto;">
                    Verified endorsements and feedback from engineering collaborators, clients, and technical teams.
                </p>
            </div>

            @if($testimonials->isEmpty())
                <div class="testimonials-empty-state reveal">
                    <span class="material-symbols-outlined empty-icon" aria-hidden="true">rate_review</span>
                    <h3 class="empty-title">Client Feedback In Progress</h3>
                    <p class="empty-desc">Endorsements from recent software engineering and system architecture engagements are currently being updated.</p>
                </div>
            @elseif($testimonials->count() === 1)
                @php $t = $testimonials->first(); @endphp
                <div class="testimonial-single-wrapper reveal">
                    <div class="testimonial-card spotlight-card">
                        <div class="testimonial-card-header">
                            <span class="material-symbols-outlined testimonial-quote-mark" aria-hidden="true">format_quote</span>
                            @if($t->rating)
                                <div class="testimonial-stars" aria-label="{{ $t->rating }} out of 5 stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-symbols-outlined star-icon {{ $i <= $t->rating ? 'filled' : 'empty' }}">star</span>
                                    @endfor
                                    <span class="rating-badge">{{ $t->rating }}.0</span>
                                </div>
                            @endif
                        </div>

                        <blockquote class="testimonial-quote">
                            &ldquo;{{ $t->content }}&rdquo;
                        </blockquote>

                        <div class="testimonial-author-row">
                            @if($t->image_url)
                                <img src="{{ asset(ltrim($t->image_url, '/')) }}" alt="{{ $t->client_name }}" class="testimonial-avatar-img" width="48" height="48" loading="lazy">
                            @else
                                <div class="testimonial-avatar-initials" aria-hidden="true">
                                    {{ $t->initials }}
                                </div>
                            @endif
                            <div class="testimonial-author-meta">
                                <strong class="author-name">{{ $t->client_name }}</strong>
                                <span class="author-role">{{ $t->company ?: 'Collaborator' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Multi-item Carousel -->
                <div class="testimonials-carousel-container reveal" id="testimonials-carousel-root" data-count="{{ $testimonials->count() }}">
                    <div class="testimonials-carousel-viewport" id="testimonials-viewport" tabindex="0" aria-roledescription="carousel" aria-label="Client feedback carousel">
                        <div class="testimonials-carousel-track" id="testimonials-track">
                            @foreach($testimonials as $t)
                            <div class="testimonial-slide" data-slide-index="{{ $loop->index }}" aria-roledescription="slide" aria-label="Testimonial {{ $loop->iteration }} of {{ $testimonials->count() }}">
                                <div class="testimonial-card">
                                    <div class="testimonial-card-header">
                                        <span class="material-symbols-outlined testimonial-quote-mark" aria-hidden="true">format_quote</span>
                                        @if($t->rating)
                                            <div class="testimonial-stars" aria-label="{{ $t->rating }} out of 5 stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="material-symbols-outlined star-icon {{ $i <= $t->rating ? 'filled' : 'empty' }}">star</span>
                                                @endfor
                                                <span class="rating-badge">{{ $t->rating }}.0</span>
                                            </div>
                                        @endif
                                    </div>

                                    <blockquote class="testimonial-quote">
                                        &ldquo;{{ $t->content }}&rdquo;
                                    </blockquote>

                                    <div class="testimonial-author-row">
                                        @if($t->image_url)
                                            <img src="{{ asset(ltrim($t->image_url, '/')) }}" alt="{{ $t->client_name }}" class="testimonial-avatar-img" width="48" height="48" loading="lazy">
                                        @else
                                            <div class="testimonial-avatar-initials" aria-hidden="true">
                                                {{ $t->initials }}
                                            </div>
                                        @endif
                                        <div class="testimonial-author-meta">
                                            <strong class="author-name">{{ $t->client_name }}</strong>
                                            <span class="author-role">{{ $t->company ?: 'Collaborator' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Carousel Controls & Indicators -->
                    <div class="testimonials-carousel-controls">
                        <button type="button" class="carousel-nav-btn prev" id="testimonials-prev-btn" aria-label="Previous testimonial">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </button>

                        <div class="carousel-dots-list" id="testimonials-dots-list" role="tablist" aria-label="Testimonial navigation dots">
                            @foreach($testimonials as $t)
                                <button type="button" class="carousel-dot-btn {{ $loop->first ? 'active' : '' }}" data-slide="{{ $loop->index }}" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-label="Go to testimonial {{ $loop->iteration }}"></button>
                            @endforeach
                        </div>

                        <button type="button" class="carousel-nav-btn next" id="testimonials-next-btn" aria-label="Next testimonial">
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>

                        <span class="carousel-live-counter" id="testimonials-live-counter" aria-live="polite">
                            1 / {{ $testimonials->count() }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- =========================================================================
         8. PROFESSIONAL CAPABILITY & AEO KNOWLEDGE BRIEF
         ========================================================================= -->
    <section id="about" class="reveal" style="padding: 60px 0; border-top: 1px solid var(--border-light);" aria-labelledby="aeo-overview-heading">
        <div style="text-align: center; max-width: 720px; margin: 0 auto 48px auto;">
            <span class="section-tag">Engineering Profile &amp; Technical Capabilities</span>
            <h2 id="aeo-overview-heading" class="section-title">Verified Technical Competencies &amp; Scope</h2>
            <p class="section-description" style="margin: 0 auto;">
                A concise technical overview of software engineering architectures, systems infrastructure, and IT capabilities delivered by Victor Oduor.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 40px;">
            <div class="bento-card" style="padding: 28px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                    <span class="material-symbols-outlined" style="color: var(--secondary); font-size: 28px;">code_blocks</span>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0;">Full-Stack Software Engineering</h3>
                </div>
                <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin-bottom: 14px;">
                    Building monolithic and modular web applications in <strong>PHP</strong> and <strong>Laravel</strong>, complemented by reactive frontends using <strong>React.js</strong> and JavaScript. Architect of RESTful APIs, relational MySQL schemas, and robust data integrity pipelines.
                </p>
                <div style="font-size: 13px; color: var(--text-muted); font-weight: 600;">
                    Key Tools: Laravel, PHP 8+, React, Blade, MySQL, REST APIs, JSON-LD
                </div>
            </div>

            <div class="bento-card" style="padding: 28px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                    <span class="material-symbols-outlined" style="color: var(--secondary); font-size: 28px;">payments</span>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0;">Fintech &amp; Payment Integrations</h3>
                </div>
                <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin-bottom: 14px;">
                    End-to-end implementation of <strong>Safaricom M-PESA Daraja APIs</strong>, including STK Push (Lipa Na M-PESA Online), C2B validation/confirmation callbacks, and real-time transaction reconciliation workflows.
                </p>
                <div style="font-size: 13px; color: var(--text-muted); font-weight: 600;">
                    Key Protocols: STK Push, Daraja C2B/B2C, Webhook Signativity, Automated Ledgering
                </div>
            </div>

            <div class="bento-card" style="padding: 28px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                    <span class="material-symbols-outlined" style="color: var(--secondary); font-size: 28px;">dns</span>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0;">Linux Operations &amp; Networking</h3>
                </div>
                <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin-bottom: 14px;">
                    Practical systems administration on Ubuntu and Debian servers, cPanel environments, SSH management, and web server configuration (Nginx &amp; Apache). Proficient in TCP/IP diagnostics, DNS records, DHCP routing, and LAN troubleshooting.
                </p>
                <div style="font-size: 13px; color: var(--text-muted); font-weight: 600;">
                    Key Disciplines: Ubuntu/Debian, Nginx, Apache, SSH, DNS Management, Routing &amp; Switching
                </div>
            </div>

            <div class="bento-card" style="padding: 28px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
                    <span class="material-symbols-outlined" style="color: var(--secondary); font-size: 28px;">security</span>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0;">Defensive Cybersecurity &amp; IT Support</h3>
                </div>
                <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin-bottom: 14px;">
                    Implementation of OWASP Top 10 mitigation strategies: rate limiting, CSRF protection, input sanitization, two-factor authentication (2FA), and secure credential storage. General IT support, hardware diagnostics, and workstation provisioning.
                </p>
                <div style="font-size: 13px; color: var(--text-muted); font-weight: 600;">
                    Key Practices: Defensive Web Security, OWASP Mitigation, 2FA, IT Support &amp; Helpdesk
                </div>
            </div>
        </div>

        <!-- Quick Technical Q&A for Search Engines and AI Answer Engines -->
        <div class="bento-card" style="padding: 32px; background: #ffffff;">
            <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="color: var(--secondary);">help_center</span>
                Frequently Requested Technical Information
            </h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
                <div>
                    <h4 style="font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 6px;">Who is Victor Oduor?</h4>
                    <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin: 0;">
                        Victor Oduor is a Kenya-based Full Stack Developer and Cybersecurity Specialist operating under the platform TheTechCraft. He creates secure web applications, payment gateways, and resilient server infrastructure.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 6px;">What primary technologies are used?</h4>
                    <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin: 0;">
                        PHP, Laravel Framework, React.js, MySQL, JavaScript, HTML5/CSS3, Linux, Git, RESTful APIs, and the Safaricom M-PESA Daraja payment ecosystem.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 6px;">Does Victor accept freelance or contract work?</h4>
                    <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin: 0;">
                        Yes. Available for high-impact software engineering roles, custom payment integration contracts, application security audits, and systems deployment consultations.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 15px; font-weight: 700; color: var(--primary); margin-bottom: 6px;">How can I contact Victor Oduor?</h4>
                    <p style="font-size: 14px; color: var(--text-light); line-height: 1.6; margin: 0;">
                        Visitors and prospective clients can submit an inquiry via the verified on-site <a href="#contact" style="color: var(--secondary); font-weight: 600;">contact form</a> or email directly through the published communication channels.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         9. CONTACT & CONNECT SECTION (TWO-COLUMN REDESIGN)
         ========================================================================= -->
    <x-contact-section :is-standalone="false" />
</div>
@endsection
