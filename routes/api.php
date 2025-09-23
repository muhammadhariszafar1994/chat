<?php

use App\Http\Controllers\OpenAIChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('chat', [OpenAIChatController::class, 'index'])->name('chat.index');
Route::post('chat', [OpenAIChatController::class, 'store'])->name('chat.store');
