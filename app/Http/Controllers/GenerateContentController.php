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
        // Verifica se l'utente è autenticato utilizzando la sessione
        $user = Auth::user();
        $isAuthenticated = !is_null($user);

        // Log l'ID dell'utente se autenticato
        if ($isAuthenticated) {
            Log::info('Authenticated user ID', ['user_id' => $user->id]);
        } else {
            Log::info('No user authenticated');
        }

        // Validazione dei dati in ingresso
        $validated = $request->validate([
            'prompt' => 'required|string|max:1000',
            'platforms' => 'required|array',
            'platforms.*' => 'boolean',
        ]);

        Log::info('Validated data', ['data' => $validated, 'user_authenticated' => $isAuthenticated]);

        $apiKey = config('services.openai.api_key');
        $endpoint = config('services.openai.api_url');
        $responses = [];

        foreach ($validated['platforms'] as $platform => $enabled) {
            if ($enabled) {
                $prompt = "Generate {$platform} content based on: {$validated['prompt']}";
                try {
                    $response = Http::withHeaders([
                        'Authorization' => "Bearer {$apiKey}",
                        'Content-Type' => 'application/json',
                    ])->post($endpoint, [
                                'model' => 'gpt-4o-mini',
                                'messages' => [
                                    ['role' => 'system', 'content' => "You are Poster, a top-tier social media content creator."],
                                    ['role' => 'user', 'content' => "Generate a detailed content strategy for {$platform} based on: {$validated['prompt']}"],
                                ],
                                'max_tokens' => 200,
                                'temperature' => 0.7,
                            ]);

                    if ($response->successful()) {
                        $responses[$platform] = $response->json()['choices'][0]['message']['content'];
                    } else {
                        $responses[$platform] = "Error generating content for {$platform}";
                        Log::warning("Failed to generate content for {$platform}", [
                            'response_status' => $response->status(),
                            'response_body' => $response->body()
                        ]);
                    }
                } catch (\Exception $e) {
                    $responses[$platform] = "Error generating content for {$platform}";
                    Log::error("Exception generating content for {$platform}", [
                        'error_message' => $e->getMessage(),
                        'stack_trace' => $e->getTraceAsString()
                    ]);
                }
            }
        }

        // Salva la ricerca nella tabella search_histories
        if ($isAuthenticated) {
            SearchHistory::create([
                'user_id' => $user->id,
                'prompt' => $validated['prompt'],
                'response' => json_encode($responses), // Salva le risposte come JSON
            ]);
        }

        return response()->json([
            'prompt' => $validated['prompt'],
            'responses' => $responses,
            'user_authenticated' => $isAuthenticated
        ]);
    }
}
