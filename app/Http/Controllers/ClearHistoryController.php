<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Auth;

class ClearHistoryController extends Controller
{
    public function clearHistory()
    {
        $userId = Auth::id();
        if ($userId) {
            SearchHistory::where('user_id', $userId)->delete();
            return response()->json(['message' => 'History cleared successfully']);
        } else {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }
}