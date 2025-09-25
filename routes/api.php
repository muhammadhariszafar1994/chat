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
])->group(function () {
    Route::get('chat', [OpenAIChatController::class, 'index']);
    Route::post('chat', [OpenAIChatController::class, 'store']);
});