<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\BuyCoinsController;
use App\Models\CoinPackage;
use App\Http\Controllers\CoinPackageControllerController;
use App\Http\Controllers\PaymentController;
// routes/web.php (o routes/api.php se preferisci mantenere la separazione)
use App\Http\Controllers\SearchHistoryController;
use App\Http\Controllers\ClearHistoryController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\GoogleTrendsController;


Route::get('/trends', [GoogleTrendsController::class, 'index'])->name('trends.index');
Route::get('/trends/{country}', [GoogleTrendsController::class, 'fetchTrends'])->name('trends.fetch');
Route::get('auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('socialite.redirect');
Route::get('auth/{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])->name('socialite.callback');




Route::get('/search-history', [SearchHistoryController::class, 'getSearchHistory']);
Route::post('/clear-history', [ClearHistoryController::class, 'clearHistory']);
// Middleware 'auth' verifica che l'utente sia autenticato via sessione


// Route per gestire il ritorno da PayPal dopo l'approvazione
Route::get('/payment/success', [PaymentController::class, 'executePayment'])->name('payment.success');
Route::get('/payment/cancel', [PaymentController::class, 'executePayment'])->name('payment.cancel');
Route::post('/payment/create', [PaymentController::class, 'createPayment'])->Middleware('web');
Route::post('/payment/execute', [PaymentController::class, 'executePayment'])->Middleware('web');

Route::get('/buy-coins', [BuyCoinsController::class, 'index'])->name('buy-coins');
Route::get('/calendar', [CalendarController::class, 'index'])->name('calendare');

Route::middleware('web')->get('/user/coin', function (Request $request) {
    return response()->json([
        'coin' => $request->user()->coin, // Restituisce il numero di coin dell'utente autenticato
    ]);
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware('auth:sanctum')->get('/test-auth', function () {
    if (Auth::check()) {
        Log::info('User is authenticated:', ['user_id' => Auth::id()]);

        return response()->json(['user_id' => Auth::id()]);
    } else {
        Log::error('User not authenticated. Session data:', session()->all());
        return response()->json(['error' => 'Unauthenticated'], 401);
    }
});