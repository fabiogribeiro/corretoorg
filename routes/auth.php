<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');

    // Social login routes
    Route::controller(SocialAuthController::class)->group(function () {
        Route::get('auth/github', 'redirectToGithub')
            ->name('auth.github');
        Route::get('auth/github/callback', 'handleGithubCallback');

        Route::get('auth/apple', 'redirectToApple')
            ->name('auth.apple');
        Route::get('auth/apple/callback', 'handleAppleCallback');

        Route::get('auth/facebook', 'redirectToFacebook')
            ->name('auth.facebook');
        Route::get('auth/facebook/callback', 'handleFacebookCallback');

        Route::get('auth/google', 'redirectToGoogle')
            ->name('auth.google');
        Route::get('auth/google/callback', 'handleGoogleCallback');
    });
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
