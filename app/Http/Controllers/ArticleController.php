<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function index()
    {
        // Recupera gli articoli con l'utente associato e ordinati per data di creazione
        $articles = Article::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($articles);
    }

    public function store(Request $request)
    {
        // Validazione dei campi
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string', // Assicurati che il corpo possa contenere HTML
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        // Se è presente un'immagine, salvala nella directory 'articles'
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        // Creazione dell'articolo con i dati forniti
        $article = Article::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body, // Salva il contenuto HTML formattato
            'image' => $imagePath,
        ]);

        // Ritorna l'articolo creato come risposta JSON
        return response()->json($article, 201);
    }

    public function show($id)
    {
        // Trova l'articolo tramite ID e carica l'utente associato
        $article = Article::with('user')->findOrFail($id);
        return response()->json($article);
    }
}
