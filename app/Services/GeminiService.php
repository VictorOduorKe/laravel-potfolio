<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Skill;
use App\Models\Roadmap;
use App\Models\News;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.api_key', '');
        $this->model = (string) config('services.gemini.model', 'gemini-2.5-flash');
    }

    /**
     * Generate grounded portfolio assistant response
     */
    public function generateResponse(array $messages, ?string $customInstruction = null): string
    {
        if (empty($this->apiKey)) {
            return "The AI assistant is temporarily unavailable because the API key is not configured.";
        }

        $systemInstruction = $customInstruction ?: $this->buildPortfolioContext();
        $model = !empty($this->model) ? $this->model : 'gemini-2.5-flash';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $this->apiKey;

        $contents = [];
        foreach ($messages as $msg) {
            $role = in_array($msg['role'] ?? '', ['assistant', 'model']) ? 'model' : 'user';
            $text = $msg['content'] ?? $msg['message'] ?? '';
            if (!empty($text)) {
                $contents[] = [
                    'role' => $role,
                    'parts' => [['text' => (string) $text]],
                ];
            }
        }

        if (empty($contents)) {
            return "Hello! How can I assist you with Victor Oduor's portfolio and projects today?";
        }

        $payload = [
            'contents' => $contents,
            'systemInstruction' => [
                'parts' => [['text' => $systemInstruction]],
            ],
            'generationConfig' => [
                'temperature' => 0.2,
                'maxOutputTokens' => 800,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(15)->post($url, $payload);

            if ($response->successful()) {
                $result = $response->json();
                return $result['candidates'][0]['content']['parts'][0]['text']
                    ?? "I don't have enough information to answer that question accurately.";
            }

            Log::error('Gemini API Error: ' . $response->body());
            $errorMsg = $response->json('error.message', 'Unknown Gemini error');
            return "I apologize, but I encountered an issue: " . $errorMsg;
        } catch (\Throwable $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return "I apologize, but I'm unable to connect to the assistant service right now. Please try again shortly or contact Victor directly via the contact form.";
        }
    }

    /**
     * Generate trending tech news with Google Search grounding
     */
    public function generateNewsArticle(): ?array
    {
        if (empty($this->apiKey)) {
            return null;
        }

        $model = !empty($this->model) ? $this->model : 'gemini-2.5-flash';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key=" . $this->apiKey;

        $systemInstruction = "You are an expert tech journalist. Search the web to identify the most current trending topic in software development, AI, cloud computing, or cybersecurity. Write a short, engaging news article about it. Format your response strictly as a JSON object with four keys: 'title' (string), 'content' (string containing multiple paragraphs separated by newlines), 'source' (string, the name of the publication or website you got this from), and 'source_url' (string, ALWAYS set this to exactly \"\"). Do NOT include long URLs in your output. Do NOT wrap the JSON in markdown code blocks.";

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [['text' => 'Generate the latest trending tech news article in JSON format.']],
                ],
            ],
            'systemInstruction' => [
                'parts' => [['text' => $systemInstruction]],
            ],
            'tools' => [
                ['googleSearch' => new \stdClass()],
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 1000,
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, $payload);

            if (!$response->successful()) {
                Log::error('Gemini News Generation API Error: ' . $response->body());
                return null;
            }

            $result = $response->json();
            $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;

            $groundingChunks = $result['candidates'][0]['groundingMetadata']['groundingChunks'] ?? [];
            $realUrl = null;
            $realSource = null;
            if (!empty($groundingChunks)) {
                foreach ($groundingChunks as $chunk) {
                    if (isset($chunk['web']['uri'])) {
                        $realUrl = $chunk['web']['uri'];
                        $realSource = $chunk['web']['title'] ?? 'Google Search';
                        break;
                    }
                }
            }

            if ($text) {
                $cleaned = preg_replace('/```json/i', '', $text);
                $cleaned = preg_replace('/```/', '', $cleaned);
                $data = json_decode(trim($cleaned), true);

                if (json_last_error() === JSON_ERROR_NONE && isset($data['title'], $data['content'])) {
                    if ($realUrl) {
                        $data['source_url'] = $realUrl;
                        if (!empty($realSource)) {
                            $data['source'] = $realSource;
                        }
                    }
                    return $data;
                }

                Log::warning('Gemini News JSON Parse Failed: ' . json_last_error_msg() . ' Text: ' . $cleaned);
            }
        } catch (\Throwable $e) {
            Log::error('Gemini News Generation Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Build system context dynamically from database
     */
    public function buildPortfolioContext(): string
    {
        $context = "You are Victor Oduor's professional portfolio AI assistant on TheTechCraft.\n";
        $context .= "Your goal is to answer visitor questions warmly, accurately, and professionally about Victor's background, projects, engineering skills, and career roadmap.\n\n";
        $context .= "GUIDELINES:\n";
        $context .= "- Speak strictly on behalf of Victor's portfolio and software engineering domain.\n";
        $context .= "- Keep responses concise, helpful, and technically articulate.\n";
        $context .= "- If asked questions completely unrelated to tech, programming, or Victor's work, politely steer back to Victor's portfolio or offer to send a message via the contact form.\n\n";

        try {
            // Live projects
            $projects = Project::where('is_published', true)->orderBy('created_at', 'desc')->get();
            if ($projects->isNotEmpty()) {
                $context .= "PORTFOLIO PROJECTS:\n";
                foreach ($projects as $p) {
                    $context .= "- {$p->title}: {$p->impact_line}. {$p->description}. Repo: {$p->github_repo_url}\n";
                }
                $context .= "\n";
            }

            // Skills
            $skills = Skill::where('is_published', true)->orderBy('category')->get();
            if ($skills->isNotEmpty()) {
                $context .= "CORE SKILLS & TECHNOLOGIES:\n";
                $grouped = $skills->groupBy('category');
                foreach ($grouped as $cat => $items) {
                    $names = $items->pluck('name')->implode(', ');
                    $context .= "- {$cat}: {$names}\n";
                }
                $context .= "\n";
            }

            // Roadmap
            $roadmaps = Roadmap::where('is_published', true)->orderBy('target_date', 'desc')->take(5)->get();
            if ($roadmaps->isNotEmpty()) {
                $context .= "ENGINEERING ROADMAP & MILESTONES:\n";
                foreach ($roadmaps as $r) {
                    $context .= "- {$r->title} [Status: {$r->status}]: {$r->description}\n";
                }
                $context .= "\n";
            }
        } catch (\Throwable $e) {
            // Fallback static context if database is temporarily unavailable during migration
            $context .= "Victor Oduor is a Full Stack Software Engineer specializing in Laravel, PHP, Python, React, MySQL, Cloud architecture, and AI-integrated systems.\n";
        }

        return $context;
    }
}
