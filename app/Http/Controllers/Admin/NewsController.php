<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Setting;
use App\Services\GeminiService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class NewsController extends Controller
{
    public function index(): View
    {
        $articles = News::orderBy('created_at', 'desc')->paginate(15);
        $autoUpdateEnabled = Setting::get('news_auto_update_enabled', '1') === '1';
        $updateInterval = Setting::get('news_update_interval_hours', '24');

        return view('admin.news.index', compact('articles', 'autoUpdateEnabled', 'updateInterval'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'source' => ['nullable', 'string', 'max:100'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'source' => $validated['source'] ?? 'Manual Post',
            'source_url' => $validated['source_url'] ?? '',
            'published' => $request->boolean('is_published', true),
            'is_published' => $request->boolean('is_published', true),
            'created_at' => now(),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Article created successfully.');
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'source' => ['nullable', 'string', 'max:100'],
            'source_url' => ['nullable', 'url', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $news->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'source' => $validated['source'] ?? $news->source,
            'source_url' => $validated['source_url'] ?? '',
            'published' => $request->boolean('is_published'),
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.news.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(News $news): RedirectResponse
    {
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Article deleted.');
    }

    public function togglePublish(News $news): JsonResponse
    {
        $state = !$news->is_published;
        $news->update([
            'published' => $state,
            'is_published' => $state,
        ]);
        return response()->json(['success' => true, 'is_published' => $state]);
    }

    /**
     * Trigger AI News generation immediately
     */
    public function generateNow(GeminiService $gemini): RedirectResponse
    {
        $exitCode = Artisan::call('news:auto-generate', ['--force' => true]);

        if ($exitCode === 0) {
            return redirect()->route('admin.news.index')->with('success', 'New tech article generated successfully by Gemini and published.');
        }

        return redirect()->route('admin.news.index')->withErrors(['gemini' => 'Failed to generate article. Check your Gemini API key in settings or logs.']);
    }
}
