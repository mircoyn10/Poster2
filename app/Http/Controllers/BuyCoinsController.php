<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class BuyCoinsController extends Controller
{
    public function index()
    {
        return Inertia::render('BuyCoins');
    }
}

