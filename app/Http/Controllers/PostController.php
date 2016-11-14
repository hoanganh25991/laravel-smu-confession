<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function post(Request $req){
        $status = $req->get('status');
        $photo_path = '';
        // validate the user-entered Captcha code when the form is submitted
        $captChaCode = $req->get('CaptchaCode');
        $isHuman = captcha_validate($captChaCode);

        if (!$isHuman) {
            // TODO: Captcha validation passed, perform protected  action
//            return response(['msg' => 'Who\'re you? Please don\'t hack my side']);
            $post = (object) compact('status');
            flash('Please retype your captcha');
            return view('posts.post')->with(compact('post'));
        }

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
        return view('posts.post');
    }
    
}
