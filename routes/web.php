<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\ResourceController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $buildings = (new BuildingController())->index();
    return Inertia::render('Dashboard', [
        'buildings' => $buildings->getData()->buildings,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/gather-resource', [ResourceController::class, 'gatherResource']);
    Route::get('/resources', [ResourceController::class, 'getResources']);
    Route::get('/leaderboard', [ResourceController::class, 'leaderboard']);

    // Building routes
    Route::post('/buildings', [BuildingController::class, 'store']);
    Route::delete('/buildings/{id}', [BuildingController::class, 'destroy'])->middleware('auth');
});

require __DIR__.'/auth.php';