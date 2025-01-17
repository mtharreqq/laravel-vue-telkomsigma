<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Persons;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', ['data' => Persons::all()]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/data-management', function () {
    return Inertia::render('DataManagement', ['data' => Persons::all()]);
})
    ->middleware(['auth', 'verified'])
    ->name('data-management');

Route::get('/persons/add/5', [
    PersonsController::class,
    'addPersons',
])->middleware(['auth', 'verified']);

Route::get('/persons/delete/5', [
    PersonsController::class,
    'delete5Persons',
])->middleware(['auth', 'verified']);

Route::get('/persons/delete/all', [
    PersonsController::class,
    'deleteAllPersons',
])->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name(
        'profile.edit'
    );
    Route::patch('/profile', [ProfileController::class, 'update'])->name(
        'profile.update'
    );
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name(
        'profile.destroy'
    );
});

require __DIR__ . '/auth.php';
