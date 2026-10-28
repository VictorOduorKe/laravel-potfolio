<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display the paginated news feed page
     */
    public function index(Request $request): View
    {
        $articles = News::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pages.news', [
            'articles' => $articles,
        ]);
    }
}
