<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\UserController;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'index');

Route::controller(UserController::class)->group(function () {
    Route::get('dashboard', 'dashboard')
        ->middleware(['auth'])
        ->name('dashboard');

    Route::get('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');
});

Route::resource('challenges', ChallengeController::class);

Route::view('terms', 'info', ['info' => 'terms']);

Route::view('privacy', 'info', ['info' => 'privacy']);

Volt::route('files', 'filemanager')
    ->middleware(['auth'])
    ->name('files');

require __DIR__.'/auth.php';
