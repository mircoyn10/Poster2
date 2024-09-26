<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SearchHistory;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $events = auth()->user()->events()->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'prompt' => $event->prompt,
                'response' => $event->response,
                'start' => $event->start_date,
                'end' => $event->end_date,
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'prompt' => 'nullable|string|max:1000', // Validazione per prompt
            'response' => 'nullable|string', // Validazione per response
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event = auth()->user()->events()->create($validatedData);

        return response()->json($event, 201);
    }

    public function destroy($id)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $event = auth()->user()->events()->find($id);

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully'], 200);
    }

    public function getUserPrompts()
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Ottieni tutti i prompt e le response dell'utente autenticato
        $prompts = SearchHistory::where('user_id', auth()->id())
            ->get(['prompt', 'response']); // Recupera sia prompt che response

        // Restituisci i prompt e le response come array di oggetti
        return response()->json($prompts);
    }

}
