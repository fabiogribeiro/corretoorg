<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\UserController;

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

require __DIR__.'/auth.php';
