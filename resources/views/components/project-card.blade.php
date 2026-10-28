@props([
    'project',
    'staggerIndex' => 0,
    'featured' => false
])

<article class="project-card compact-card reveal" style="--stagger-index: {{ $staggerIndex }};" data-project-id="{{ $project->id }}">
    <!-- Card Media / Thumbnail -->
    <div class="compact-card-media">
        @if($project->image_url)
            <img src="{{ $project->image_url }}"
                 alt="{{ $project->title }} cover image"
                 loading="lazy"
                 class="compact-card-img"
                 onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='flex';">
            <div class="compact-card-placeholder" style="display: none;">
                <span class="material-symbols-outlined" style="font-size: 42px;">terminal</span>
                <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Architecture</span>
            </div>
        @else
            <div class="compact-card-placeholder">
                <span class="material-symbols-outlined" style="font-size: 42px;">terminal</span>
                <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Architecture</span>
            </div>
        @endif

        <!-- Floating Badges -->
        <div class="compact-card-badge-container">
            @if($project->hasLiveUrl())
                <span class="card-status-pill pill-live" title="Live Application Deployed">
                    <span class="pulse-dot"></span>
                    <span>Live</span>
                </span>
            @elseif($featured)
                <span class="card-status-pill pill-featured">
                    <span class="material-symbols-outlined" style="font-size: 13px;">star</span>
                    <span>Featured</span>
                </span>
            @elseif($project->status_label)
                <span class="card-status-pill pill-default">
                    {{ $project->status_label }}
                </span>
            @endif
        </div>
    </div>

    <!-- Card Body -->
    <div class="compact-card-body">
        @if($project->impact_line)
            <div class="compact-card-impact" title="{{ $project->impact_line }}">
                {{ $project->impact_line }}
            </div>
        @endif

        <h3 class="compact-card-title">
            <a href="{{ route('projects.show', $project) }}" class="title-link">
                {{ $project->title }}
            </a>
        </h3>

        <p class="compact-card-desc">
            {{ $project->description }}
        </p>

        <!-- Compact Tech Stack Tags -->
        @if(!empty($project->tech_stack))
            <div class="compact-tech-stack" aria-label="Technologies used">
                @foreach(array_slice($project->tech_stack, 0, 4) as $tech)
                    <span class="compact-tech-tag">{{ $tech }}</span>
                @endforeach
                @if(count($project->tech_stack) > 4)
                    <span class="compact-tech-tag-more" title="{{ implode(', ', array_slice($project->tech_stack, 4)) }}">
                        +{{ count($project->tech_stack) - 4 }}
                    </span>
                @endif
            </div>
        @endif

        <!-- Distinct Accessible Actions Bar -->
        <div class="compact-card-actions">
            <a href="{{ route('projects.show', $project) }}" class="btn-card-details" aria-label="View Details for {{ $project->title }}">
                <span>View Details</span>
                <span class="material-symbols-outlined" style="font-size: 17px;">arrow_forward</span>
            </a>

            <div class="compact-card-external-links">
                @if($project->hasRepoUrl())
                    <a href="{{ $project->github_repo_url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="btn-card-icon"
                       title="View Source Code on GitHub"
                       aria-label="Source code for {{ $project->title }} (opens in a new tab)">
                        <span class="material-symbols-outlined" style="font-size: 18px;">code</span>
                    </a>
                @endif

                @if($project->hasLiveUrl())
                    <a href="{{ $project->live_url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="btn-card-icon btn-card-live"
                       title="Open Live Deployed Application"
                       aria-label="Live Demo for {{ $project->title }} (opens in a new tab)">
                        <span class="material-symbols-outlined" style="font-size: 18px;">launch</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</article>
