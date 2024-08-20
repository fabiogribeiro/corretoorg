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
}
