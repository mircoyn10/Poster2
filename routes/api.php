<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GenerateContentController;
use App\Models\CoinPackage;
use App\Http\Controllers\BuyCoinsController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SeoController;


Route::post('/generate-seo-content', [SeoController::class, 'generateSeoContent'])->middleware('web');

Route::apiResource('events', EventController::class)->middleware('web');
Route::get('/coin-packages', function () {
    return CoinPackage::all();
})->middleware('web');
Route::get('/buy-coins', [BuyCoinsController::class, 'index'])->name('buy-coins')->middleware('web');
#Route::middleware('auth:sanctum')->post('/generate-content', [GenerateContentController::class, 'generateContent']);
Route::post('/generate-content', [GenerateContentController::class, 'generateContent'])->middleware('web');
Route::get('/user', function (Request $request) {

    return $request->user();

})->middleware('auth:sanctum');
