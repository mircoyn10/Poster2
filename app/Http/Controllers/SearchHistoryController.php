<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Auth;

class SearchHistoryController extends Controller
{
    public function getSearchHistory()
    {
        $userId = Auth::id();
        if ($userId) {
            $searchHistory = SearchHistory::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'prompt' => $item->prompt,
                        'response' => json_decode($item->response, true),
                        'created_at' => $item->created_at->toDateTimeString()
                    ];
                });

            \Log::info('Search History:', $searchHistory->toArray()); // Log per debug
            return response()->json($searchHistory);
        } else {
            return response()->json(['error' => 'Utente non autenticato'], 401);
        }
    }
    public function getUserPrompts()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Ottieni i prompt e le response dell'utente autenticato
        $prompts = SearchHistory::where('user_id', auth()->id())
            ->get(['prompt', 'response']); // Assicurati di recuperare sia `prompt` che `response`

        return response()->json($prompts);
    }

}