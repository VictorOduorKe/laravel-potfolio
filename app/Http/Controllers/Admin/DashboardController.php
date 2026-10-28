<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\News;
use App\Models\Project;
use App\Models\Roadmap;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin overview dashboard
     */
    public function index(Request $request): View
    {
        $stats = [
            'projects_count' => Project::count(),
            'published_projects' => Project::where('is_published', true)->count(),
            'skills_count' => Skill::count(),
            'roadmaps_count' => Roadmap::count(),
            'news_count' => News::count(),
            'messages_count' => Contact::count(),
            'testimonials_count' => Testimonial::count(),
        ];

        $recentMessages = Contact::orderBy('created_at', 'desc')->take(5)->get();
        $recentNews = News::orderBy('created_at', 'desc')->take(5)->get();
        $recentProjects = Project::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentMessages' => $recentMessages,
            'recentNews' => $recentNews,
            'recentProjects' => $recentProjects,
        ]);
    }
}
