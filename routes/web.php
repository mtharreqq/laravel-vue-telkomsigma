<?php

use App\Http\Controllers\PersonsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Persons;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('dashboard', [PersonsController::class, 'dashboard'])->name(
    'dashboard'
);

Route::resource('persons', PersonsController::class);

// Custom routes for additional actions
Route::prefix('persons')->group(function () {
    Route::get('add/5', [PersonsController::class, 'addPersons']);
    Route::get('delete/5', [PersonsController::class, 'delete5Persons']);
    Route::get('delete/all', [PersonsController::class, 'deleteAllPersons']);
});
