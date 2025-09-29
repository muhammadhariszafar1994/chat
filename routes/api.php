<?php

use App\Http\Controllers\OpenAIChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyToken;
use App\Http\Middleware\GlobalCorsFromProjects;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware([
    VerifyToken::class,
    GlobalCorsFromProjects::class
])->prefix('openai')->group(function () {
    Route::get('/', [OpenAIChatController::class, 'showChatPage'])->name('openai.chat');
    Route::post('/conversation', [OpenAIChatController::class, 'createConversation']);
    Route::post('/conversation/{conversationId}', [OpenAIChatController::class, 'sendMessageToConversation']);
    Route::get('/conversation/{conversationId}', [OpenAIChatController::class, 'getConversationResponses']);
});

// Route::get('chat', [OpenAIChatController::class, 'index']);
    // Route::post('chat', [OpenAIChatController::class, 'store']);