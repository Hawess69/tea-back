<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\FeedPost;
use App\Models\MenPost;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Profile Route (placeholder)
Route::get('/profile', function () {
    return view('profile');
})->name('profile');

// Logout Route
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Logout GET fallback - redirect to login with message
Route::get('/logout', function () {
    return redirect()->route('login')->with('message', 'Please use the logout button to sign out properly.');
});

// Admin Routes - Protected by authentication
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // User management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::post('/users/{user}/ban', [App\Http\Controllers\Admin\UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/role', [App\Http\Controllers\Admin\UserController::class, 'changeRole'])->name('users.role');
    
    // Posts management
    Route::resource('feed-posts', App\Http\Controllers\Admin\FeedPostController::class);
    Route::resource('men-posts', App\Http\Controllers\Admin\MenPostController::class);
    Route::post('/feed-posts/{feedPost}/hide', [App\Http\Controllers\Admin\FeedPostController::class, 'hide'])->name('feed-posts.hide');
    Route::post('/feed-posts/{feedPost}/publish', [App\Http\Controllers\Admin\FeedPostController::class, 'publish'])->name('feed-posts.publish');
    Route::post('/men-posts/{menPost}/hide', [App\Http\Controllers\Admin\MenPostController::class, 'hide'])->name('men-posts.hide');
    Route::post('/men-posts/{menPost}/publish', [App\Http\Controllers\Admin\MenPostController::class, 'publish'])->name('men-posts.publish');
    
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
    
    // Comment management routes
    Route::put('/comments/{id}', [App\Http\Controllers\Admin\CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');
});

// Simple Admin Dashboard (Temporary - JSON API)
Route::get('/admin', function () {
    $users = User::count();
    $feedPosts = FeedPost::count();
    $menPosts = MenPost::count();
    
    return response()->json([
        'message' => 'Tea Backend Admin Dashboard',
        'stats' => [
            'total_users' => $users,
            'feed_posts' => $feedPosts,
            'men_posts' => $menPosts,
        ],
        'endpoints' => [
            'users' => '/api/v1/users',
            'feed_posts' => '/api/v1/feed/posts',
            'men_posts' => '/api/v1/men/posts',
        ],
        'note' => 'Filament admin panel will be available after installing dependencies'
    ]);
});
