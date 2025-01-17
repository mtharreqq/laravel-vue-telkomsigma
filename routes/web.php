<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $response = Http::get('https://randomuser.me/api/?results=25');

    // // Get all data
    // if ($response->successful()) {
    //     return Inertia::render('Dashboard', ['data' => $response->json()]);
    // }

    // Extract the necessary fields: name, email, and phone
    if ($response->successful()) {
        $filteredData = collect($response->json()['results'])->map(function (
            $person
        ) {
            return [
                'name' => "{$person['name']['first']} {$person['name']['last']}",
                'email' => $person['email'],
                'gender' => $person['gender'],
                'phone' => $person['phone'],
                'dob' => "{$person['dob']['date']}",
                'country' => "{$person['location']['country']}",
            ];
        });

        return Inertia::render('Dashboard', ['data' => $filteredData]);
    }

    throw new \Exception('Failed to fetch persons: ' . $response->body());
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/data-management', function () {
    $response = Http::get('https://randomuser.me/api/?results=25');

    if ($response->successful()) {
        $filteredData = collect($response->json()['results'])->map(function (
            $person
        ) {
            return [
                'name' => "{$person['name']['first']} {$person['name']['last']}",
                'email' => $person['email'],
                'gender' => $person['gender'],
                'phone' => $person['phone'],
                'dob' => "{$person['dob']['date']}",
                'country' => "{$person['location']['country']}",
            ];
        });

        return Inertia::render('DataManagement', ['data' => $filteredData]);
    }

    throw new \Exception('Failed to fetch persons: ' . $response->body());
})
    ->middleware(['auth', 'verified'])
    ->name('data-management');

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
