<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Persons;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('dashboard', function (Request $request) {
    $fromDate = request('startDate') ?? '1800-01-01';
    $toDate = request('endDate') ?? '2045-01-01';
    $persons = Persons::whereBetween('dob', [$fromDate, $toDate])
        ->orderBy('dob', 'desc')
        ->get();
    $males = Persons::where('gender', 'male')
        ->whereBetween('dob', [$fromDate, $toDate])
        ->latest('dob')
        ->get();
    $females = Persons::where('gender', 'female')
        ->whereBetween('dob', [$fromDate, $toDate])
        ->latest('dob')
        ->get();
    return Inertia::render(
        'Dashboard/Index',
        compact('persons', 'males', 'females', 'fromDate', 'toDate')
    );
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
