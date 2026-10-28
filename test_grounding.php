<?php
require 'backend/src/Services/GeminiService.php';

$env = @parse_ini_file(__DIR__ . '/backend/.env');
$apiKey = $env['GEMINI_API_KEY'] ?? '';

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;
$payload = [
    'contents' => [['role' => 'user', 'parts' => [['text' => 'What is the current trending tech news?']]]],
    'tools' => [['googleSearch' => new \stdClass()]]
];
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);
$data = json_decode($response, true);
$chunks = $data['candidates'][0]['groundingMetadata']['groundingChunks'] ?? [];
echo "Grounding Chunks:\n";
print_r($chunks);

