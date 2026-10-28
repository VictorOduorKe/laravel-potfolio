<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects or redirect to the portfolio showcase
     */
    public function index(Request $request): RedirectResponse|View|JsonResponse
    {
        // When accessed directly, can either show the list or anchor to home #projects
        if ($request->wantsJson()) {
            return response()->json(
                Project::where('is_published', true)
                    ->with('gallery')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );
        }

        return redirect()->route('home', ['#projects']);
    }

    /**
     * Display a specific project details view
     */
    public function show(Project $project): View
    {
        // Only allow published projects for general public unless authenticated admin
        if (!$project->is_published && !auth('admin')->check()) {
            abort(404);
        }

        $project->load('gallery');

        // Fetch related or other projects for quick discovery
        $otherProjects = Project::where('is_published', true)
            ->where('id', '!=', $project->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('pages.project-detail', [
            'project' => $project,
            'otherProjects' => $otherProjects,
        ]);
    }
}
