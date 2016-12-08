<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function post(Request $req){
        $content = $req->get('content');
        $photo_path = '';
        // g-recaptcha-response
        $gRecaptchaRes = $req->get('g-recaptcha-response');
        $isHuman = $this->validateCaptcha($gRecaptchaRes);
        if(!$isHuman){
            flash('reCAPTCHA validate failed', 'danger');

            return redirect('');
        }

        /**
         * Save file if has
         */
        if ($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $fileName = $photo->getClientOriginalName();
            $photo_path = $photo->storeAs('photos', $fileName);
        }

        $post = new Post(compact('content', 'photo_path'));
        $post->save();

        return redirect('post-success');
    }
    
    public function index(){
        return view('posts.post');
    }
    
    private function validateCaptcha($gRecaptchaRes){
        $secrete = env('GOOGLE_RECAPTCHA_SECRET');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.google.com/recaptcha/api/siteverify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"secret\"\r\n\r\n{$secrete}\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"response\"\r\n\r\n{$gRecaptchaRes}\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "postman-token: dd9b871d-73f2-5512-2274-c2a6fd5fcb32"
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            throw $err;
        } else {
            $resObj = json_decode($response, true);
            return $resObj['success'];
        }
    }
    
    public function postSuccess(){
        return view('posts.post-success');
    }
    
}
