<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\UserRole;

class SocialLoginController extends Controller{

    public function facebookLogin(){
        return view('social-logins.facebook-login');
    }

    public function redirectToProvider(){
        return Socialite::with('facebook')->redirect();
    }

    public function handleProviderCallback(){
        $user = Socialite::with('facebook')->user();

//        dd($user);
//        $userId = $user->id; //1147326748637566
//        $userId = $user->getId(); //1147326748637566

        /**
         * Login here, only allowed id can log into admin-page
         */
        $usersRoleAdmin = UserRole::where('role', 'admin')->get();

        $isAdmin = $usersRoleAdmin
                ->filter(function ($userRoleAdmin) use ($user){
                    return $userRoleAdmin->provider_id == $user->id;
                })
                ->count()
                >= 1;

//        dd($isAdmin);
        if($isAdmin){
            session(['isAdmin' => true]);

//            dd(session('isAdmin'));

            return redirect()->route('admin');
        }
        
        if(!$isAdmin){
            session('isAdmin', false);
            
            return redirect()->route('home');
        }
    }
}
