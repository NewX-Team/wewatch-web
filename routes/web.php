<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CreatorController;
use App\Http\Controllers\CreatorMovieController;
use App\Http\Controllers\CreatorSubscriptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'superAdmin'])->name('admin.dashboard');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::patch('/admin/users/{user}/toggle-suspend', [AdminUserController::class, 'toggleSuspend'])->name('admin.users.toggle-suspend');
        Route::patch('/admin/users/{user}/toggle-verification', [AdminUserController::class, 'toggleVerification'])->name('admin.users.toggle-verification');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

        // Announcement / Broadcast Management
        Route::post('/admin/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
        Route::patch('/admin/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggleStatus'])->name('admin.announcements.toggle');
        Route::delete('/admin/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
    });

    Route::middleware('role:creator,super_admin')->group(function () {
        Route::get('/creator/dashboard', [DashboardController::class, 'creator'])->name('creator.dashboard');
        Route::post('/creator/movies', [CreatorMovieController::class, 'store'])->name('creator.movies.store');
        Route::post('/creator/movies/{movie}/episodes', [CreatorMovieController::class, 'addEpisode'])->name('creator.movies.episodes.store');
        Route::patch('/creator/movies/{movie}/toggle-status', [CreatorMovieController::class, 'toggleStatus'])->name('creator.movies.toggle-status');
        Route::delete('/creator/movies/{movie}', [CreatorMovieController::class, 'destroy'])->name('creator.movies.destroy');
    });

    Route::middleware('role:user,creator,super_admin')->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'user'])->name('user.dashboard');
        Route::get('/user/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
        Route::get('/user/subscriptions', [SubscriptionController::class, 'userSubscriptions'])->name('user.subscriptions');
        Route::get('/user/announcements', [AnnouncementController::class, 'userAnnouncements'])->name('user.announcements');
    });

    Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');
    Route::post('/movies/{movie}/toggle-favorite', [FavoriteController::class, 'toggle'])->name('movies.toggle-favorite');
    Route::get('/creators/{id}', [CreatorController::class, 'show'])->name('creators.show');
    Route::post('/creators/{user}/toggle-subscription', [CreatorSubscriptionController::class, 'toggle'])->name('creators.toggle-subscription');
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
