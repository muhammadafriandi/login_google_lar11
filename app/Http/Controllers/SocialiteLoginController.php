<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SocialiteLogin;
use Laravel\Socialite\Facades\Socialite;

class SocialiteLoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        $socialiteuser = Socialite::driver('google')->user();

        $user = User::firstOrCreate(
            ['email' => $socialiteuser->getEmail()],
            ['name' => $socialiteuser->getName()],
        );
        SocialiteLogin::firstOrCreate(
            ['user_id' => $user->id, 'provider' => 'google'],
            ['provider_id' => $socialiteuser->getId()],
        );

        auth()->login($user);
        return to_route('home');
    }
}
