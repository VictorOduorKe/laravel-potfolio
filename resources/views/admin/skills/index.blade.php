@extends('layouts.admin')

@section('title', 'Manage Skills Bento')
@section('header', 'Skills Bento Grid')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 360px; gap: 32px; align-items: start;">
    <!-- Left: Skills List grouped by category -->
    <div class="admin-card" style="padding: 0; overflow: hidden;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Skill Name</th>
                    <th>Category</th>
                    <th>Proficiency</th>
                    <th>Badge / Tags</th>
                    <th>Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($skills as $skill)
                <tr>
                    <td>
                        <strong style="color: var(--primary);">{{ $skill->name }}</strong>
                        @if($skill->description)
                            <span style="font-size: 11px; color: var(--text-muted); display: block;">{{ Str::limit($skill->description, 50) }}</span>
                        @endif
                    </td>
                    <td><span class="tag-badge">{{ $skill->category }}</span></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 70px; height: 6px; background: var(--surface-variant); border-radius: 999px; overflow: hidden;">
                                <div style="width: {{ $skill->proficiency }}%; height: 100%; background: var(--secondary);"></div>
                            </div>
                            <span style="font-size: 12px; font-weight: 600;">{{ $skill->proficiency }}%</span>
                        </div>
                    </td>
                    <td>
                        @if($skill->experience_badge)
                            <span class="status-badge status-progress" style="font-size: 10px;">{{ $skill->experience_badge }}</span>
                        @endif
                        @if($skill->tags)
                            <span style="font-size: 11px; color: var(--text-muted); display: block;">{{ Str::limit($skill->tags, 25) }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge {{ $skill->is_published ? 'status-completed' : 'status-planned' }}">
                            {{ $skill->is_published ? 'Active' : 'Hidden' }}
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" onsubmit="return confirm('Delete skill?');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 4px 8px; border-radius: 4px; border: none;">
                                <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 32px; color: var(--text-light);">
                        No skills created yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Right: Add New Skill Card -->
    <div class="admin-card">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 16px;">Add New Skill</h3>
        <form action="{{ route('admin.skills.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="name">Skill Name *</label>
                <input type="text" id="name" name="name" class="form-control" required placeholder="e.g. CSRF Token implementation">
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label" for="category">Category *</label>
                <input type="text" id="category" name="category" list="category-suggestions" class="form-control" required placeholder="e.g. IT Support &amp; Systems Administration">
                <datalist id="category-suggestions">
                    <option value="IT Support &amp; Systems Administration">
                    <option value="Networking &amp; Infrastructure">
                    <option value="Cybersecurity">
                    <option value="Cloud, DevOps &amp; Deployment">
                    <option value="Database &amp; Technical Operations">
                    <option value="Backend Development">
                    <option value="Frontend Development">
                    <option value="Payment Systems">
                    <option value="AI &amp; Automation">
                    <option value="System Design &amp; Architecture">
                </datalist>
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label" for="proficiency">Proficiency (0 - 100)%</label>
                <input type="number" id="proficiency" name="proficiency" class="form-control" min="1" max="100" value="85">
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label" for="experience_badge">Experience Badge</label>
                <input type="text" id="experience_badge" name="experience_badge" class="form-control" placeholder="e.g. 5+, Senior, Expert">
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label" for="tags">Keywords / Tags</label>
                <input type="text" id="tags" name="tags" class="form-control" placeholder="OWASP Top 10, hashing, encryption">
            </div>

            <div style="margin-bottom: 20px;">
                <label class="form-label" for="description">Technical Scope / Summary</label>
                <textarea id="description" name="description" class="form-control" rows="2" placeholder="Brief technical summary..."></textarea>
            </div>

            <div style="margin-bottom: 24px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 14px; font-weight: 600;">
                    <input type="checkbox" name="is_published" value="1" checked style="width: 18px; height: 18px;">
                    <span>Publish to Bento Grid</span>
                </label>
            </div>

            <button type="submit" class="btn-hero-primary" style="width: 100%; padding: 12px;">Add Skill</button>
        </form>
    </div>
</div>
@endsection
