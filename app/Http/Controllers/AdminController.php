<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Facebook;

class AdminController extends Controller
{
    /**
     * @param Request $req
     * @return $this|array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function verifyPost(Request $req){
        $isAdmin = session('isAdmin', false);
        
        if(!$isAdmin){
            return ['text' => 'Hey, you are not admin'];
        }


        if($req->method() == 'GET'){
            //        return ['text' => 'welcome to admin page'];
            /**
             * Load current post for admin verify
             */
            $posts = Post::where('status', 'pending')->paginate(2);
//        $posts = DB::table('posts')->paginate(15);

            return view('admins.verify-post')->with(compact('posts'));
        }

        if($req->method() == 'POST'){
            $action = $req->get('action');
            $postId = $req->get('postId');

            $post = Post::find($postId);
            $fb = new Facebook\Facebook([
                'app_id' => '1282309335173913',
                'app_secret' => 'd27cdfa5fcb1c92a079552d878bc3dae',
                'default_graph_version' => 'v2.8'
            ]);

            $pageAccessToken = env('PAGE_ACCESS_TOKEN');
            $fb->setDefaultAccessToken($pageAccessToken);

            $now = date('Y/m/dTH:m:s');
            $data = [
                'message' => "{$post->content} {$now}"
            ];

            $postUrl = '/1582722098684919/feed';

            /**
             * Case post with image
             * Save to photo
             */
            if(!empty($post->photo_path)){
//                $data['source'] = $fb->fileToUpload(storage_path($post->photo_path));
//                $data['source'] = $fb->fileToUpload(asset($post->photo_path));
                try{
//                    $data['source'] = $fb->fileToUpload('https://tinker.press/demon.gif');
//                    $data['source'] = $fb->fileToUpload('http://localhost:8000/photos/add.gif');
                   $data['source'] = $fb->fileToUpload(storage_path($post->photo_path));
                    // $data['source'] = $fb->fileToUpload(asset($post->photo_path));
                }catch(\Exception $e){
                    return $e->getMessage();
                }
                $postUrl = '/me/photos';
            }

            try {
                $res = $fb->post($postUrl, $data);
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                return $e->getMessage();
                exit;
            }

            $graphNode = $res->getGraphNode();

            return response($graphNode->getField('id'), 200, ['Content-Type' => 'application/json']);
        }

    }
    
//    public function 
}
