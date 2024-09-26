<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SeoController extends Controller
{
    public function generateSeoContent(Request $request)
    {
        // Verifica se l'utente è autenticato
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401); // Return error if not authenticated
        }

        // Validazione dei dati in ingresso
        $validated = $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);

        // Verifica se l'utente ha abbastanza coin
        if ($user->coin < 5) {
            return response()->json(['error' => 'Not enough coins'], 400);
        }

        // Sottrai 5 coin dall'utente
        $user->coin -= 5;
        $user->save();

        $apiKey = config('services.openai.api_key');
        $endpoint = config('services.openai.api_url');

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->post($endpoint, [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are an SEO expert focused on improving search engine rankings.'],
                            ['role' => 'user', 'content' => $validated['prompt']],
                        ],
                        'max_tokens' => 500,
                        'temperature' => 0.7,
                    ]);

            if ($response->successful()) {
                return response()->json([
                    'content' => $response->json()['choices'][0]['message']['content'],
                    'remaining_coins' => $user->coin
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error generating SEO content: ' . $e->getMessage());
            return response()->json(['error' => 'Error generating content'], 500);
        }

        return response()->json(['content' => 'Error generating content']);
    }
}
