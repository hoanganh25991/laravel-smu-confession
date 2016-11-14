<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function post(Request $req){
        $status = $req->get('status');
        $photo = $req->get('photo');

        $photo_path = '';
        /**
         * Save file if has
         */
        $post = new Post(compact('status', 'photo_path'));
        $post->save();

        return $post;
//        dd($status);
    }
    
    public function index(){
        return view('home');
    }
    
}
