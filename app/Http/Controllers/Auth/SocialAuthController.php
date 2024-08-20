<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::where('email', $githubUser->email)->orWhere('github_id', $githubUser->id)->first();
        if ($user) {
            $user->update([
                'github_id' => $githubUser->id,
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        }
        else {
            $user = User::create([
                'github_id' => $githubUser->id,
                'email' => $githubUser->email,
                'name' => $githubUser->name,
                'password' => Str::password(),
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }

    public function redirectToApple()
    {
        return Socialite::driver('apple')->redirect();
    }

    public function handleAppleCallback()
    {
        $appleUser = Socialite::driver('apple')->user();

        $user = User::where('email', $appleUser->email)->orWhere('apple_id', $appleUser->id)->first();
        if ($user) {
            $user->update([
                'apple_id' => $appleUser->id,
                'apple_token' => $appleUser->token,
                'apple_refresh_token' => $appleUser->refreshToken,
            ]);
        } else {
            $user = User::create([
                'apple_id' => $appleUser->id,
                'email' => $appleUser->email,
                'name' => $appleUser->name,
                'password' => Str::password(),
                'apple_token' => $appleUser->token,
                'apple_refresh_token' => $appleUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->user();

        $user = User::where('email', $facebookUser->email)->orWhere('facebook_id', $facebookUser->id)->first();
        if ($user) {
            $user->update([
                'facebook_id' => $facebookUser->id,
                'facebook_token' => $facebookUser->token,
                'facebook_refresh_token' => $facebookUser->refreshToken,
            ]);
        } else {
            $user = User::create([
                'facebook_id' => $facebookUser->id,
                'email' => $facebookUser->email,
                'name' => $facebookUser->name,
                'password' => Str::password(),
                'facebook_token' => $facebookUser->token,
                'facebook_refresh_token' => $facebookUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->email)->orWhere('google_id', $googleUser->id)->first();
        if ($user) {
            $user->update([
                'google_id' => $googleUser->id,
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);
        } else {
            $user = User::create([
                'google_id' => $googleUser->id,
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'password' => Str::password(),
                'google_token' => $googleUser->token,
                'google_refresh_token' => $googleUser->refreshToken,
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/dashboard');
    }
}
