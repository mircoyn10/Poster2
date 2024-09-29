<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CommunityController extends Controller
{
    public function index()
    {
        return Inertia::render('Community', [
            'instagramUrl' => 'https://instagram.com/yourapp',
            'instagramHandle' => 'YourApp',
            'tiktokUrl' => 'https://tiktok.com/@yourapp',
            'tiktokHandle' => 'YourApp',
            'blogUrl' => url('/blog'), // or however you want to generate this URL
        ]);
    }
}