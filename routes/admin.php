<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\VisitorController;
use App\Http\Controllers\Admin\OpenAIAdminController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\CheckInternalUserStatus;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store']);

Route::middleware([AdminAuthenticate::class, CheckInternalUserStatus::class])->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Role
    Route::resource('/roles', RoleController::class)
        ->except(['show']);

    // Permission
    Route::resource('/permissions', PermissionController::class)
        ->except(['show']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // User
    Route::resource('/users', UserController::class);

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Theme
    Route::resource('/themes', ThemeController::class);

    // Project
    
    Route::get('/projects/api-keys', [ProjectController::class, 'projectApiKeys'])->name('projectApiKeys');
    Route::get('/projects/api-key-details', [ProjectController::class, 'projectApiKeyDetails'])->name('projectApiKeyDetails');
    Route::resource('/projects', ProjectController::class);

    // Project
    Route::resource('/visitors', VisitorController::class);

    Route::prefix('openai-projects')->name('openai-projects.')->group(function () {
        // List Projects
        Route::get('/', [OpenAIAdminController::class, 'index'])->name('index');
        
        // Create Project
        Route::get('create', [OpenAIAdminController::class, 'create'])->name('create');
        Route::post('/', [OpenAIAdminController::class, 'store'])->name('store');
        
        // Edit Project
        Route::get('{projectId}/edit', [OpenAIAdminController::class, 'edit'])->name('edit');
        Route::put('{projectId}', [OpenAIAdminController::class, 'update'])->name('update');
        
        // Show Project Details
        Route::get('{projectId}', [OpenAIAdminController::class, 'show'])->name('show');
        
        // Archive Project
        Route::post('{projectId}/archive', [OpenAIAdminController::class, 'archive'])->name('archive');
    });


    
    
});
