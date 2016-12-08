<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Facebook;
use App\Config;

class AdminController extends Controller{
    /**
     * @param Request $req
     * @return $this|array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function verifyPost(Request $req){
        $this->checkFacebookLogin();
        $confessionIdConfig = Config::where('key', 'lastConfessionId')->first();
        $lastConfessionId = $confessionIdConfig->value;
        $nextConfessionId = $lastConfessionId + 1;

        if($req->method() == 'GET'){
            $posts = Post::where('status', 'pending')->orderBy('created_at', 'desc')->paginate(5);

            return view('admins.admin')->with(compact('posts', 'nextConfessionId'));
        }

        if($req->method() == 'POST'){
            $action = $req->get('action');
            $postId = $req->get('postId');

            $post = Post::find($postId);
            /**
             * Discard
             */
            if($action == 'discard'){
                $post->delete();
                return response(['msg' => "postId {$postId}: deleted"], 200, ['Content-Type' => 'application/json']);
            }

            /**
             * Approve
             */
            $fb = new Facebook\Facebook([
                'app_id' => '1282309335173913',
                'app_secret' => 'd27cdfa5fcb1c92a079552d878bc3dae',
                'default_graph_version' => 'v2.8'
            ]);

            $pageAccessToken = env('PAGE_ACCESS_TOKEN');
            $fb->setDefaultAccessToken($pageAccessToken);

            $data = ['message' => "Confession #{$nextConfessionId}\n{$post->content}"];
            $postUrl = '/1582722098684919/feed';
            try{
                /**
                 * Case post with image
                 * Save to photo
                 */
                if(!empty($post->photo_path)){
                    $data['source'] = $fb->fileToUpload(storage_path($post->photo_path));
                    $postUrl = '/me/photos';
                }
                $res = $fb->post($postUrl, $data);
                $graphNode = $res->getGraphNode();
            }catch(Facebook\Exceptions\FacebookSDKException $e){
                return $e->getMessage();
                exit;
            }

            /**
             * Post to page success
             * 1. Change post status
             * 2. Update lastConfessionId
             */
            $post->status = 'approved';
            $post->save();

            $confessionIdConfig->valule = $nextConfessionId;
            $confessionIdConfig->save();

            return response($graphNode->getField('id'), 200, ['Content-Type' => 'application/json']);
        }

    }

    private function checkFacebookLogin(){
        $isAdmin = session('isAdmin');
        if(!$isAdmin){
            return redirect('admin/login');
        }
    }
}
