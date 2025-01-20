<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Persons;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('dashboard', function () {
    $persons = Persons::latest()->get();
    $males = Persons::where('gender', 'male')->latest()->get();
    $females = Persons::where('gender', 'female')->latest()->get();
    $dob = Persons::orderBy('dob', 'desc')->get();
    return Inertia::render('Dashboard/Index', [
        'persons' => $persons,
        'males' => $males,
        'females' => $females,
        'dob' => $dob,
    ]);
})->name('dashboard');

Route::resource('persons', PersonsController::class);

Route::get('/persons/add/5', [PersonsController::class, 'addPersons']);

Route::get('/persons/delete/5', [PersonsController::class, 'delete5Persons']);

Route::get('/persons/delete/all', [
    PersonsController::class,
    'deleteAllPersons',
]);

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
