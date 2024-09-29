<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
class PosterStoryController extends Controller
{
    public function index()
    {
        return Inertia::render('PosterStory');
    }
}