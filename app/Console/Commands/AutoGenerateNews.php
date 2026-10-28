<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\Setting;
use App\Services\GeminiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoGenerateNews extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'news:auto-generate {--force : Force generation even if disabled in settings}';

    /**
     * The console command description.
     */
    protected $description = 'Fetch trending tech news with Google Search grounding via Gemini and publish to the news feed';

    /**
     * Execute the console command.
     */
    public function handle(GeminiService $gemini): int
    {
        $force = $this->option('force');
        $enabled = Setting::get('news_auto_update_enabled', '1');

        if (!$force && $enabled !== '1') {
            $this->info('[' . now()->toDateTimeString() . '] News auto-generation is disabled in settings. Skipping.');
            return self::SUCCESS;
        }

        $this->info('[' . now()->toDateTimeString() . '] Fetching trending tech article via Gemini...');

        $article = $gemini->generateNewsArticle();

        if ($article && !empty($article['title']) && !empty($article['content'])) {
            $news = News::create([
                'title' => $article['title'],
                'content' => $article['content'],
                'source' => $article['source'] ?? 'AI Aggregator',
                'source_url' => $article['source_url'] ?? '',
                'published' => true,
                'is_published' => true,
                'created_at' => now(),
            ]);

            $msg = "Auto-generated news posted successfully: '{$news->title}' (ID: {$news->id})";
            Log::info($msg);
            $this->info('[' . now()->toDateTimeString() . '] ' . $msg);
            return self::SUCCESS;
        }

        $errMsg = 'Failed to generate news article via Gemini. Check logs for details.';
        Log::warning($errMsg);
        $this->error('[' . now()->toDateTimeString() . '] ' . $errMsg);
        return self::FAILURE;
    }
}
