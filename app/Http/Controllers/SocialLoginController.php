<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;

class SocialLoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::with('facebook')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::with('facebook')->user();

        dd($user);
    }
}
