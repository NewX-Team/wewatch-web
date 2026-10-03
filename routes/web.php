<?php

use App\Http\Controllers\CreatorController;
use App\Http\Controllers\DashboardController;
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
    });

    Route::middleware('role:creator')->group(function () {
        Route::get('/creator/dashboard', [DashboardController::class, 'creator'])->name('creator.dashboard');
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'user'])->name('user.dashboard');
    });

    Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movies.show');
    Route::get('/creators/{id}', [CreatorController::class, 'show'])->name('creators.show');
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
