<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function verifyPost(){
        $isAdmin = session('isAdmin', false);
        
        if(!$isAdmin){
            return ['text' => 'Hey, you are not admin'];
        }
        
//        return ['text' => 'welcome to admin page'];
        /**
         * Load current post for admin verify
         */
        $post = Post::where();


        return view('admins.verify-post');
    }
    
//    public function 
}
