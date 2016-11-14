<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\UserRole;
use Facebook;

session_start();

class SocialLoginController extends Controller{

    public function facebookLogin(){
        $fb = new Facebook\Facebook([
            'app_id' => '1282309335173913',
            'app_secret' => 'd27cdfa5fcb1c92a079552d878bc3dae',
            'default_graph_version' => 'v2.8'
        ]);

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes', 'user_posts', 'manage_pages', 'publish_pages']; // optional
        $loginUrl = $helper->getLoginUrl(url('facebook-login-callback'), $permissions);

        return view('social-logins.facebook-login')->with(compact('loginUrl'));
    }

    public function handleProviderCallback(){
        $fb = new Facebook\Facebook([
            'app_id' => '1282309335173913',
            'app_secret' => 'd27cdfa5fcb1c92a079552d878bc3dae',
            'default_graph_version' => 'v2.8',
            //'default_access_token' => '{access-token}', // optional
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // There was an error communicating with Graph
            echo $e->getMessage();
            exit;
        }

        if (isset($accessToken)) {
            // User authenticated your app!
            // Save the access token to a session and redirect
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            // Log them into your web framework here . . .
            // Redirect here . . .
            $response = $fb->get('/me', $accessToken);
//            dd($response);
            $graphNode = $response->getGraphNode();
        } elseif ($helper->getError()) {
            // The user denied the request
            // You could log this data . . .
            var_dump($helper->getError());
            var_dump($helper->getErrorCode());
            var_dump($helper->getErrorReason());
            var_dump($helper->getErrorDescription());
            // You could display a message to the user
            // being all like, "What? You don't like me?"
            exit;
        }
//        dd($user);
//        $userId = $user->id; //1147326748637566
//        $userId = $user->getId(); //1147326748637566

        /**
         * Login here, only allowed id can log into admin-page
         */
        $usersRoleAdmin = UserRole::where('role', 'admin')->get();
        $user = $graphNode;
        $isAdmin = $usersRoleAdmin
                ->filter(function ($userRoleAdmin) use ($user){
                    return $userRoleAdmin->provider_id == $user->getField('id');
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
