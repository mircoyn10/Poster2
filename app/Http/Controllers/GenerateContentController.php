<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SearchHistory;

class GenerateContentController extends Controller
{
    public function generateContent(Request $request)
    {
        // Verifica se l'utente è autenticato
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401); // Return error if not authenticated
        }

        // Validazione dei dati in ingresso
        $validated = $request->validate([
            'prompt' => 'required|string|max:1000',
            'platforms' => 'required|array',
            'platforms.*' => 'boolean',
        ]);

        // Check if user has enough coins
        if ($user->coin < 10) {
            return response()->json(['error' => 'Not enough coins'], 400);
        }

        // Sottrai 10 coin dall'utente
        $user->coin -= 10;
        $user->save();

        $apiKey = config('services.openai.api_key');
        $endpoint = config('services.openai.api_url');
        $responses = [];

        // Riconosci la lingua del prompt utilizzando OpenAI
        $language = $this->detectLanguageWithAI($validated['prompt'], $apiKey, $endpoint);

        foreach ($validated['platforms'] as $platform => $enabled) {
            if ($enabled) {
                // Prompt specifico per ogni piattaforma
                $prompt = $this->generatePlatformPrompt($validated['prompt'], $platform, $language);

                try {
                    $response = Http::withHeaders([
                        'Authorization' => "Bearer {$apiKey}",
                        'Content-Type' => 'application/json',
                    ])->post($endpoint, [
                                'model' => 'gpt-4o-mini',
                                'messages' => [
                                    ['role' => 'system', 'content' => "You are Poster, a top-tier social media content creator."],
                                    ['role' => 'user', 'content' => $prompt],
                                ],
                                'max_tokens' => 250, // Limita i token per non avere risposte troppo lunghe
                                'temperature' => 0.7,
                            ]);

                    if ($response->successful()) {
                        $responses[$platform] = $this->formatResponse($response->json()['choices'][0]['message']['content'], $platform, $language);
                    } else {
                        $responses[$platform] = "Error generating content for {$platform}";
                    }
                } catch (\Exception $e) {
                    $responses[$platform] = "Error generating content for {$platform}";
                }
            }
        }

        // Salva la ricerca nella tabella search_histories
        SearchHistory::create([
            'user_id' => $user->id,
            'prompt' => $validated['prompt'],
            'response' => json_encode($responses), // Salva le risposte come JSON
        ]);

        return response()->json([
            'prompt' => $validated['prompt'],
            'responses' => $responses,
            'remaining_coins' => $user->coin // Include i coin rimanenti nella risposta
        ]);
    }

    /**
     * Funzione per rilevare la lingua del prompt usando OpenAI.
     */
    private function detectLanguageWithAI($text, $apiKey, $endpoint)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post($endpoint, [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'user', 'content' => "Identify the language of the following text: \"$text\". Please respond with only the language name."],
                        ],
                        'max_tokens' => 20,
                        'temperature' => 0,
                    ]);

            if ($response->successful()) {
                return trim($response->json()['choices'][0]['message']['content']);
            }
        } catch (\Exception $e) {
            Log::error("Error detecting language: " . $e->getMessage());
            return 'English'; // Default fallback
        }
        return 'English'; // Default fallback
    }

    /**
     * Genera un prompt specifico per la piattaforma selezionata.
     */
    private function generatePlatformPrompt($prompt, $platform, $language)
    {
        $platformSettings = [
            'instagram' => [
                'focus' => 'visually appealing content with engaging captions',
                'elements' => 'Main caption (max 150 characters), 3-5 relevant hashtags, Emoji suggestions',
                'format' => "1. Visual Idea:\n2. Caption:\n3. Hashtags:\n4. Emojis:"
            ],
            'tiktok' => [
                'focus' => 'short, creative video concepts with trending elements',
                'elements' => 'Video concept (15-60 seconds), Background music or sound suggestion, 2-3 relevant hashtags',
                'format' => "1. Video Concept:\n2. Sound/Music:\n3. Hashtags:\n4. Call-to-Action:"
            ],
            'twitter' => [
                'focus' => 'concise, engaging tweets that spark conversation',
                'elements' => 'Tweet text (max 280 characters), 1-2 relevant hashtags, Engagement question',
                'format' => "1. Tweet:\n2. Hashtags:\n3. Engagement Question:"
            ],
            'facebook' => [
                'focus' => 'community-driven content with detailed insights',
                'elements' => 'Post text (200-300 characters), Content type suggestion (poll, image, video, etc.), Call-to-action',
                'format' => "1. Post Content:\n2. Content Type:\n3. Call-to-Action:\n4. Discussion Starter:"
            ]
        ];

        $setting = $platformSettings[$platform];

        return "In {$language}, create an innovative and engaging content idea for {$platform} based on: '{$prompt}'. 
        Focus on {$setting['focus']}. 
        Include these elements: {$setting['elements']}.
        Be creative, trendy, and audience-focused. Aim to maximize engagement and virality potential.
        Format your response as follows:
        {$setting['format']}
        
        Ensure each element is concise, impactful, and platform-optimized. Think outside the box and surprise the audience!";
    }

    /**
     * Format the response in a user-friendly manner based on the platform and language.
     */
    private function formatResponse($response, $platform, $language)
    {
        return $response;
    }


}
