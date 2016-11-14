<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

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
        $posts = Post::where('status', 'pending')->paginate(2);
//        $posts = DB::table('posts')->paginate(15);

        return view('admins.verify-post')->with(compact('posts'));
    }
    
//    public function 
}
