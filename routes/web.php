<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\EmbedScriptController;
use App\Http\Controllers\OpenAIChatController;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, array_keys(config('panel.available_languages')))) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('set-language');

Route::get('/', function () {
    return view('index');
});

Route::get('/embed.js', [EmbedScriptController::class, 'script']);

Route::prefix('openai')->group(function () {
    Route::get('/', [OpenAIChatController::class, 'showChatPage'])->name('openai.chat');
    Route::post('/conversation', [OpenAIChatController::class, 'createConversation']);
    Route::post('/conversation/{conversationId}', [OpenAIChatController::class, 'sendMessageToConversation']);
    Route::get('/conversation/{conversationId}', [OpenAIChatController::class, 'getConversationResponses']);
});

// Route::get('/', function () {
//     return view('welcome');
// });

require __DIR__.'/auth.php';