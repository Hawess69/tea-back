<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FeedPostController;
use App\Http\Controllers\Api\MenPostController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\EventController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // User profile
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        
        // Feed Posts
        Route::get('/feed/posts', [FeedPostController::class, 'index']);
        Route::post('/feed/posts', [FeedPostController::class, 'store']);
        Route::get('/feed/posts/{id}', [FeedPostController::class, 'show']);
        Route::post('/feed/posts/{id}/vote', [FeedPostController::class, 'vote']);
        Route::get('/feed/posts/{id}/comments', [FeedPostController::class, 'comments']);
        Route::post('/feed/posts/{id}/comments', [FeedPostController::class, 'addComment']);
        
        // Men Posts
        Route::get('/men/posts', [MenPostController::class, 'index']);
        Route::post('/men/posts', [MenPostController::class, 'store']);
        Route::get('/men/posts/{id}', [MenPostController::class, 'show']);
        Route::post('/men/posts/{id}/flag', [MenPostController::class, 'flag']);
        Route::get('/men/posts/{id}/comments', [MenPostController::class, 'comments']);
        Route::post('/men/posts/{id}/comments', [MenPostController::class, 'addComment']);
        
        // Alerts
        Route::get('/alerts', [AlertController::class, 'index']);
        Route::post('/alerts', [AlertController::class, 'store']);
        Route::delete('/alerts/{id}', [AlertController::class, 'destroy']);
        
        // Events
        Route::get('/events', [EventController::class, 'index']);
        
        // Notifications
        Route::get('/notifications', [AuthController::class, 'notifications']);
    });
});
