<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // Assicurati che l'utente sia autenticato
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $events = auth()->user()->events()->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event = auth()->user()->events()->create($validatedData);

        return response()->json($event, 201);
    }

    // Altri metodi del controller...
}