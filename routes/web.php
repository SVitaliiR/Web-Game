<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::controller(ResourceController::class)
    ->group(function () {
        Route::post('/gather-resource', 'gatherResource')->name('gather-resource');
    });

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/api/resources', [ResourceController::class, 'getResources'])
    ->middleware('auth')
    ->name('api.resources');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
