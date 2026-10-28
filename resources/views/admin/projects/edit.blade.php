@extends('layouts.admin')

@section('title', 'Edit Project: ' . $project->title)
@section('header', 'Edit Project: ' . $project->title)

@section('content')
<div class="admin-card" style="max-width: 800px;">
    <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div style="margin-bottom: 20px;">
            <label class="form-label" for="title">Project Title *</label>
            <input type="text" id="title" name="title" class="form-control" required value="{{ old('title', $project->title) }}">
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label" for="impact_line">Impact Line / Tagline</label>
            <input type="text" id="impact_line" name="impact_line" class="form-control" value="{{ old('impact_line', $project->impact_line) }}">
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label" for="description">Detailed Description</label>
            <textarea id="description" name="description" class="form-control" rows="5">{{ old('description', $project->description) }}</textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label" for="github_repo_url">GitHub Repository URL</label>
            <input type="url" id="github_repo_url" name="github_repo_url" class="form-control" value="{{ old('github_repo_url', $project->github_repo_url) }}">
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label" for="live_url">Live Project URL (Optional)</label>
            <input type="url" id="live_url" name="live_url" class="form-control" value="{{ old('live_url', $project->live_url) }}" placeholder="https://demo.example.com">
            <span style="font-size: 12px; color: var(--text-muted); margin-top: 4px; display: block;">
                The public URL of the deployed application, for example <code>https://demo.example.com</code>.
            </span>
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label" for="technologies">Technologies / Stack (Optional)</label>
            <input type="text" id="technologies" name="technologies" class="form-control" value="{{ old('technologies', $project->technologies) }}" placeholder="e.g. PHP, Laravel, React, MySQL, Gemini AI">
            <span style="font-size: 12px; color: var(--text-muted); margin-top: 4px; display: block;">
                Comma-separated tags for the tech stack. If left blank, key technologies mentioned in the description will be auto-detected.
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 24px;">
            <div>
                <label class="form-label" for="image">Upload New Cover Image</label>
                <input type="file" id="image" name="image" class="form-control" accept="image/*">
            </div>
            <div>
                <label class="form-label" for="image_url">Image URL / Path</label>
                <input type="text" id="image_url" name="image_url" class="form-control" value="{{ old('image_url', $project->image_url) }}">
            </div>
        </div>

        @if($project->image_url)
        <div style="margin-bottom: 24px; padding: 12px; background: var(--background); border-radius: var(--radius-sm); display: flex; align-items: center; gap: 16px;">
            <img src="{{ $project->image_url }}" alt="Cover" style="height: 60px; border-radius: 4px; border: 1px solid var(--border);" onerror="this.style.opacity='0.4';">
            <span style="font-size: 13px; color: var(--text-muted); word-break: break-all;">Current Cover Image: {{ $project->image_url }}</span>
        </div>
        @endif

        <div style="margin-bottom: 32px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $project->is_published) ? 'checked' : '' }} style="width: 18px; height: 18px;">
                <span>Published</span>
            </label>
        </div>

        <div style="display: flex; gap: 14px;">
            <button type="submit" class="btn-hero-primary">Save Changes</button>
            <a href="{{ route('admin.projects.index') }}" class="btn-hero-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
