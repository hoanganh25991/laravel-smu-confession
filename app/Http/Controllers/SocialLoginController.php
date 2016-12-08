<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\UserRole;
use Facebook;

session_start();

class SocialLoginController extends Controller{
    protected $fb;
    
    public function __construct(){
        $this->fb = new Facebook\Facebook([
            'app_id' => '1282309335173913',
            'app_secret' => 'd27cdfa5fcb1c92a079552d878bc3dae',
            'default_graph_version' => 'v2.8'
        ]);
    }
    

    public function facebookLogin(){
        $fb = $this->fb;

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes', 'user_posts', 'manage_pages', 'publish_pages']; // optional
        $loginUrl = $helper->getLoginUrl(url('admin/facebook-login-callback'), $permissions);

        return view('admins.admin-login')->with(compact('loginUrl'));
    }

    public function handleProviderCallback(){
        $fb = $this->fb;

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            $response = $fb->get('/me', $accessToken);
            $graphNode = $response->getGraphNode();
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // There was an error communicating with Graph
            echo $e->getMessage();
            exit;
        }

        /**
         * Login here, only allowed id can log into admin-page
         */
        $usersRoleAdmin = UserRole::where('role', 'admin')->get();
        $user = $graphNode;
        $userFacebookId = $user->getField('id');
        $isAdmin = $usersRoleAdmin
                ->filter(function ($userRoleAdmin) use ($userFacebookId){
                    return $userRoleAdmin->provider_id == $userFacebookId;
                })
                ->count()
                >= 1;

        session(['isAdmin' => $isAdmin]);
        $redirectUrl = $isAdmin ? 'admin' : '';
        flash("Facebook id: {$user->getField('id')}");

        /**
         * Just store for easy check
         */

        $newUserRole = UserRole::where('provider_id', $userFacebookId)->first();
        if(empty($newUserRole)){
            $newUserRole = new UserRole();
        }
        $newUserRole->provider_id = $userFacebookId;
        $newUserRole->name = $user->getField('name');
        $newUserRole->save();

        return redirect($redirectUrl);
    }
    
    public function getNewFacebookId(Request $req){
        $fb = $this->fb;

        if(!isset($_SESSION['facebook_access_token'])){
            flash('Please connect to facebook first');

            return redirect('admin/login');
        }

        $fbId = $req->get('fbId');
        $accessToken = $_SESSION['facebook_access_token'];
        $response = $fb->get("/{$fbId}", $accessToken);
        $graphNode = $response->getGraphNode();

        return $graphNode;
    }
}
