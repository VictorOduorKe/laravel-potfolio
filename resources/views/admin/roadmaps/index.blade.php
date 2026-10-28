@extends('layouts.admin')

@section('title', 'Manage Roadmap Tree')
@section('header', 'Roadmap Milestones')

@section('content')
<div class="admin-grid-split">
    <!-- Left: Roadmaps List -->
    <div class="admin-card" style="padding: 0; overflow: hidden;">
        <div class="admin-table-responsive">
            <table class="data-table">
            <thead>
                <tr>
                    <th>Milestone Title</th>
                    <th>Parent (Tree Node)</th>
                    <th>Status</th>
                    <th>Target Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($roadmaps as $r)
                <tr>
                    <td>
                        <strong style="color: var(--primary);">{{ $r->title }}</strong>
                        @if($r->description)
                            <span style="font-size: 12px; color: var(--text-muted); display: block;">{{ Str::limit($r->description, 60) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($r->parent)
                            <span class="tag-badge">{{ $r->parent->title }}</span>
                        @else
                            <span style="font-size: 12px; color: var(--text-muted); font-style: italic;">[Root Milestone]</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusClass = match(strtolower($r->status)) {
                                'completed' => 'status-completed',
                                'in progress', 'progress' => 'status-progress',
                                default => 'status-planned'
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ $r->status }}</span>
                    </td>
                    <td>
                        <span style="font-size: 13px; color: var(--text-light);">{{ $r->target_date ? $r->target_date->format('M Y') : 'Ongoing' }}</span>
                    </td>
                    <td style="text-align: right;">
                        <form action="{{ route('admin.roadmaps.destroy', $r) }}" method="POST" onsubmit="return confirm('Delete milestone?');" style="display: inline;">
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
                    <td colspan="5" style="text-align: center; padding: 32px; color: var(--text-light);">
                        No roadmap milestones added.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <!-- Right: Add Milestone Form -->
    <div class="admin-card">
        <h3 style="font-size: 16px; font-weight: 700; color: var(--primary); margin-bottom: 16px;">Add New Milestone</h3>
        <form action="{{ route('admin.roadmaps.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label class="form-label" for="title">Milestone Title *</label>
                <input type="text" id="title" name="title" class="form-control" required placeholder="e.g. Microservices Refactoring">
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label" for="parent_id">Parent Milestone (Optional)</label>
                <select id="parent_id" name="parent_id" class="form-control">
                    <option value="">-- No Parent (Root Milestone) --</option>
                    @foreach($rootRoadmaps as $root)
                        <option value="{{ $root->id }}">{{ $root->title }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label" for="status">Current Status *</label>
                <select id="status" name="status" class="form-control">
                    <option value="Planned">Planned</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label class="form-label" for="target_date">Target Date</label>
                <input type="date" id="target_date" name="target_date" class="form-control">
            </div>

            <div style="margin-bottom: 20px;">
                <label class="form-label" for="description">Description / Objective</label>
                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Scope and expected technical outcomes..."></textarea>
            </div>

            <button type="submit" class="btn-hero-primary" style="width: 100%; padding: 12px;">Add Milestone</button>
        </form>
    </div>
</div>
@endsection
