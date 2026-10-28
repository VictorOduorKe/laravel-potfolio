<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roadmap;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoadmapController extends Controller
{
    public function index(): View
    {
        $roadmaps = Roadmap::with(['parent', 'children'])
            ->orderBy('target_date', 'desc')
            ->get();

        $rootRoadmaps = Roadmap::whereNull('parent_id')->orderBy('title')->get();

        return view('admin.roadmaps.index', compact('roadmaps', 'rootRoadmaps'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
            'target_date' => ['nullable', 'date'],
            'parent_id' => ['nullable', 'exists:roadmaps,id'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        Roadmap::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'target_date' => $validated['target_date'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('admin.roadmaps.index')->with('success', 'Roadmap milestone added successfully.');
    }

    public function update(Request $request, Roadmap $roadmap): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:50'],
            'target_date' => ['nullable', 'date'],
            'parent_id' => ['nullable', 'exists:roadmaps,id'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        // Prevent self as parent
        if (!empty($validated['parent_id']) && (int) $validated['parent_id'] === $roadmap->id) {
            return back()->withErrors(['parent_id' => 'A roadmap milestone cannot be its own parent.']);
        }

        $roadmap->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'target_date' => $validated['target_date'] ?? null,
            'parent_id' => $validated['parent_id'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.roadmaps.index')->with('success', 'Roadmap milestone updated successfully.');
    }

    public function destroy(Roadmap $roadmap): RedirectResponse
    {
        // Re-assign or orphan children
        Roadmap::where('parent_id', $roadmap->id)->update(['parent_id' => null]);
        $roadmap->delete();

        return redirect()->route('admin.roadmaps.index')->with('success', 'Roadmap milestone deleted.');
    }

    public function togglePublish(Roadmap $roadmap): JsonResponse
    {
        $roadmap->update(['is_published' => !$roadmap->is_published]);
        return response()->json(['success' => true, 'is_published' => $roadmap->is_published]);
    }
}
