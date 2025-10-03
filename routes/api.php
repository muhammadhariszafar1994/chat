<?php

use App\Http\Controllers\OpenAIChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyTokenAndCors;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([
    VerifyTokenAndCors::class
])->prefix('openai')->group(function () {
    Route::post('/conversation', [OpenAIChatController::class, 'sendMessageToConversation']);
    Route::get('/conversation', [OpenAIChatController::class, 'getConversationResponses']);
});

// Route::get('chat', [OpenAIChatController::class, 'index']);
// Route::post('chat', [OpenAIChatController::class, 'store']);