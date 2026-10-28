<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Project;
use App\Models\Roadmap;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Render the main visitor portfolio landing page
     */
    public function index(Request $request): View
    {
        // 1. Fetch published projects with eager-loaded gallery sorted by sort_order
        $projects = Project::where('is_published', true)
            ->with(['gallery' => function ($q) {
                $q->orderBy('sort_order', 'asc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        $featuredProject = $projects->first();
        $otherProjects = $projects->slice(1)->values();

        // 2. Fetch published skills and construct refined Bento grid domains
        $publishedSkills = Skill::where('is_published', true)
            ->orderBy('category')
            ->orderBy('proficiency', 'desc')
            ->get();

        $skillsByCategory = $publishedSkills->groupBy('category');
        $skillsBento = $this->buildSkillsBento($publishedSkills);

        // 3. Fetch nested roadmap tree (root items with their eager loaded children)
        $roadmapTree = Roadmap::whereNull('parent_id')
            ->where('is_published', true)
            ->with(['children' => function ($q) {
                $q->where('is_published', true)->orderBy('target_date', 'asc');
            }])
            ->orderBy('target_date', 'desc')
            ->get();

        // 4. Fetch latest news preview
        $latestNews = News::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // 5. Fetch published testimonials with automatic cache invalidation
        $testimonials = Cache::remember('public_testimonials', 3600, function () {
            return Testimonial::published()
                ->orderBy('display_order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        return view('pages.home', [
            'featuredProject' => $featuredProject,
            'otherProjects' => $otherProjects,
            'allProjects' => $projects,
            'skillsByCategory' => $skillsByCategory,
            'skillsBento' => $skillsBento,
            'totalSkillsCount' => $publishedSkills->count(),
            'roadmapTree' => $roadmapTree,
            'latestNews' => $latestNews,
            'testimonials' => $testimonials,
        ]);
    }

    /**
     * Organize published skills into balanced, cohesive Bento grid categories.
     */
    protected function buildSkillsBento($skills): array
    {
        $domainDefinitions = [
            'backend' => [
                'id' => 'backend',
                'title' => 'Backend Development',
                'tagline' => 'High-concurrency server platforms, RESTful APIs, and secure integrations',
                'icon' => 'terminal',
                'accent' => 'accent-blue',
                'col_span' => 'span-7',
                'filter_tag' => 'Backend',
                'categories' => ['Backend Development'],
            ],
            'frontend' => [
                'id' => 'frontend',
                'title' => 'Frontend Development',
                'tagline' => 'Component-driven reactive UIs, responsive styling, and modern web standards',
                'icon' => 'web',
                'accent' => 'accent-cyan',
                'col_span' => 'span-5',
                'filter_tag' => 'Frontend',
                'categories' => ['Frontend Development', 'Human-Computer Interaction (HCI)'],
            ],
            'databases' => [
                'id' => 'databases',
                'title' => 'Databases & Technical Operations',
                'tagline' => 'Relational schema design, SQL querying, backup restoration, and diagnostics',
                'icon' => 'database',
                'accent' => 'accent-cyan',
                'col_span' => 'span-4',
                'filter_tag' => 'Databases',
                'categories' => ['Databases', 'Database & Technical Operations'],
            ],
            'cybersecurity' => [
                'id' => 'cybersecurity',
                'title' => 'Cybersecurity',
                'tagline' => 'Defensive engineering, OWASP mitigation, access controls, and security testing',
                'icon' => 'shield_lock',
                'accent' => 'accent-emerald',
                'col_span' => 'span-4',
                'filter_tag' => 'Security',
                'categories' => ['Cybersecurity & Networking', 'Cybersecurity'],
            ],
            'cloud-devops' => [
                'id' => 'cloud-devops',
                'title' => 'Cloud, DevOps & Deployment',
                'tagline' => 'AWS cloud fundamentals, Docker containers, CI/CD, and Linux deployments',
                'icon' => 'cloud_done',
                'accent' => 'accent-blue',
                'col_span' => 'span-4',
                'filter_tag' => 'Cloud & DevOps',
                'categories' => ['Cloud & DevOps', 'Cloud, DevOps & Deployment', 'Tools & Workflow'],
            ],
            'it-support' => [
                'id' => 'it-support',
                'title' => 'IT Support & Systems Administration',
                'tagline' => 'Hardware/software diagnostics, OS administration, and user helpdesk operations',
                'icon' => 'support_agent',
                'accent' => 'accent-amber',
                'col_span' => 'span-6',
                'filter_tag' => 'IT Support',
                'categories' => ['IT Support & Systems Administration'],
            ],
            'networking' => [
                'id' => 'networking',
                'title' => 'Networking & Infrastructure',
                'tagline' => 'TCP/IP protocols, routing/switching, DNS/DHCP, and LAN diagnostics',
                'icon' => 'router',
                'accent' => 'accent-emerald',
                'col_span' => 'span-6',
                'filter_tag' => 'Networking',
                'categories' => ['Networking & Infrastructure'],
            ],
            'payment-systems' => [
                'id' => 'payment-systems',
                'title' => 'Payment Systems & Fintech',
                'tagline' => 'Production M-PESA Daraja integration, STK Push, and automated webhooks',
                'icon' => 'payments',
                'accent' => 'accent-orange',
                'col_span' => 'span-6',
                'filter_tag' => 'Payments',
                'categories' => ['Payment Systems'],
            ],
            'ai-automation' => [
                'id' => 'ai-automation',
                'title' => 'AI & Automation',
                'tagline' => 'Autonomous pipelines, headless browser scraping, and predictive algorithms',
                'icon' => 'smart_toy',
                'accent' => 'accent-violet',
                'col_span' => 'span-6',
                'filter_tag' => 'AI & Auto',
                'categories' => ['AI & Automation'],
            ],
            'architecture' => [
                'id' => 'architecture',
                'title' => 'System Architecture & Solutions',
                'tagline' => 'Clean architecture patterns validated across mission-critical deployed systems',
                'icon' => 'architecture',
                'accent' => 'accent-slate',
                'col_span' => 'span-12',
                'filter_tag' => 'Architecture',
                'categories' => ['System Design & Architecture', 'Software Projects Experience'],
            ],
        ];

        $bentoGroups = [];
        $consumedSkillIds = [];

        foreach ($domainDefinitions as $domainKey => $def) {
            $domainSkills = $skills->filter(function ($skill) use ($def) {
                return in_array($skill->category, $def['categories'], true);
            })->values();

            if ($domainSkills->isNotEmpty()) {
                foreach ($domainSkills as $s) {
                    $consumedSkillIds[] = $s->id;
                }

                $bentoGroups[] = [
                    'id' => $def['id'],
                    'title' => $def['title'],
                    'tagline' => $def['tagline'],
                    'icon' => $def['icon'],
                    'accent' => $def['accent'],
                    'col_span' => $def['col_span'],
                    'filter_tag' => $def['filter_tag'],
                    'skills' => $domainSkills,
                ];
            }
        }

        // Catch-all: Any custom or newly added categories from admin that are not in the predefined map
        $remainingSkills = $skills->reject(fn ($s) => in_array($s->id, $consumedSkillIds, true))->groupBy('category');
        foreach ($remainingSkills as $customCat => $customSkills) {
            $bentoGroups[] = [
                'id' => \Illuminate\Support\Str::slug($customCat),
                'title' => $customCat,
                'tagline' => 'Specialized technical domain and competencies',
                'icon' => 'code',
                'accent' => 'accent-blue',
                'col_span' => 'span-4',
                'filter_tag' => $customCat,
                'skills' => $customSkills->values(),
            ];
        }

        return $bentoGroups;
    }
}
