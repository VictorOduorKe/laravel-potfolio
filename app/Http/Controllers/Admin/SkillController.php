<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index(): View
    {
        $skills = Skill::orderBy('category')
            ->orderBy('proficiency', 'desc')
            ->get();

        return view('admin.skills.index', compact('skills'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'proficiency' => ['nullable', 'integer', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
            'experience_badge' => ['nullable', 'string', 'max:100'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        Skill::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'proficiency' => (int) ($validated['proficiency'] ?? 80),
            'description' => $validated['description'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'experience_badge' => $validated['experience_badge'] ?? null,
            'is_published' => $request->boolean('is_published', true),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.skills.index')->with('success', 'Skill added successfully.');
    }

    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:100'],
            'proficiency' => ['nullable', 'integer', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
            'tags' => ['nullable', 'string', 'max:500'],
            'experience_badge' => ['nullable', 'string', 'max:100'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $skill->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'proficiency' => (int) ($validated['proficiency'] ?? 80),
            'description' => $validated['description'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'experience_badge' => $validated['experience_badge'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.skills.index')->with('success', 'Skill updated successfully.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();
        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted successfully.');
    }

    public function togglePublish(Skill $skill): JsonResponse
    {
        $skill->update(['is_published' => !$skill->is_published]);
        return response()->json(['success' => true, 'is_published' => $skill->is_published]);
    }
}
