<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\TextController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:15,1');
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->get('/check-auth', function () {
    return response()->json(['ok' => true]);
});
Route::middleware(['api', 'auth:api'])->group(function(){
    Route::controller(ImageController::class)->prefix('image')->group(function(){
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::delete('/{id}', 'visible');
    });

    Route::controller(AchievementController::class)->prefix('achievement')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'visible');
    });

    Route::controller(InformationController::class)->prefix('information')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'visible');
    });

    Route::controller(SocialController::class)->prefix('social')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'visible');
    });

    Route::controller(ResumeController::class)->prefix('resume')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'visible');
    });

    Route::controller(SkillController::class)->prefix('skill')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'visible');
    });

    Route::controller(TextController::class)->prefix('text')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::post('/{id}', 'update');
        Route::put('/{id}', 'visible');
    });

    Route::controller(PortfolioController::class)->prefix('portfolio')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::post('/{id}', 'update');
        Route::put('/visible/{id}', 'visible');
        Route::put('/reorder', 'reorder');
    });

    Route::controller(BlogController::class)->prefix('blog')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        Route::post('/{id}', 'update');
        Route::put('/visible/{id}', 'visible');
        Route::put('/reorder', 'reorder');
    });

    Route::controller(EmailController::class)->prefix('email')->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'create');
        // Route::post('/{id}', 'update');
        Route::post('/visible', 'visible');
    });
});

