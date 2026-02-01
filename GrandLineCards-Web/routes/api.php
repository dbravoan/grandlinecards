<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\CardController;

Route::prefix('v1')->group(function () {
    Route::get('/cards', [CardController::class, 'index']);
    Route::get('/cards/{card}', [CardController::class, 'show']);
    
    Route::get('/decks', [App\Http\Controllers\Api\V1\DeckController::class, 'index']);
    
    Route::middleware('auth:api')->group(function () {
        Route::post('/decks', [App\Http\Controllers\Api\V1\DeckController::class, 'store']);
        // Suggestions
        Route::post('/event-suggestions', [\App\Http\Controllers\Api\V1\EventSuggestionController::class, 'store']);
    });
});

Route::get('/search/cards', [App\Http\Controllers\Api\SearchController::class, 'search']);
