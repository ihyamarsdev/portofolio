<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function analyzeCV(string $cvContent): array
    {
        if (empty($this->apiKey)) {
            return [
                'error' => 'API Key not found. Please set GEMINI_API_KEY in .env file.',
                'ats_score' => 0,
            ];
        }

        $prompt = "Analyze this CV/Resume for ATS (Applicant Tracking System) compatibility. 
        
        Provide the response in strict JSON format with the following structure:
        {
            \"ats_score\": number (0-100),
            \"keywords\": {
                \"found\": [string],
                \"missing\": [string]
            },
            \"format_score\": number (0-100),
            \"suggestions\": [string],
            \"industry_match\": {
                \"devops\": number,
                \"cloud\": number,
                \"sre\": number
            },
            \"summary\": string
        }

        Do not include markdown formatting (```json) in the response, just the raw JSON.

        CV Content:
        " . $cvContent;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '?key=' . $this->apiKey, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ]
                    ]);

            if ($response->failed()) {
                Log::error('Gemini API Error: ' . $response->body());
                return ['error' => 'Failed to connect to Gemini API: ' . $response->status()];
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '{}';

            // Clean markdown code blocks if present data
            $text = str_replace(['```json', '```'], '', $text);

            return json_decode($text, true) ?? ['error' => 'Failed to parse JSON response'];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return ['error' => 'An error occurred: ' . $e->getMessage()];
        }
    }
}
