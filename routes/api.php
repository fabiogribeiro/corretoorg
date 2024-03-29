<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChallengeController;
use App\Http\Middleware\AuthenticateOnceWithBasicAuth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(ChallengeController::class)->group(function() {
    Route::post('challenges', 'post')
        ->middleware(AuthenticateOnceWithBasicAuth::class);

    Route::get('challenges/{id}', 'get');

    Route::put('challenges/{id}', 'put')
        ->middleware(AuthenticateOnceWithBasicAuth::class);
});
