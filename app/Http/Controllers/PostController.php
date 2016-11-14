<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function post(Request $req){
        $status = $req->get('status');

        $photo_path = '';
        /**
         * Save file if has
         */
        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $fileName = $photo->getClientOriginalName();
            $photo_path = $photo->storeAs('photos', $fileName);
        }

        $post = new Post(compact('status', 'photo_path'));
        $post->save();

        return $post;
    }
    
    public function index(){
        return view('home');
    }
    
}
