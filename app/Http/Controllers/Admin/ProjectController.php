<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectGallery;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with('gallery')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        return view('admin.projects.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'impact_line' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'technologies' => ['nullable', 'string', 'max:255'],
            'github_repo_url' => ['nullable', 'url:http,https', 'max:255'],
            'live_url' => ['nullable', 'url:http,https', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'], // 5MB max
            'image_url' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $imageUrl = $validated['image_url'] ?? null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/projects', 'public');
            $imageUrl = '/storage/' . $path;
        } elseif (!empty($imageUrl)) {
            $clean = ltrim($imageUrl, '/');
            if (str_starts_with($clean, 'uploads/')) {
                $imageUrl = '/storage/' . $clean;
            } elseif (!str_starts_with($clean, 'storage/') && !preg_match('#^https?://#i', $clean)) {
                $imageUrl = '/storage/' . $clean;
            }
        }

        $project = Project::create([
            'title' => $validated['title'],
            'impact_line' => $validated['impact_line'] ?? null,
            'description' => $validated['description'] ?? null,
            'technologies' => $validated['technologies'] ?? null,
            'github_repo_url' => $validated['github_repo_url'] ?? null,
            'live_url' => $validated['live_url'] ?? null,
            'image_url' => $imageUrl,
            'is_published' => $request->boolean('is_published', true),
        ]);

        // Multiple gallery images if provided
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $idx => $file) {
                $galleryPath = $file->store('uploads/projects/gallery', 'public');
                ProjectGallery::create([
                    'project_id' => $project->id,
                    'image_url' => '/storage/' . $galleryPath,
                    'sort_order' => $idx,
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project): View
    {
        $project->load('gallery');
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'impact_line' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'technologies' => ['nullable', 'string', 'max:255'],
            'github_repo_url' => ['nullable', 'url:http,https', 'max:255'],
            'live_url' => ['nullable', 'url:http,https', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $imageUrl = $project->getRawOriginal('image_url');
        if ($request->hasFile('image')) {
            if ($imageUrl && str_starts_with($imageUrl, '/storage/')) {
                $oldPath = preg_replace('#^/storage/#', '', $imageUrl);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('uploads/projects', 'public');
            $imageUrl = '/storage/' . $path;
        } elseif (!empty($validated['image_url'])) {
            $imageUrl = $validated['image_url'];
            $clean = ltrim($imageUrl, '/');
            if (str_starts_with($clean, 'uploads/')) {
                $imageUrl = '/storage/' . $clean;
            } elseif (!str_starts_with($clean, 'storage/') && !preg_match('#^https?://#i', $clean)) {
                $imageUrl = '/storage/' . $clean;
            }
        }

        $project->update([
            'title' => $validated['title'],
            'impact_line' => $validated['impact_line'] ?? null,
            'description' => $validated['description'] ?? null,
            'technologies' => $validated['technologies'] ?? null,
            'github_repo_url' => $validated['github_repo_url'] ?? null,
            'live_url' => $validated['live_url'] ?? null,
            'image_url' => $imageUrl,
            'is_published' => $request->boolean('is_published'),
        ]);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $idx => $file) {
                $galleryPath = $file->store('uploads/projects/gallery', 'public');
                ProjectGallery::create([
                    'project_id' => $project->id,
                    'image_url' => '/storage/' . $galleryPath,
                    'sort_order' => $idx + $project->gallery()->count(),
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        // Clean up project storage image
        $rawImage = $project->getRawOriginal('image_url');
        if ($rawImage && str_starts_with($rawImage, '/storage/')) {
            $storagePath = preg_replace('#^/storage/#', '', $rawImage);
            Storage::disk('public')->delete($storagePath);
        }

        // Clean up gallery storage images
        foreach ($project->gallery as $galleryItem) {
            $rawGallery = $galleryItem->getRawOriginal('image_url');
            if ($rawGallery && str_starts_with($rawGallery, '/storage/')) {
                $storagePath = preg_replace('#^/storage/#', '', $rawGallery);
                Storage::disk('public')->delete($storagePath);
            }
        }

        $project->gallery()->delete();
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function togglePublish(Project $project): JsonResponse
    {
        $project->update(['is_published' => !$project->is_published]);
        return response()->json(['success' => true, 'is_published' => $project->is_published]);
    }

    public function deleteGalleryImage(ProjectGallery $gallery): JsonResponse
    {
        $raw = $gallery->getRawOriginal('image_url');
        if ($raw && str_starts_with($raw, '/storage/')) {
            $storagePath = preg_replace('#^/storage/#', '', $raw);
            Storage::disk('public')->delete($storagePath);
        }
        $gallery->delete();
        return response()->json(['success' => true]);
    }
}
