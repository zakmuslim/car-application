<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoanApplicationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'role' => auth()->user()?->role ?? 'applicant',
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/create', fn() => redirect()->route('loans.create'));

// API for vehicle data
Route::get('/vehicles', [VehiclesController::class, 'index'])->name('vehicles.index');

// Applicant
Route::get('/apply', [LoanApplicationController::class, 'create'])->name('loans.create');
Route::post('/apply', [LoanApplicationController::class, 'store'])->name('loans.store');
Route::get('/thanks', [LoanApplicationController::class, 'thanks'])->name('loans.thanks');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/consult/loans', [LoanApplicationController::class, 'index'])->name('loans.index');
    Route::get('/consult/loans/{loan}', [LoanApplicationController::class, 'show'])->name('loans.show');
    Route::put('/consult/loans/{loan}', [LoanApplicationController::class, 'update'])->name('loans.update');
    Route::delete('/consult/loans/{loan}', [LoanApplicationController::class, 'destroy'])->name('loans.destroy');

});

require __DIR__ . '/auth.php';
