<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class ChatController extends Controller
{
    public function __construct(
        protected GeminiService $geminiService
    ) {}

    /**
     * Handle AI Assistant chat interactions
     */
    public function chat(Request $request): JsonResponse
    {
        $ip = $request->ip() ?: '127.0.0.1';
        $key = 'chat:' . $ip;

        // Rate limiting: 20 requests per minute
        if (RateLimiter::tooManyAttempts($key, 20)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many requests. Please wait {$seconds} seconds before asking again.",
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $validated = $request->validate([
            'messages' => ['required', 'array', 'min:1'],
            'messages.*.role' => ['required', 'string', 'in:user,assistant,model'],
            'messages.*.content' => ['required', 'string', 'max:2000'],
            'session_id' => ['nullable', 'string', 'max:100'],
        ]);

        $sanitizedMessages = [];
        foreach ($validated['messages'] as $msg) {
            $sanitizedMessages[] = [
                'role' => $msg['role'],
                'content' => strip_tags(trim($msg['content'])),
            ];
        }

        try {
            $reply = $this->geminiService->generateResponse($sanitizedMessages);
            return response()->json([
                'success' => true,
                'reply' => $reply,
            ]);
        } catch (\Throwable $e) {
            Log::error('ChatController error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to generate response at this time.',
            ], 500);
        }
    }
}
